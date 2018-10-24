<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Models\Buyer;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
      return [
          'identifier' => (string)$buyer->uuid,
          'full-name' => (string)$buyer->name,
          'mail' => (string)$buyer->email,
          'verification' => (boolean)$buyer->verified,
          'creation' => (string)$buyer->created_at->format('d/m/Y'),
          'links' => [
            [
              'rel' => 'self',
              'href' => route('buyers.show',$buyer->uuid)
            ],
            [
              'rel' => 'buyers.categories',
              'href' => route('buyers.categories',$buyer->uuid)
            ],
            [
              'rel' => 'buyers.products',
              'href' => route('buyers.products',$buyer->uuid)
            ],
            [
              'rel' => 'buyers.sellers',
              'href' => route('buyers.sellers',$buyer->uuid)
            ],
            [
              'rel' => 'buyers.transactions',
              'href' => route('buyers.transactions',$buyer->uuid)
            ],
            [
              'rel' => 'users',
              'href' => route('users.show', $buyer->uuid)
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
}
