<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\ApiResponser;

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
}
