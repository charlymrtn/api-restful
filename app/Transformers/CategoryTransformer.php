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
          'creation' => (string)$category->created_at->format('d/m/Y')
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
}
