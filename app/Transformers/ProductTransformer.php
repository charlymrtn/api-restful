<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Models\Product;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
      return [
          'identifier' => (string)$product->uuid,
          'title' => (string)$product->name,
          'details' => (string)$product->description,
          'stock' => (int)$product->quantity,
          'available' => (string)$product->status,
          'photo' => url("img/{$product->image}"),
          'seller' => (string)$product->seller_uuid,
          'creation' => (string)$product->created_at->format('d/m/Y')
      ];
    }

    public static function originalAttribute($index)
    {
      $attributes = [
        'identifier' => 'uuid',
        'title' => 'name',
        'details' => 'description',
        'stock' => 'quantity',
        'available' => 'status',
        'photo' => 'image',
        'seller' => 'seller_uuid',
        'creation' => 'created_at'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
