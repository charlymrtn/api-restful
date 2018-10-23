<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Product;

use App\Transformers\CategoryTransformer;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'uuid';

    public $transformer = CategoryTransformer::class;

    protected $fillable = [
        'name', 'description'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $hidden = [
        'deleted_at', 'pivot'
    ];

    public static function boot()
    {
      parent::boot();
      self::creating(function ($model){
        if(empty($model->uuid))
        {
          $model->uuid = Uuid::generate(4)->string;
        }
      });
    }

    public function products()
    {
      return $this->belongsToMany(Product::class,'category_product','product_uuid','category_uuid');
    }
}
