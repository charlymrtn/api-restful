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
          'creation' => (string)$category->created_at->format('d/M/Y')
      ];
    }
}
