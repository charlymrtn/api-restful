<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInput =[];
        //asi solo tomamos los compos del input e ignoramos los query params
        foreach ($request->request->all() as $input => $value) {
          $transformedInput[$transformer::originalAttribute($input)] = $value;
        }
        $request->replace($transformedInput);

        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceOf ValidationException) {
          $data = $response->getData();

          $tranformedErrors = [];

          foreach ($data->error as $field => $error) {
            $tranformedField = $transformer::transformAttribute($field);
            $tranformedErrors[$tranformedField] = str_replace($field, $tranformedField, $error);
          }

          $data->error = $tranformedErrors;

          $response->setData($data);
        }

        return $response;
    }
}
