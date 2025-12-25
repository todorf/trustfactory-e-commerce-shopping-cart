<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShoppingCartController extends Controller
{
    private const QUANTITY_UPDATE_DECREASE = "decrease";
    private const QUANTITY_UPDATE_INCREASE = "increase";

    /**
     * Display a listing of products in the authenticated user's shopping cart.
     *
     * @return RedirectResponse|View
     */
    public function index(): RedirectResponse|View
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route("login");
        }

        $shoppingCart = ShoppingCart::firstOrCreate([
            "user_id" => $user->id,
        ]);

        $shoppingCartProducts = $shoppingCart->products()->get();

        foreach ($shoppingCartProducts as $shoppingCartProduct) {
            $shoppingCartProduct->name = $shoppingCartProduct->product ? $shoppingCartProduct->product->name : '';
            $shoppingCartProduct->total_price = $shoppingCartProduct->price * $shoppingCartProduct->quantity;
        }

        return view("shopping-cart.index", compact("shoppingCartProducts"));
    }

    /**
     * Add a product to the authenticated user's shopping cart.
     *
     * If the product is out of stock, flashes an error message and redirects to the product page.
     * If the product doesn't exist in the cart, adds one with quantity 1; otherwise, increases quantity by 1.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function add(Product $product): RedirectResponse
    {
        if ($product->stock_quantity <= 0) {
            session()->flash("error", __("Product is out of stock."));
            return redirect()->route("products.show", $product);
        }

        $shoppingCart = ShoppingCart::firstOrCreate([
            "user_id" => auth()->user()->id,
        ]);

        $shoppingCartProduct = $shoppingCart->products()->where("product_id", $product->id)->first();

        if (!$shoppingCartProduct) {
            $shoppingCart->products()->create([
                "product_id" => $product->id,
                "quantity" => 1,
                "price" => $product->price,
            ]);
        } else {
            $shoppingCartProduct->update([
                "quantity" => $shoppingCartProduct->quantity + 1,
                "price" => $product->price,
            ]);
        }

        session()->flash("status", "Product added to cart successfully.");

        return redirect()->route("products.show", $product);
    }

    /**
     * Remove a product from the authenticated user's shopping cart.
     *
     * @param ShoppingCartProduct $shoppingCartProduct
     * @return RedirectResponse
     */
    public function remove(ShoppingCartProduct $shoppingCartProduct): RedirectResponse
    {
        $shoppingCartProduct->delete();

        session()->flash("status", "Product removed from cart successfully.");

        return redirect()->route("shopping-cart.index");
    }

    /**
     * Update the quantity of a product in the authenticated user's shopping cart.
     *
     * Increases or decreases the quantity of the given ShoppingCartProduct, or removes
     * the product from the cart if quantity is reduced below 1.
     *
     * @param ShoppingCartProduct $shoppingCartProduct
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(ShoppingCartProduct $shoppingCartProduct, Request $request): RedirectResponse
    {
        $request->validate([
            "quantity_update" => ["required", "string", "in:" . self::QUANTITY_UPDATE_DECREASE . "," . self::QUANTITY_UPDATE_INCREASE],
        ]);

        if ($shoppingCartProduct->quantity <= 1 && $request->quantity_update === self::QUANTITY_UPDATE_DECREASE) {
            $shoppingCartProduct->delete();

            session()->flash("status", __("Product removed from cart successfully."));
            return redirect()->route("shopping-cart.index");
        } else {
            $shoppingCartProduct->update([
                "quantity" => $request->quantity_update === self::QUANTITY_UPDATE_DECREASE ? $shoppingCartProduct->quantity - 1 : $shoppingCartProduct->quantity + 1,
            ]);
        }

        return redirect()->route("shopping-cart.index");
    }
}
