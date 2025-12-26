<?php

namespace App\Jobs;

use App\Mail\LowStockNotification;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyLowStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Product $product
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $admin = User::where('email', env('ADMIN_DUMMY_EMAIL', 'admin@example.com'))->first();
        if (!$admin) {
            throw new Exception('Admin user not found');
        }

        Mail::to($admin->email)->send(new LowStockNotification($this->product));
    }
}
