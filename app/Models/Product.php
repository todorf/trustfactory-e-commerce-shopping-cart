<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ["name", "price", "stock_quantity"];

    protected $casts = [
        "price" => "decimal:2",
    ];

    public function shoppingCartProducts(): HasMany
    {
        return $this->hasMany(ShoppingCartProduct::class);
    }
}
