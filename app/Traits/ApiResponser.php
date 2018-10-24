<?php

namespace App\Traits;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Pagination\LengthAwarePaginator;

use Validator;
use Cache;

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
    $collection = $this->paginate($collection);
    $collection = $this->transform($collection,$transformer);

    $collection = $this->cache($collection);

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

  protected function paginate(Collection $collection)
  {
    $rules = [
      'per_page' => 'integer|min:2|max:20'
    ];

    Validator::validate(request()->all(),$rules);

    $page = LengthAwarePaginator::resolveCurrentPage();

    $perPage = 15;
    if (request()->has('per_page')) {
      $perPage = (int)request()->per_page;
    }

    $results = $collection->slice(($page-1)*$perPage,$perPage)->values();

    $pagination =new LengthAwarePaginator($results,$collection->count(),$perPage,$page,[
      'path' => LengthAwarePaginator::resolveCurrentPath()
    ]);

    $pagination->appends(request()->all());
    return $pagination;
  }

  protected function cache($data)
  {
    $url = request()->url();
    $query =request()->query();

    ksort($query);

    $queryStr = http_build_query($query);

    $fullUrl = "{$url}?{$queryStr}";

    return Cache::remember($fullUrl, 30/60, function() use($data){
      return $data;
    });
  }


}
