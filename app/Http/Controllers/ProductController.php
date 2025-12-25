<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(15);

        return view("products.index", compact("products"));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view("products.create");
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "price" => ["required", "numeric", "min:0", "max:999999.99"],
            "stock_quantity" => ["required", "integer", "min:0"],
        ]);

        Product::create($validated);

        return redirect()
            ->route("products.index")
            ->with("status", "Product created successfully.");
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        return view("products.show", compact("product"));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        return view("products.edit", compact("product"));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "price" => ["required", "numeric", "min:0", "max:999999.99"],
            "stock_quantity" => ["required", "integer", "min:0"],
        ]);

        $product->update($validated);

        return redirect()
            ->route("products.index")
            ->with("status", "Product updated successfully.");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route("products.index")
            ->with("status", "Product deleted successfully.");
    }
}
