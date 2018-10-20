<?php

namespace App\Traits;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
  private function success($data,$code)
  {
    return response()->json(['data'=>$data],$code);
  }

  protected function error($message,$code)
  {
    return response()->json(['error'=> $message,'code'=>$code],$code);
  }

  protected function showAll(Collection $collection, $code =200)
  {
    return  $this->success($collection,$code);
  }

  protected function showOne(Model $model, $code =200)
  {
    return  $this->success($model,$code);
  }
}
