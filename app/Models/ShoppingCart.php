<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingCart extends Model
{
    use SoftDeletes;

    protected $fillable = ["user_id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ShoppingCartProduct::class);
    }

    public function getShoppingCartProducts(): Collection
    {
        $shoppingCartProducts = $this->products()->get();

        foreach ($shoppingCartProducts as $shoppingCartProduct) {
            /* @var ShoppingCartProduct $shoppingCartProduct */
            $shoppingCartProduct->name = $shoppingCartProduct->getNameAttribute();
            $shoppingCartProduct->total_price = $shoppingCartProduct->getTotalPriceAttribute();
        }

        return $shoppingCartProducts;
    }

    public function getTotalPrice(): float
    {
        return $this->getShoppingCartProducts()->sum('total_price');
    }
}
