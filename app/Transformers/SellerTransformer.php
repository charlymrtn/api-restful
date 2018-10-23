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
          'verified' => (boolean)$seller->verified,
          'creation' => (string)$seller->created_at->format('d/M/Y')
      ];
    }
}
