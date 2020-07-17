<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CantonModel;
use App\EmisionModel;
use App\PersonaModel;
use Log;
use Illuminate\Support\Facades\Validator;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {        
            $listaPersona = PersonaModel::with('canton')->orderBy('idcanton')->where('estado','Activo')->get(); 
            $listaCantones = CantonModel::where('estado','Activo')->get();
            return view('gestionPersona.registro',[
                'listaPersona'=> $listaPersona,
                'listaCantones'=>$listaCantones
          ]);
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back();
        }

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
    public function store(Request $request)
    {
        try {

            // CREAMOS ARREGLO PARA ENVIAR A LA FUNCION A VALIDAR CARACTERES ESPECIALES
            $validaCE=array(
                
                'nombres'=>$request->get('nombres'),
                'apellidos'=>$request->get('apellidos'),
                'fecha_nacimiento'=>$request->get('fecha_nacimiento'),
                'sexo'=>$request->get('sexo'),
                'direccion'=>$request->get('direccion'),
               
                'idcanton'=>$request->get('cmb_canton'),
                'factor'=>$request->get('factor'),
                'grupo'=>$request->get('grupo'),
                'factor_du'=>$request->get('factor_du')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoPersona'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };

             if(is_null($request->get('nombres'))) 
               {
                return back()->with(['mensajePInfoPersona'=>'Ingrese el nombre','estadoP'=>'danger']);

             }

             if(is_null($request->get('apellidos'))) 
               {
                return back()->with(['mensajePInfoPersona'=>'Ingrese el apellido','estadoP'=>'danger']);

             }


            if(is_null($request->get('fecha_nacimiento')))
               {
                return back()->with(['mensajePInfoPersona'=>'Ingrese una fecha de fecha_nacimiento','estadoP'=>'danger']);

              }

            if(is_null($request->get('cmb_canton')))
               {
                return back()->with(['mensajePInfoPersona'=>'Seleccione un cantón','estadoP'=>'danger']);

              }


            
            $correo=$request->get('correo');
            if(!is_null($correo)){

              $dataCorreo=[
                    'email' => $request->correo
                   
                ];
            
              $reglacorreo = 'string|email|max:255';

                $validator = Validator::make($dataCorreo, [
                    'email' => $reglacorreo
                  
                ]);
            
                if($validator->fails()) {    
                  return back()->with(['mensajePInfoPersona'=>'Formato de correo incorrecto','estadoP'=>'danger']);
                }
             }
             else
             {
                $correo="";
             }
            
            $sexo="";
            $masculino=$request->get('check_masculino');
            if(!is_null($masculino))
            {
                $sexo=$masculino;
            }
            $femenino=$request->get('check_femenino');
            if(!is_null($femenino))
            {
                $sexo=$femenino;
            }

            if(is_null($masculino) && is_null($femenino))
               {
                return back()->with(['mensajePInfoPersona'=>'Seleccione un sexo','estadoP'=>'danger']);

              }
           

            //GUARDAMOS LA PERSNONA EN LA BASE DE DATOS
            $persona= new PersonaModel();
            $persona->nombres=$request->get('nombres');
            $persona->apellidos=$request->get('apellidos');
            $persona->fecha_nacimiento=$request->get('fecha_nacimiento');
            $persona->sexo=$sexo;
            $persona->direccion=$request->get('direccion');
            $persona->email=$request->get('correo');
            $persona->idcanton=$request->get('cmb_canton');
            $persona->factor=$request->get('factor');
            $persona->grupo=$request->get('grupo');
            $persona->factor_du=$request->get('factor_du');
            $persona->estado="Activo";
            

            
           $personaexistente=PersonaModel::where('nombres',$persona->nombres)
            ->where('apellidos',$persona->apellidos)
            ->where('fecha_nacimiento',$persona->fecha_nacimiento)
            ->where('sexo',$persona->sexo)
            ->where('direccion',$persona->direccion)
            ->where('email',$persona->correo)
            ->where('idcanton',$persona->idcanton)
            ->where('factor',$persona->factor)
            ->where('grupo',$persona->grupo)
             ->where('factor_du',$persona->factor_du)
            ->where('estado','Activo')->first();
            if(!is_null($personaexistente))
             {
              return back()->with(['mensajePInfoPersona'=>'Persona no ingresado, el registro ya existe','estadoP'=>'danger']);
             }

           $personaActualizar=PersonaModel::where('nombres',$persona->nombres)
            ->where('apellidos',$persona->apellidos)
            ->where('fecha_nacimiento',$persona->fecha_nacimiento)
            ->where('sexo',$persona->sexo)
            ->where('direccion',$persona->direccion)
            ->where('email',$persona->correo)
            ->where('idcanton',$persona->idcanton)
            ->where('factor',$persona->factor)
            ->where('grupo',$persona->grupo)
             ->where('factor_du',$persona->factor_du)
            ->where('estado','Eliminado')->first();

            if(!is_null($personaActualizar))
             {
              $personaActualizar->estado="Activo";
              $personaActualizar->save();
              return back()->with(['mensajePInfoPersona'=>'Registro exitoso','estadoP'=>'success']);
             }

            if($persona->save())
            {
                return back()->with(['mensajePInfoPersona'=>'Registro exitoso','estadoP'=>'success']);
            }
            else
            {
                return back()->with(['mensajePInfoPersona'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
      } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoPersona'=>'Error el realizar el registro','estadoP'=>'danger']);
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
        try {
            $id=decrypt($id);
             $canton=CantonModel::where('idprovincia',$id)->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$canton
            ], 200);
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return response()->json([
                'error'=>true,
                'message'=>$th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $idpersona = decrypt($id);
            //dd($idpersona);
            $persona = PersonaModel::with('canton')
                ->where('idpersona',$idpersona)
                ->first();

            return response()->json([
                'error'=>false,
                'resultado'=>$persona
            ], 200);

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return response()->json([
                'error'=>true,
                'message'=>$th->getMessage()
            ], 500);
        }
    
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
        try {
            $idpersona = decrypt($id);
            // CREAMOS ARREGLO PARA ENVIAR A LA FUNCION A VALIDAR CARACTERES ESPECIALES
            $validaCE=array(
                'nombres'=>$request->get('nombres'),
                'apellidos'=>$request->get('apellidos'),
                'fecha_nacimiento'=>$request->get('fecha_nacimiento'),
                
                'direccion'=>$request->get('direccion'),
                
                'idcanton'=>$request->get('cmb_canton'),
                'factor'=>$request->get('factor'),
                'grupo'=>$request->get('grupo'),
                'factor_du'=>$request->get('factor_du')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoPersona'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };


           
            if(is_null($request->get('nombres'))) 
               {
                return back()->with(['mensajePInfoPersona'=>'Ingrese el nombre','estadoP'=>'danger']);

             }

             if(is_null($request->get('apellidos'))) 
               {
                return back()->with(['mensajePInfoPersona'=>'Ingrese el apellido','estadoP'=>'danger']);

             }


            if(is_null($request->get('fecha_nacimiento')))
               {
                return back()->with(['mensajePInfoPersona'=>'Ingrese una fecha de fecha_nacimiento','estadoP'=>'danger']);

              }


            if(is_null($request->get('cmb_canton')))
               {
                return back()->with(['mensajePInfoPersona'=>'Seleccione un cantón','estadoP'=>'danger']);

              }

             $correo=$request->get('correo');
            if(!is_null($correo)){

              $dataCorreo=[
                    'email' => $request->correo
                   
                ];
            
              $reglacorreo = 'string|email|max:255';

                $validator = Validator::make($dataCorreo, [
                    'email' => $reglacorreo
                  
                ]);
            
                if($validator->fails()) {    
                  return back()->with(['mensajePInfoPersona'=>'Formato de correo incorrecto','estadoP'=>'danger']);
                }
             }
             else
             {
                $correo="";
             }
            
            $sexo="";
            $masculino=$request->get('check_masculino');
            if(!is_null($masculino))
            {
                $sexo=$masculino;
            }
            $femenino=$request->get('check_femenino');
            if(!is_null($femenino))
            {
                $sexo=$femenino;
            }

             if(is_null($masculino) && is_null($femenino))
               {
                return back()->with(['mensajePInfoPersona'=>'Seleccione un sexo','estadoP'=>'danger']);

              }

            //GUARDAMOS LA PERSONA EN LA BASE DE DATOS
            $persona= PersonaModel::find($idpersona);
            $persona->nombres=$request->get('nombres');
            $persona->apellidos=$request->get('apellidos');
            $persona->fecha_nacimiento=$request->get('fecha_nacimiento');
            $persona->sexo=$sexo;
            $persona->direccion=$request->get('direccion');
            $persona->email=$correo;
            $persona->idcanton=$request->get('cmb_canton');
            $persona->factor=$request->get('factor');
            $persona->grupo=$request->get('grupo');
            $persona->factor_du=$request->get('factor_du');
            $persona->estado="Activo";

            $idpersona= decrypt($id);
            $sieslamismapersona=PersonaModel::where('nombres',$persona->nombres)
            ->where('apellidos',$persona->apellidos)
            ->where('fecha_nacimiento',$persona->fecha_nacimiento)
            ->where('sexo',$persona->sexo)
            ->where('direccion',$persona->direccion)
            ->where('email',$persona->correo)
            ->where('idcanton',$persona->idcanton)
            ->where('factor',$persona->factor)
            ->where('grupo',$persona->grupo)
             ->where('factor_du',$persona->factor_du)
            ->where('estado','Activo')
            ->where('idpersona',$idpersona)->first();
            if(!is_null($sieslamismapersona))
           {
            $canton->save();
            return back()->with(['mensajePInfoPersona'=>'Actualización exitosa ','estadoP'=>'success']);
            
           }


            $personaexistente=PersonaModel::where('nombres',$persona->nombres)
            ->where('apellidos',$persona->apellidos)
            ->where('fecha_nacimiento',$persona->fecha_nacimiento)
            ->where('sexo',$persona->sexo)
            ->where('direccion',$persona->direccion)
            ->where('email',$persona->correo)
            // ->where('idcanton',$persona->idcanton)
            ->where('factor',$persona->factor)
            ->where('grupo',$persona->grupo)
             ->where('factor_du',$persona->factor_du)->first();
            if(!is_null($personaexistente))
           {
            return back()->with(['mensajePInfoPersona'=>'Persona no actualizada, el registro ya existe','estadoP'=>'danger']);
           }

          

            if($persona->save()){
                return back()->with(['mensajePInfoPersona'=>'Actualización exitosa','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoPersona'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoPersona'=>'Error el realizar el registro','estadoP'=>'danger']);
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
        $DesencriptarID=array(
            'id'=>decrypt($id)
        );
        //Validar si tiene caracteres especiales
        if(tieneCaracterEspecialRequest($DesencriptarID)){
            return back()->with(['mensajePInfoPersona'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        //BUSCAMOS EL REGISTRO
        $idpersonaGet=decrypt($id);
        

        $emision=EmisionModel::where('estado','Activo')->where('idpersona',$idpersonaGet)->first();
       // dd($canton);
        if(!is_null($emision))
        {
            return back()->with(['mensajePInfoPersona'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }
        $persona= PersonaModel::find(decrypt($id));
        $persona->estado="Eliminado";
        if($persona->save())
        {
             return back()->with(['mensajePInfoPersona'=>'El registro fué eliminado','estadoP'=>'success']);
        }
    }
}
