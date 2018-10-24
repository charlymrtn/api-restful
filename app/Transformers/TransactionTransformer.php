<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Models\Transaction;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
      return [
          'identifier' => (string)$transaction->uuid,
          'num_items' => (int)$transaction->quantity,
          'item' => (string)$transaction->product_uuid,
          'buyer' => (string)$transaction->buyer_uuid,
          'creation' => (string)$transaction->created_at->format('d/m/Y'),
          'links' => [
            [
              'rel' => 'self',
              'href' => route('transactions.show',$transaction->uuid)
            ],
            [
              'rel' => 'transactions.categories',
              'href' => route('transactions.categories',$transaction->uuid)
            ],
            [
              'rel' => 'transactions.sellers',
              'href' => route('transactions.sellers',$transaction->uuid)
            ],
            [
              'rel' => 'buyers',
              'href' => route('buyers.show',$transaction->buyer_uuid)
            ],
            [
              'rel' => 'products',
              'href' => route('products.show',$transaction->product_uuid)
            ]
          ]
      ];
    }

    public static function originalAttribute($index)
    {
      $attributes = [
        'identifier' => 'uuid',
        'num_items' => 'quantity',
        'item' => 'product_uuid',
        'buyer' => '>buyer_uuid',
        'creation' => 'created_at'
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
