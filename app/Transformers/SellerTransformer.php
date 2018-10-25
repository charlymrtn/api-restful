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
          'creation' => (string)$seller->created_at->format('d/m/Y'),
          'links' => [
            [
              'rel' => 'self',
              'href' => route('sellers.show',$seller->uuid)
            ],
            [
              'rel' => 'sellers.buyers',
              'href' => route('sellers.buyers',$seller->uuid)
            ],
            [
              'rel' => 'sellers.categories',
              'href' => route('sellers.categories',$seller->uuid)
            ],
            [
              'rel' => 'sellers.products',
              'href' => route('sellers.products.index',$seller->uuid)
            ],
            [
              'rel' => 'sellers.transactions',
              'href' => route('sellers.transactions',$seller->uuid)
            ],
            [
              'rel' => 'users',
              'href' => route('users.show', $seller->uuid)
            ]
          ]
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

    public static function transformAttribute($index)
    {
      $attributes = [
        'uuid' => 'identifier' ,
        'name' => 'full-name' ,
        'email' => 'mail' ,
        'verified' => 'verification' ,
        'created_at' => 'creation'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
