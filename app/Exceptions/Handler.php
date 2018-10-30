<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;

use Barryvdh\Cors\CorsService;

use App\Traits\ApiResponser;

class Handler extends ExceptionHandler
{

    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $response = $this->handleException($request,$exception);
        
        app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;
    }

    public function handleException($request, Exception $exception)
    {
      if($exception instanceOf ValidationException){
        return $this->convertValidationExceptionToResponse($exception,$request);
      }

      if($exception instanceOf ModelNotFoundException){
        $model = strtolower(class_basename($exception->getModel()));
        return $this->error($model.' model instance not found',404);
      }

      if($exception instanceOf AuthenticationException){
        return $this->unauthenticated($request,$exception);
      }

      if($exception instanceOf AuthorizationException){
        return $this->error('the user does not have enough permissions',403);
      }

      if($exception instanceOf NotFoundHttpException){
        return $this->error('the path is not found',404);
      }

      if($exception instanceOf MethodNotAllowedHttpException){
        return $this->error('the method is not allowed',405);
      }

      if($exception instanceOf HttpException){
        return $this->error($exception->getMessage(),$exception->getStatusCode());
      }

      if($exception instanceOf QueryException){
        $code = $exception->errorInfo[1];
        if($code == 1451) return $this->error('database error while deleting resources',409);
      }

      if($exception instanceOf TokenMismatchException){
        return redirect()->back()->withInput($request->input());
      }

      if (config('app.debug')) {
        return parent::render($request, $exception);
      }

      return $this->error('Unknown exception',500);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
       $errors = $e->validator->errors()->getMessages();

       if ($this->isFront($request)) {
           return $request->ajax() ? response()->json($errors,422) : redirect()->back()
                                                                               ->withInput($request->input())
                                                                               ->withErrors($errors);
       }

        return $this->error($errors,422);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {

      if ($this->isFront($request)) {
          return redirect()->guest('login');
      }

        return $this->error($exception->getMessage(), 401);
    }

    private function isFront($request)
    {
      return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
