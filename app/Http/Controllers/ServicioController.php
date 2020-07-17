<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\funcionesGlobales;
use App\EmisionModel;
use App\DetalleServicioModel;
use App\ServicioModel;
use Log;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        //LISTAMOS LAS Parroquias REGISTRADAS EN LA BASE DE DATOS Y LA PASAMOS A LA VISTA
        $listaServicio=ServicioModel::where('estado','Activo')->get();
        return view('gestionServicios.registro')->with(['listaServicio'=>$listaServicio]);
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
                'detalle_servicio'=>$request->get('detalle')
               
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoServicio'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };


            if(is_null($request->get('detalle')))
            {
                    return back()->with(['mensajePInfoServicio'=>'Ingrese un servicio','estadoP'=>'danger']);

            }

            //GUARDAMOS Los ser5vicios EN LA BASE DE DATOS
            $servicio= new ServicioModel();
            $servicio->detalle_servicio=$request->get('detalle');
            $servicio->estado="Activo";

            $servicioexistente=ServicioModel::where('detalle_servicio',$servicio->detalle_servicio)
            ->where('estado','Activo')->first();
            if(!is_null($servicioexistente))
           {
            return back()->with(['mensajePInfoServicio'=>'Servicio no ingresado, el registro ya existe','estadoP'=>'danger']);
           }

           $servicioActualizar=ServicioModel::where('estado','Eliminado')->where('detalle_servicio',$servicio->detalle_servicio)->first();
           if(!is_null($servicioActualizar))
           {
            $servicioActualizar->estado="Activo";
            $servicioActualizar->save();
            return back()->with(['mensajePInfoServicio'=>'Registro exitoso','estadoP'=>'success']);
           }

            if($servicio->save()){
                return back()->with(['mensajePInfoServicio'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoServicio'=>'No se pudo realizar el registro','estadoP'=>'danger']);
        }
       } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoServicio'=>'No se pudo realizar el registro','estadoP'=>'danger']);
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
            $servicio=ServicioModel::find($id);
            return response()->json([
                'error'=>false,
                'resultado'=>$servicio
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
                'detalle_servicio'=>$request->get('detalle')
               
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoServicio'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };

            if(is_null($request->get('detalle')))
            {
                    return back()->with(['mensajePInfoServicio'=>'Ingrese un servicio','estadoP'=>'danger']);

            }

            //ACTUALIZAMOS LA Parroquia EN LA BASE DE DATOS
            $servicio= ServicioModel::find(decrypt($id));
            $servicio->detalle_servicio=$request->get('detalle');
           
            $idservicio= decrypt($id);
             $sieselmismoservicio=ServicioModel::where('detalle_servicio',$servicio->detalle_servicio)
             ->where('estado','Activo')->where('idservicio',$idservicio)->first();
            if(!is_null($sieselmismoservicio))
           {
            $servicio->save();
            return back()->with(['mensajePInfoServicio'=>'Actualización exitosa ','estadoP'=>'success']);
            //return back()->with(['mensajePInfoProvincia'=>'Provincia no actualizada, el registro ya existe','estadoP'=>'danger']);
           }

             $servicioexistente=ServicioModel::where('detalle_servicio',$servicio->detalle_servicio)->first();

            if(!is_null($servicioexistente))
           {
            return back()->with(['mensajePInfoServicio'=>'Servicio no actualizado, el registro ya existe','estadoP'=>'danger']);
           
           }

           

            if($servicio->save()){
                return back()->with(['mensajePInfoServicio'=>'Actualización exitosa','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoServicio'=>'No se pudo realizar la actualización','estadoP'=>'danger']);
            }

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoServicio'=>'No se pudo actualizar el registro','estadoP'=>'danger']);
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
            return back()->with(['mensajePInfoServicio'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        //BUSCAMOS EL REGISTRO
        $idservicioGet=decrypt($id);
        

        $emision=EmisionModel::where('estado','Activo')->where('idservicio',$idservicioGet)->first();
        if(!is_null($emision))
        {
            return back()->with(['mensajePInfoServicio'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }

        $detalleServicio=DetalleServicioModel::where('estado','Activo')->where('idservicio',$idservicioGet)->first();
        if(!is_null($detalleServicio))
        {
            return back()->with(['mensajePInfoServicio'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }

        $servicio= ServicioModel::find(decrypt($id));
        $servicio->estado="Eliminado";
        if($servicio->save())
        {
             return back()->with(['mensajePInfoServicio'=>'El registro fué eliminado','estadoP'=>'success']);
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
