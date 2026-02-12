<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final readonly class ProductQuery
{
    /** @param  array{}  $args */
    public function builder(null $_, array $args): Builder
    {
        $query = Product::query();

        if (!empty($args['filter'])) {
            $filter = $args['filter'];

            $likeColumns = [
                'slug',
                'shopify_id',
                'title',
                'status',
                'product_type'
            ];

            foreach ($likeColumns as $column) {
                if (!empty($filter[$column])) {
                    $query->where($column, 'like', "%{$filter[$column]}%");
                }
            }
        }

        return $query;
    }
}
