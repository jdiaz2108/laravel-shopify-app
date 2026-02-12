<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\SlugGenerator;
use App\Traits\UseSlugAsKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use UseSlugAsKey;

    protected $fillable = [
        'shopify_id',
        'title',
        'description',
        'vendor',
        'product_type',
        'handle',
        'status',
        'featured_image_url',
        'synced_at',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        self::creating(function (Product $model): void {
            $model['slug'] = SlugGenerator::generate(length: 10);
        });
    }
}
