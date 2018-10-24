<?php

namespace App\Traits;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
  private function success($data,$code)
  {
    return response()->json($data,$code);
  }

  protected function error($message,$code)
  {
    return response()->json(['error'=> $message,'code'=>$code],$code);
  }

  protected function showAll(Collection $collection, $code =200)
  {
    if ($collection->isEmpty()) {
      return $this->success(['data' => $collection],404);
    }

    $transformer = $collection->first()->transformer;

    $collection = $this->filter($collection,$transformer);
    $collection = $this->sort($collection,$transformer);
    $collection = $this->transform($collection,$transformer);

    return  $this->success($collection,$code);
  }

  protected function showOne(Model $model, $code =200)
  {
    $transformer = $model->transformer;
    $model = $this->transform($model,$transformer);

    return  $this->success($model,$code);
  }

  protected function message($message,$code = 200)
  {
    return response()->json(['message'=>$message],$code);
  }

  protected function sort(Collection $collection, $transformer)
  {
    if (request()->has('sort_by')) {

      $attribute = $transformer::originalAttribute(request()->sort_by);

      $collection = $collection->sortBy->{$attribute};
    }
    return $collection;
  }

  protected function transform($data, $transformer)
  {
    $transformation = fractal($data, new $transformer);

    return $transformation->toArray();
  }

  protected function filter(Collection $collection, $transformer)
  {
    foreach (request()->query() as $query => $value) {
      $attribute = $transformer::originalAttribute($query);

      if (isset($attribute, $value)) {
        $collection = $collection->where($attribute, $value);
      }
    }
    return $collection;
  }


}
