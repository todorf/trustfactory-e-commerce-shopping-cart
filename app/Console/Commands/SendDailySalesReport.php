<?php

namespace App\Console\Commands;

use App\Classes\Enums\CheckoutOptions;
use App\Mail\DailySalesReport;
use App\Models\Checkout;
use App\Models\ShoppingCartProduct;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:report-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report to admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating daily sales report...');

        $today = now()->startOfDay();

        // Get all completed checkouts from today
        $checkouts = Checkout::where('status', CheckoutOptions::COMPLETED->value)
            ->whereDate('created_at', $today->toDateString())
            ->get();

        if ($checkouts->isEmpty()) {
            $this->info('No sales found for today.');
            return Command::SUCCESS;
        }

        $productsSold = [];
        $totalPrice = 0;
        $totalCheckouts = $checkouts->count();

        foreach ($checkouts as $checkout) {
            $totalPrice += $checkout->total_price;

            // Get shopping cart products (even if cart is soft-deleted)
            $cartProducts = ShoppingCartProduct::where('shopping_cart_id', $checkout->shopping_cart_id)
                ->with('product')
                ->get();

            foreach ($cartProducts as $cartProduct) {
                $productId = $cartProduct->product_id;
                $productName = $cartProduct->product ? $cartProduct->product->name : 'Unknown Product';

                if (!isset($productsSold[$productId])) {
                    $productsSold[$productId] = [
                        'name' => $productName,
                        'quantity' => 0,
                        'total_price' => 0,
                    ];
                }

                $productsSold[$productId]['quantity'] += $cartProduct->quantity;
                $productsSold[$productId]['total_price'] += $cartProduct->price * $cartProduct->quantity;
            }
        }

        // Get admin user
        $admin = User::where('email', env('ADMIN_DUMMY_EMAIL', 'admin@example.com'))->first();
        if (!$admin) {
            $this->error('Admin user not found. Please set ADMIN_DUMMY_EMAIL in your .env file.');
            return Command::FAILURE;
        }

        // Send email
        try {
            Mail::to($admin->email)->send(new DailySalesReport(
                $productsSold,
                $totalPrice,
                $totalCheckouts,
                $today
            ));

            $this->info("Daily sales report sent successfully to {$admin->email}");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error("Failed to send daily sales report: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
