<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TipoUsuarioModel;
use App\RutaModel;
use App\OpcionesModel;
use App\MenuModel;
use App\ProvinciaModel;
use Log;

class OpcionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //try {        
        
            $opciones=OpcionesModel::pluck('idopciones');
                   
            $listaOpciones=TipoUsuarioModel::with(['tipoUsuarioGestion'=>function($query_gestion){
                    $query_gestion->orderBy('idopciones','ASC');    
                }])
            ->whereHas('tipoUsuarioGestion', function($query)use($opciones){
                        $query ->whereIn('idopciones',$opciones);
                    })->get();
            

            $listaUsuario = TipoUsuarioModel::where('estado','Activo')->get();// obtenemos todas los usuarios para cargar en el combo
            $listaRuta2 = RutaModel::all();// obtenemos todas los usuarios para cargar en el combo
            
            $listaRuta=MenuModel::with(['ruta'=>function($query_gestion){
                $query_gestion->orderBy('descripcion','ASC');    
            }])->get(); 

            

            return view('gestionAccesos.opciones',[
                'listaOpciones'=> $listaOpciones,
                'listaUsuario'=>$listaUsuario,
                'listaRuta'=>$listaRuta
               
          ]);
        // } catch (\Throwable $th) {
        //     Log::error("Error get Request Id ".$th->getMessage());
        //     return back();
        // }

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
                
                'idtipo_usuario'=>$request->get('cmb_tipousuario'),
                //'idruta'=>$request->get('cmb_ruta')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoOpciones'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };


            if(is_null($request->get('cmb_tipousuario')))
            {
                return back()->with(['mensajePInfoOpciones'=>'Ingrese un tipo de Usuario,','estadoP'=>'danger']);

            }
             if(is_null($request->get('cmb_ruta')))
            {
                return back()->with(['mensajePInfoOpciones'=>'Ingrese una o más gestiónes','estadoP'=>'danger']);

            }
             //dd($request->get('clave'));

            //GUARDAMOS LA ZONA EN LA BASE DE DATOS
            foreach ($request->get('cmb_ruta') as $key => $ruta) {
            $opciones= new OpcionesModel();
            $opciones->idtipo_usuario=$request->get('cmb_tipousuario');
            $opciones->idruta = $ruta;

            
           $opcionexistente=OpcionesModel::where('idtipo_usuario',$opciones->idtipo_usuario)
            ->where('idruta',$opciones->idruta)->first();

             if(is_null($opcionexistente))
               {
                $opciones->save();
               }
               else{
                 return back()->with(['mensajePInfoOpciones'=>'No se pudo realizar el registro, la información ya existe','estadoP'=>'danger']);
               }

            }
             return back()->with(['mensajePInfoOpciones'=>'Registro exitoso','estadoP'=>'success']);
          
          

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoOpciones'=>'Error el realizar el registro','estadoP'=>'danger']);
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
            $idtipo_usuario = decrypt($id);
            $opciones = OpcionesModel::where('idtipo_usuario',$idtipo_usuario)
                ->get();
              //  dd($opciones);

            return response()->json([
                'error'=>false,
                'resultado'=>$opciones,
                'idtipo_usuario'=>$idtipo_usuario
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
            $idopciones = decrypt($id);
            
            // CREAMOS ARREGLO PARA ENVIAR A LA FUNCION A VALIDAR CARACTERES ESPECIALES
             $validaCE=array(
                
                'idtipo_usuario'=>$request->get('cmb_tipousuario'),
               // 'idruta'=>$request->get('cmb_ruta')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoOpciones'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
            
            if(is_null($request->get('cmb_tipousuario')))
            {
                return back()->with(['mensajePInfoOpciones'=>'Ingrese un tipo de Usuario,','estadoP'=>'danger']);

            }
             if(is_null($request->get('cmb_ruta')))
            {
                return back()->with(['mensajePInfoOpciones'=>'Ingrese una o más gestiónes','estadoP'=>'danger']);

            }


            $borrar=OpcionesModel::where('idtipo_usuario',$idopciones);
            //dd($borrar);
            $borrar->delete();

            foreach ($request->get('cmb_ruta') as $key => $ruta) {
                   $opciones= new OpcionesModel;
                   $opciones->idtipo_usuario=$request->get('cmb_tipousuario');
                   $opciones->idruta = $ruta;

            
           $opcionexistente=OpcionesModel::where('idtipo_usuario',$opciones->idtipo_usuario)
            ->where('idruta',$opciones->idruta)->first();

             if(is_null($opcionexistente))
               {
                $opciones->save();
               }
               else{
                 return back()->with(['mensajePInfoOpciones'=>'No se pudo realizar el registro, la información ya existe','estadoP'=>'danger']);
               }

            }
             return back()->with(['mensajePInfoOpciones'=>'Registro exitoso','estadoP'=>'success']);


           
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoOpciones'=>'Error el realizar el registro','estadoP'=>'danger']);
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
            return back()->with(['mensajePInfoOpciones'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        //BUSCAMOS EL REGISTRO
        $idtipo_usuario=decrypt($id);

         $opciones= OpcionesModel::where('idtipo_usuario',$idtipo_usuario);
        if($opciones->delete())
        {
             return back()->with(['mensajePInfoOpciones'=>'El registro fué eliminado','estadoP'=>'success']);
        }
        else{
                return back()->with(['mensajePInfoOpciones'=>'No se pudo eliminar el registro','estadoP'=>'danger']);
            }
    }
}
