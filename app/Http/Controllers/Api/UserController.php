<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Http\Resources\User as UserResource;

use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
          'name' => 'required|string',
          'email' => 'required|email|unique:users',
          'password' => 'required|min:3|confirmed',
        ];

        $this->validate($request,$rules);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generateToken();
        $campos['admin'] = User::USUARIO_NO_ADMINISTRADOR;

        $user = User::create($campos);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, String $id)
    {
      $user = User::findOrFail($id);

      $rules = [
        'email' => [
        'required',
        Rule::unique('users')->ignore($user->uuid, 'uuid'),
      ],
        'password' => 'min:3|confirmed',
        'admin' => 'in:'.User::USUARIO_ADMINISTRADOR.','.User::USUARIO_NO_ADMINISTRADOR,
      ];

      $this->validate($request,$rules);

      if($request->has('name')){
        $user->name = $request->name;
      }

      if($request->has('email') && $user->email != $request->email) {
          $user->verified = User::USUARIO_NO_VERIFICADO;
          $user->verification_token = User::generateToken();
          $user->email = $request->email;
      }

      if($request->has('password')){
        $user->password = bcrypt($request->password);
      }

      if($request->has('admin')){
        if (!$user->verificado()) {
          return response()->json(['error' => 'only verified users can change their admin value','code' => 409],409);
        }

        $user->admin = $request->admin;
      }

      if (!$user->isDirty()) {
        return response()->json(['error' => 'at least must have one different value to update','code' => 422],422);
      }

      $user->save();

      return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $user = User::findOrFail($id);

      $user->delete();

      return new UserResource($user);
    }
}
