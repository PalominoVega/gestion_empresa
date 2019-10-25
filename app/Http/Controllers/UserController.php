<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UsuarioValidation;
use App\Http\Requests\UsuarioUpdataValidation;
use App\Http\Requests\UsuarioPasswordUpdateValidation;
Use App\Mail\CredencialesUserMail;

use Peru\Jne\Dni;
use Peru\Http\ContextClient;

use App\Model\User;
use Carbon\Carbon;
use Mail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if ($request->all==true) {
            $usuarios=User::select('id','nombre','apellido','dni','email','numero','rol','estado')->where('empresa_id',session('empresa_id'))->get();
        }else {
            $usuarios=User::select('id','nombre','apellido','dni','email','numero','rol','estado')->where('empresa_id',session('empresa_id'))->paginate(5);
        }
        return response()->json($usuarios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioValidation $request)
    {
        DB::beginTransaction();
        

        try {
            $usuario= new User();
            $usuario->nombre=$request->nombre;
            $usuario->apellido=$request->apellido;
            $usuario->dni=$request->dni;
            $usuario->email=$request->email;
            $usuario->numero=$request->numero;
            $usuario->password=bcrypt($request->password);
            $usuario->api_token=session('empresa_id').'_'.Carbon::now()->format('YmdHisu');
            $usuario->estado='0';
            $usuario->rol=$request->rol;
            $usuario->empresa_id=session('empresa_id');
            $usuario->save();

            Mail::send('Mail.prueba', ["empresa"=>$usuario->empresa->nombre,"usuario"=>$usuario, "contrasenia"=>$request->password], function ($message) use ($usuario) {
                $message->subject('Credenciales del usuario');
                $message->from('alertas@corporacionvespro.com','Gestión de empresa');
                $message->to($usuario->email);
            });

            $contrasenia=$request->password;

            // Mail::to($usuario->email)->send(new CredencialesUserMail($usuario, $contrasenia));

            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $usuario : "usuario Guardado",
                // "data"=>['mensaje'=>"Usuario Registrado", "rol"=>$usuario->rol, "id_usuario"=>$usuario->id, "nombre"=>$usuario->nombre.' '.$usuario->apellido,"id_empresa"=>$usuario->empresa_id,"token"=>$usuario->api_token],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario= User::select('id','nombre','apellido','dni','email','numero','rol')->where('id',$id)->first();
        return response()->json($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsuarioUpdataValidation $request, $id)
    {
        DB::beginTransaction();

        try {
            $usuario= User::where('id',$id)->first();
            $usuario->nombre=$request->nombre;
            $usuario->apellido=$request->apellido;
            $usuario->dni=$request->dni;
            $usuario->email=$request->email;
            $usuario->numero=$request->numero;
            $usuario->rol=$request->rol;
            $usuario->save();

            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $usuario : "Usuario Actualizado",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cambiar_Estado($id)
    {
        DB::beginTransaction();

        try {

            $usuario= User::where('id',$id)->first();
            if($usuario->estado=='2'){
                return response()->json([
                    "status"    =>  "WARNING",
                    "data"      =>  "Usuario no puede desactivarse",
                ]);
            }
            
            $estado = ($usuario->estado=='0') ? '1': '0'; //saber el estado actual y cambiarlo
            
            $usuario->estado=$estado;
            $usuario->save();

            $estado = ($usuario->estado=='0') ? 'Usuario activado ': 'Usuario desactivado'; //saber el estado cambiado para mostrar el mensaje

            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  $estado,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
    }

    public function cambiar_contrasenia(UsuarioPasswordUpdateValidation $request)
    {
        DB::beginTransaction();
        $usuario=User::where('id',session('user_id'))->first();

        if(Hash::check($request->passwordactual, $usuario->password)){
            try{
                // $usuario->password = Hash::make($request->password);
                $usuario->password = bcrypt($request->passwordnuevo);
                $usuario->save();
                DB::commit();
                return response()->json([
                    "status"    =>  "OK",
                    "data"      =>  "Nueva contraseña guardada correctamente",
                ]);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json([
                    "status"    =>  "DANGER",
                    "data"      =>  $e->getMessage()
                ]);
            };
        }else{
            return response()->json([
                "status"    =>  "WARNING",
                "data"      =>  "La contraseña actual no es correcta",
            ]);
        }
    }

    public function email(Request $request){
        
        DB::beginTransaction();

        try{
            $usuario=User::where('email',$request->email)->first();
            
            if(is_null($usuario)){
                return response()->json([
                    "status"    =>  "WARNING",
                    "data"      =>  "Correo no ha sido encontrado",
                ]);
            }
            $usuario->api_token=$usuario->empresa_id.'_'.Carbon::now()->format('YmdHisu');
            $usuario->save();

            Mail::send('Mail.passwordEmail', ["empresa"=>$usuario->empresa->nombre,"usuario"=>$usuario], function ($message) use ($usuario) {
                $message->subject('Recuperación de Contraseña');
                $message->to($usuario->email);
            });

            // Mail::to($usuario->email)->send(new CredencialesUserMail($usuario));

            DB::commit();

            return response()->json([
                "status"    =>  "OK",
                "data"      =>  "Verifique su correo",
            ]);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        };
    }

    public function new_contrasenia(Request $request, $token)
    {
        DB::beginTransaction();
        
        $usuario=User::where('api_token',$token)->first();

        if(is_null($usuario)){
            return response()->json([
                "status"    =>  "WARNING",
                "data"      =>  "Token invalido",
            ]);
        }

        try{
            // $usuario->password = Hash::make($request->password);
            $usuario->password = bcrypt($request->password);
            $usuario->api_token=session('empresa_id').'_'.Carbon::now()->format('YmdHisu');
            $usuario->save();

            Mail::send('Mail.prueba', ["empresa"=>$usuario->empresa->nombre,"usuario"=>$usuario, "contrasenia"=>$request->password], function ($message) use ($usuario) {
                $message->subject('Credenciales del usuario');
                $message->to($usuario->email);
            });

            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"=>['mensaje'=>"Ingreso", "rol"=>$usuario->rol, "id_usuario"=>$usuario->id, "nombre"=>$usuario->nombre.' '.$usuario->apellido,"id_empresa"=>$usuario->empresa_id,"token"=>$usuario->api_token],
            ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        };
        
    }

    public function consulta(Request $request){
        if ($request->dni!=null) {
            $dni = $request->dni;
    
            $cs = new Dni();
            $cs->setClient(new ContextClient());
    
            $person = $cs->get($dni);
            if ($person === false) {
                echo $cs->getError();
                exit();
            }
        
            echo json_encode($person);
        }
    }
    
}
