<?php

namespace App\Models;

use App\Helpers\SlugGenerator;
use App\Traits\UseSlugAsKey;
use Illuminate\Database\Eloquent\Model;
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

    protected static function boot(): void
    {
        parent::boot();
        self::creating(function (ProductVariant $model) {
            $model['slug'] = SlugGenerator::generate(length: 10);
        });
    }
}
