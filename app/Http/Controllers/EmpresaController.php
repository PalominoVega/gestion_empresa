<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LandingEmpresaValidation;

use App\Model\User;
use App\Model\Empresa;
use Carbon\Carbon;
use Mail;


class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($request->all==true) {
            $empresa=Empresa::all();
        }else {
            $empresa=Empresa::paginate(5);
        }
        return response()->json($empresa);
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
    public function store(LandingEmpresaValidation $request)
    {
        DB::beginTransaction();
        try{
            if ($request->tipo=="demo") {
                $empresa = new Empresa();
                $empresa->nombre=$request->nombre_empresa;
                $empresa->telefono=$request->telefono;
                $empresa->email=$request->email;
                $empresa->ruc=$request->ruc;
                $empresa->codigo='VesPro';
                $empresa->save();
                
                $contrasenia=Carbon::now()->format('His').($request->nombre)[0];

                $usuario=new User();
                $usuario->nombre=mb_strtoupper($request->nombre);
                $usuario->email=$request->email;
                $usuario->numero=$request->numero;
                $usuario->password=bcrypt($contrasenia);
                $usuario->api_token=$empresa->id.'_'.Carbon::now()->format('YmdHisu');
                $usuario->estado='2';
                $usuario->rol='Administrador';
                $usuario->empresa_id=$empresa->id;
                $usuario->save();
                
                DB::commit();

                Mail::send('Mail.prueba', ["empresa"=>$empresa->nombre,"usuario"=>$usuario, "contrasenia"=>$contrasenia], function ($message) use ($usuario) {
                    $message->subject('Bienvenido a gestión de empresa');
                    $message->to($usuario->email);
                });

                return response()->json([
                    "status"    =>  "OK",
                    "data"      =>  ($request->data==true) ? $empresa : "Empresa registrado",
                ]);
            }
            $empresa = new Empresa();

            $nombre="logoFC.png";

            $empresa->nombre=$request->nombreempresa;
            $empresa->direccion=mb_strtoupper($request->direccionempresa);
            $empresa->telefono=$request->telefonoempresa;
            $empresa->email=$request->emailempresa;
            $empresa->ruc=$request->ruc;
        
            $id=str_random(3);
            if($request->file('file')!=""){
                $file = $request->file('file');
                $extension=$file->getClientOriginalExtension();
                $nombre='item'.$id.'.'.$extension;            
                Storage::disk('public/storage/logo/')->put($nombre,  \File::get($file));
                // public_path('/storage/producto/'.$fileName)
            }
            $empresa->logo=$nombre;
            $empresa->codSeguridad='VesPro';
            $empresa->save();
            $id_empresa=$empresa->id;
            
            /* registrar usuarios */
            $user=new User();
            $user->nombre=mb_strtoupper($request->nombre);
            $user->apellido=mb_strtoupper($request->apellido);
            $user->email=$request->email;
            $user->dni=$request->dni;
            $user->numero=$request->numero;
            $user->password=bcrypt($request->contrasenia);
            $user->api_token=$id.'_'.Carbon::now()->format('YmdHisu');
            $user->estado='2';
            $user->rol='Administrador';
            $user->empresa_id=$id_empresa;
            $user->save();
            
            DB::commit();
            Mail::send('Mail.prueba', ["empresa"=>$empresa->nombre,"usuario"=>$usuario, "contrasenia"=>$request->password], function ($message) use ($usuario) {
                $message->subject('Registro de Incidentes');
                $message->to($usuario->email);
            });

            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $empresa : "Empresa registrado",
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
        $empresa= Empresa::where('id',$id)->first();
        return response()->json($empresa);
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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $empresa= Empresa::where('id',$id)->first();
            $empresa->nombre=$request->nombreempresa;
            $empresa->direccion=mb_strtoupper($request->direccionempresa);
            $empresa->telefono=$request->telefonoempresa;
            $empresa->email=$request->emailempresa;
            $empresa->ruc=$request->ruc;
            $nombre=$empresa->logo;
            if($request->file('file')!=""){
                $file = $request->file('file');
                $extension=$file->getClientOriginalExtension();
                $nombre='item'.$empresa_id.'.'.$extension;            
                Storage::disk('public/storage/logo/')->put($nombre,  \File::get($file));
            }
            $empresa->logo=$nombre;
            $empresa->codSeguridad='VesPro';
            $empresa->save();
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $empresa : "Empresa  actualizada",
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

            $empresa= Empresa::where('id',$id)->first();
            
            $estado = ($empresa->estado=='Activo') ? 'Inactivo': 'Activo'; //saber el estado actual y cambiarlo
            
            $empresa->estado=$estado;
            $empresa->save();

            $estado = ($empresa->estado=='Activo') ? 'La cuenta de la empresa fue desactivado': 'La cuenta de la empresa fue activado'; //saber el estado cambiado para mostrar el mensaje

            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $empresa : $estado,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage(),
            ]);
        }
    }
}
