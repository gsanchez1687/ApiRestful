<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserupdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users,200);
    }

    //Uso de inyeccion de dependencias
    // User $user
    public function show(User $user)
    {
        return $this->showOne($user,200);
        
    }

    public function store(UserRequest $request){

        $requests = $request->all();
        $requests['password'] = bcrypt($request->password);
        $requests['verifed'] = User::USUARIO_NO_VERIFICADO;
        $requests['verification_token'] = User::generarverificacionToken();
        $requests['admin'] = User::USUARIO_REGULAR;
       

        $user = User::create($requests);
        if( !empty($user) && isset($user) )
            return $this->showOne($user,201);
        else
            abort(500);    
    }

    public function update(UserupdateRequest $request, $id){

        $user = User::findOrFail($id);
        if($request->has('name'))
            $user->name = $request->name;

        if( $request->has('email') && $user->email != $request->email ){
            $user->verifed  = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarverificacionToken();
            $user->email = $request->email;
        }
        if( $request->has('password') )
            $user->password = bcrypt($request->password);

        if( $request->has('admin') ){
            if( !$user->UsuarioVerificado() ){
                return $this->errorResponse('solo los usuarios verificados pueden cambiar su valor de administracion',409);
            }
            $user->admin = $request->admin;
                
        }

        if(!$user->isDirty()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar',422);
        }

        $user->save();
        return $this->showOne($user,200);
                
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->showOne($user,200);
    }
}
