<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class ApiController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function allowAdmin()
    {
      if (Gate::denies('admin-action')) {
        throw new AuthorizationException("This action is forbidden");
      }
    }
}
