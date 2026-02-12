<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\SlugGenerator;
use App\Traits\UseSlugAsKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;
    use UseSlugAsKey;

    protected $fillable = [
        'product_id',
        'shopify_id',
        'title',
        'sku',
        'price',
        'inventory_quantity',
        'synced_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        self::creating(function (ProductVariant $model): void {
            $model['slug'] = SlugGenerator::generate(length: 10);
        });
    }
}
