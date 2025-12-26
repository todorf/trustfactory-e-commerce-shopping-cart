<?php

namespace App\Models;

use App\Jobs\NotifyLowStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public const LOW_STOCK_THRESHOLD = 3;

    protected $fillable = ["name", "price", "stock_quantity"];

    protected $casts = [
        "price" => "decimal:2",
    ];

    protected static function booted(): void
    {
        static::created(function (Product $product) {
            // Check if product is created with low stock
            if ($product->stock_quantity <= self::LOW_STOCK_THRESHOLD) {
                NotifyLowStock::dispatch($product);
            }
        });

        static::updated(function (Product $product) {
            $lowStockThreshold = self::LOW_STOCK_THRESHOLD;

            // Check if stock_quantity was changed
            if ($product->isDirty('stock_quantity')) {
                // Check if stock is now low (below threshold) and was previously above threshold
                $wasAboveThreshold = $product->getOriginal('stock_quantity') > $lowStockThreshold;
                $isNowLow = $product->stock_quantity <= $lowStockThreshold;

                if ($wasAboveThreshold && $isNowLow) {
                    NotifyLowStock::dispatch($product);
                }
            }
        });
    }

    public function shoppingCartProducts(): HasMany
    {
        return $this->hasMany(ShoppingCartProduct::class);
    }
}
