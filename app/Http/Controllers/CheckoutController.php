<?php

namespace App\Http\Controllers;

use App\Classes\Enums\CheckoutOptions;
use App\Models\Checkout;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page with the user's shopping cart products, total price, and user details.
     *
     * @return RedirectResponse|View
     */
    public function index(): RedirectResponse|View
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route("login");
        }

        $shoppingCart = ShoppingCart::where("user_id", $user->id)->first();
        if (!$shoppingCart) {
            return redirect()->route("shopping-cart.index");
        }

        $shoppingCartId = $shoppingCart->id;
        $shoppingCartProducts = $shoppingCart->getShoppingCartProducts();

        $totalPrice = $shoppingCartProducts->sum('total_price');
        $name = $user->name;

        return view("checkout", compact("shoppingCartProducts", "totalPrice", "name", "shoppingCartId"));
    }

    /**
     * Processes the checkout for the authenticated user's shopping cart.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function process(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route("login");
        }

        $request->validate([
            "shopping_cart_id" => "required|exists:shopping_carts,id",
        ]);

        if ($user->shoppingCart->id !== (int) $request->input("shopping_cart_id")) {
            return redirect()->route("checkout.index")->with("error", "Shopping cart not found.");
        }

        $shoppingCart = ShoppingCart::find($request->shopping_cart_id);
        if (!$shoppingCart) {
            return redirect()->route("checkout.index")->with("error", "Shopping cart not found.");
        }

        $shoppingCartProducts = $shoppingCart->getShoppingCartProducts();
        $totalPrice = $shoppingCart->getTotalPrice();

        try {
            DB::transaction(function () use ($shoppingCartProducts, $shoppingCart, $totalPrice, $user) {
                foreach ($shoppingCartProducts as $shoppingCartProduct) {
                    /* @var ShoppingCartProduct $shoppingCartProduct */
                    $product = $shoppingCartProduct->product;
                    $product->stock_quantity -= $shoppingCartProduct->quantity;

                    if ($product->stock_quantity < 0) {
                        throw new Exception("Product {{ $product->name }} is out of stock.");
                    }

                    $product->save();
                }

                $checkout = Checkout::create([
                    "user_id" => $user->id,
                    "shopping_cart_id" => $shoppingCart->id,
                    "total_price" => $totalPrice,
                    "status" => CheckoutOptions::COMPLETED->value,
                ]);

                if (!$checkout) {
                    throw new Exception("Failed to create checkout.");
                }

                // Empty the shopping cart
                $shoppingCart->delete();
            });
        } catch (Exception $e) {
            return redirect()->route("checkout.index")->with("error", $e->getMessage());
        }

        return redirect()->route("shopping-cart.index")->with("status", "Checkout created successfully.");
    }
}
