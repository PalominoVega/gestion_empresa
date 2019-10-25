<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Model\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function postLogin(Request $request)
    {
        // $this->validate($request, [
        //     'email' => 'required', 
        //     'password' => 'required',
        // ]);
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()
                ->json([
                    "status"=>'VALIDATION',
                    "data"=>$validator->messages()
                ]);
        }
        
        $usuario=User::where('email',$request->email)->first();
        if(is_null($usuario))
            return response()->json([
                "status"    =>  "WARNING",
                "data"=>"Email no encontrado",
            ]);
        
        if(Hash::check($request->password, $usuario->password)){

            if (($usuario->estado=='0' || $usuario->estado=='2') && $usuario->empresa->tipo=='Activo'){
                return response()->json([
                    "status"    =>  "OK",
                    "data"=>['mensaje'=>"Ingreso", "rol"=>$usuario->rol, "id_usuario"=>$usuario->id, "nombre"=>$usuario->nombre.' '.$usuario->apellido,"id_empresa"=>$usuario->empresa_id,"token"=>$usuario->api_token],
                ]);
                // return redirect()->route('home');
            }elseif ($usuario->empresa->tipo=='Inactivo') {
                return response()->json([
                    "status"    =>  "WARNING",
                    "data"=>"Removar contrato",
                ]);
            }
            return response()->json([
                "status"    =>  "WARNING",
                "data"=>"Su cuenta está deshabilitada",
            ]);
        };

        return response()->json([
            "status"    =>  "WARNING",
            "data"=>"Contraseña Incorrecta",
        ]);
    }
}
