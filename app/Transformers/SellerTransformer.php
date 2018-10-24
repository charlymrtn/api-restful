<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Models\Seller;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
      return [
          'identifier' => (string)$seller->uuid,
          'full-name' => (string)$seller->name,
          'mail' => (string)$seller->email,
          'verification' => (boolean)$seller->verified,
          'creation' => (string)$seller->created_at->format('d/m/Y')
      ];
    }

    public static function originalAttribute($index)
    {
      $attributes = [
        'identifier' => 'uuid',
        'full-name' => 'name',
        'mail' => 'email',
        'verification' => '>verified',
        'creation' => 'created_at'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
