<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\User;
use App\Http\Resources\User as UserResource;

use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
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

        return $this->showOne($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
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
        if (!$user->verificado) {
          return $this->error('only verified users can change their admin value',409);
        }

        $user->admin = $request->admin;
      }

      if (!$user->isDirty()) {
        return $this->error('at least must have one different value to update',422);
      }

      $user->save();

      return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
      $user->delete();

      return $this->showOne($user);
    }
}
