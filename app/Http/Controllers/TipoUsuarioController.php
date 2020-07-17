<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\funcionesGlobales;
use App\User;
use App\TipoUsuarioModel;
use App\OpcionesModel;
use Log;

class TipoUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        //LISTAMOS LOS TIPO USUARIOS REGISTRADAS EN LA BASE DE DATOS Y LA PASAMOS A LA VISTA
        $listaTipoUsuarios=TipoUsuarioModel::where('estado','Activo')->get();
        return view('gestionUsuario.tipo')->with(['listaTipoUsuarios'=>$listaTipoUsuarios]);
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
                'detalle'=>$request->get('detalle')
               
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoTipoUsuario'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
             if(is_null($request->get('detalle')))
             {
                    return back()->with(['mensajePInfoTipoUsuario'=>'Ingrese una descripción','estadoP'=>'danger']);

             }
            //GUARDAMOS LA Parroquia EN LA BASE DE DATOS
            $tipoUsuario= new TipoUsuarioModel();
            $tipoUsuario->detalle=$request->get('detalle');
            $tipoUsuario->estado="Activo";

            $tipoUsuarioexistente=TipoUsuarioModel::where('detalle',$tipoUsuario->detalle)
            ->where('estado','Activo')->first();
            if(!is_null($tipoUsuarioexistente))
           {
            return back()->with(['mensajePInfoTipoUsuario'=>'Tipo Usuario no ingresado, el registro ya existe','estadoP'=>'danger']);
           }

           $TipoUsuarioActualizar=TipoUsuarioModel::where('estado','Eliminado')->where('detalle',$tipoUsuario->detalle)->first();
           if(!is_null($TipoUsuarioActualizar))
           {
            $TipoUsuarioActualizar->estado="Activo";
            $TipoUsuarioActualizar->save();
            return back()->with(['mensajePInfoTipoUsuario'=>'Registro exitoso','estadoP'=>'success']);
           }

            if($tipoUsuario->save()){
                return back()->with(['mensajePInfoTipoUsuario'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoTipoUsuario'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoTipoUsuario'=>'No se pudo realizar el registro','estadoP'=>'danger']);
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
        //
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
            $id=decrypt($id);
            $tipoUsuario=TipoUsuarioModel::find($id);
            return response()->json([
                'error'=>false,
                'resultado'=>$tipoUsuario
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
        // CREAMOS ARREGLO PARA ENVIAR A LA FUNCION A VALIDAR CARACTERES ESPECIALES
            $validaCE=array(
                'detalle'=>$request->get('detalle')
               
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoTipoUsuario'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };

             if(is_null($request->get('detalle')))
             {
                    return back()->with(['mensajePInfoTipoUsuario'=>'Ingrese una descripción','estadoP'=>'danger']);

             }
             
            //ACTUALIZAMOS TIPOUSUARIO EN LA BASE DE DATOS
            
            $tipoUsuario= TipoUsuarioModel::find(decrypt($id));
            $tipoUsuario->detalle=$request->get('detalle');
           
            $id= decrypt($id);
             $sieselmismo=TipoUsuarioModel::where('detalle',$tipoUsuario->detalle)
             ->where('estado','Activo')->where('idtipo_usuario',$id)->first();
            if(!is_null($sieselmismo))
           {
            $tipoUsuario->save();
            return back()->with(['mensajePInfoTipoUsuario'=>'Actualización exitosa ','estadoP'=>'success']);
            //return back()->with(['mensajePInfoProvincia'=>'Provincia no actualizada, el registro ya existe','estadoP'=>'danger']);
           }

             $tipoUsuarioexistente=TipoUsuarioModel::where('detalle',$tipoUsuario->detalle)->first();

            if(!is_null($tipoUsuarioexistente))
           {
            return back()->with(['mensajePInfoTipoUsuario'=>'Tipo Usuario no actualizado, el registro ya existe','estadoP'=>'danger']);
           
           }

          

            if($tipoUsuario->save()){
                return back()->with(['mensajePInfoTipoUsuario'=>'Actualización exitosa','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoTipoUsuario'=>'No se pudo realizar la actualización','estadoP'=>'danger']);
            }

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoTipoUsuario'=>'No se pudo actualizar el registro','estadoP'=>'danger']);
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
        //CREAR ARREGLO PARA MANDAR A VALIDAR SI TIENE CARACTERESE ESPECIALES
        $DesencriptarID=array(
            'id'=>decrypt($id)
        );
        //Validar si tiene caracteres especiales
        if(tieneCaracterEspecialRequest($DesencriptarID)){
            return back()->with(['mensajePInfoTipoUsuario'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        //BUSCAMOS EL REGISTRO
        $idtipoUsuarioGet=decrypt($id);
        

        $usuario=User::where('idtipo_usuario',$idtipoUsuarioGet)->first();
        if(!is_null($usuario))
        {
            return back()->with(['mensajePInfoTipoUsuario'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }
        
        $opciones=OpcionesModel::where('idtipo_usuario',$idtipoUsuarioGet)->first();
        if(!is_null($opciones))
        {
            return back()->with(['mensajePInfoTipoUsuario'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }

        $tipoUsuario= TipoUsuarioModel::find(decrypt($id));
        $tipoUsuario->estado="Eliminado";
        if($tipoUsuario->save())
        {
             return back()->with(['mensajePInfoTipoUsuario'=>'El registro fué eliminado','estadoP'=>'success']);
        }

        // //ELIMINAMOS EL REGISTRO
        // try {
        //     $provincia->delete();
        //     return back()->with(['mensajePInfoProvincia'=>'El registro fué eliminado','estadoP'=>'success']);
        // } catch (\Throwable $th) {
        //     return back()->with(['mensajePInfoProvincia'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        // }
    }
}
