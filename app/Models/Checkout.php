<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkout extends Model
{
    protected $table = "checkouts";
    protected $fillable = ["user_id", "shopping_cart_id", "total_price", "status"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shoppingCart(): BelongsTo
    {
        return $this->belongsTo(ShoppingCart::class);
    }
}
