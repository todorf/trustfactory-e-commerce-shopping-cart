<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
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

        return view("checkout", compact("shoppingCart"));
    }
}
