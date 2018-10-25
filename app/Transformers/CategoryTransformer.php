<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Models\Category;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
      return [
          'identifier' => (string)$category->uuid,
          'title' => (string)$category->name,
          'details' => (string)$category->description,
          'creation' => (string)$category->created_at->format('d/m/Y'),
          'links' => [
            [
              'rel' => 'self',
              'href' => route('categories.show',$category->uuid)
            ],
            [
              'rel' => 'categories.buyers',
              'href' => route('categories.buyers',$category->uuid)
            ],
            [
              'rel' => 'categories.products',
              'href' => route('categories.products',$category->uuid)
            ],
            [
              'rel' => 'categories.sellers',
              'href' => route('categories.sellers',$category->uuid)
            ],
            [
              'rel' => 'categories.transactions',
              'href' => route('categories.transactions',$category->uuid)
            ]
          ]
      ];
    }

    public static function originalAttribute($index)
    {
      $attributes = [
        'identifier' => 'uuid',
        'title' => 'name',
        'details' => 'description',
        'creation' => 'created_at'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformAttribute($index)
    {
      $attributes = [
        'uuid' => 'identifier',
        'name' => 'title',
        'description' => 'details',
        'created_at' => 'creation'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
