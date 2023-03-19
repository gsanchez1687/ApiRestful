<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserupdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        if( !empty($users) && isset($users) )
            return  response()->json(['data'=>$users],200);
        else
            abort(500);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users = User::findOrFail($id);
        if( !empty($users) && isset($users) )
            return response()->json(['data'=>$users],200);
        else
            abort(500);    
    }

    public function store(UserRequest $request){

        $requests = $request->all();
        $requests['password'] = bcrypt($request->password);
        $requests['verifed'] = User::USUARIO_NO_VERIFICADO;
        $requests['verification_token'] = User::generarverificacionToken();
        $requests['admin'] = User::USUARIO_REGULAR;
       

        $user = User::create($requests);
        if( !empty($user) && isset($user) )
            return response()->json(['data'=>$user],201);
        else
            abort(500);    
    }

    public function update(UserupdateRequest $request, $id){

        $users = User::findOrFail($id);
        if($request->has('name'))
            $users->name = $request->name;

        if( $request->has('email') && $users->email != $request->email ){
            $users->verifed  = User::USUARIO_NO_VERIFICADO;
            $users->verification_token = User::generarverificacionToken();
            $users->email = $request->email;
        }
        if( $request->has('password') )
            $users->password = bcrypt($request->password);

        if( $request->has('admin') ){
            if( !$users->UsuarioVerificado() ){
                return response()->json([
                    'error' => 'Solo los usuario verificados pueden cambiar el valor de administrador',
                    'code' =>409,
                ],409);
            }
            $users->admin = $request->admin;
                
        }
        $users->save();
        return response()->json(['data'=>$users],200);
                
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::findOrFail($id);
        $users->delete();
        return response()->json(['data'=>$users],200);
    }
}
