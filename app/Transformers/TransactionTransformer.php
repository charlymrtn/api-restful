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
          'creation' => (string)$transaction->created_at->format('d/m/Y')
      ];
    }
}
