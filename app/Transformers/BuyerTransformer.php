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
          'verified' => (boolean)$buyer->verified,
          'creation' => (string)$buyer->created_at->format('d/m/Y')
      ];
    }
}
