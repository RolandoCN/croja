<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\funcionesGlobales;
use App\ProvinciaModel;
use App\CantonModel;
use Log;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        //LISTAMOS LAS PROVINCIAS REGISTRADAS EN LA BASE DE DATOS Y LA PASAMOS A LA VISTA
        $listaProvincia=ProvinciaModel::where('estado','Activo')->get();
        return view('gestionResidencia.provincia')->with(['listaProvincia'=>$listaProvincia]);
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
                'detalle'=>$request->get('provincia')
               
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoProvincia'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
            if(is_null($request->get('provincia')))
            {
                    return back()->with(['mensajePInfoProvincia'=>'Ingrese un nombre de provincia','estadoP'=>'danger']);

            }
            //GUARDAMOS LA PROVINICIA EN LA BASE DE DATOS
            $provincia= new ProvinciaModel();
            $provincia->detalle=$request->get('provincia');
            $provincia->estado="Activo";

            $provinciaexistente=ProvinciaModel::where('detalle',$provincia->detalle)
            ->where('estado','Activo')->first();
            if(!is_null($provinciaexistente))
           {
            return back()->with(['mensajePInfoProvincia'=>'Provincia no ingresada, el registro ya existe','estadoP'=>'danger']);
           }

           $provinciaActualizar=ProvinciaModel::where('estado','Eliminado')->where('detalle',$provincia->detalle)->first();
           if(!is_null($provinciaActualizar))
           {
            $provinciaActualizar->estado="Activo";
            $provinciaActualizar->save();
            return back()->with(['mensajePInfoProvincia'=>'Registro exitoso','estadoP'=>'success']);
           }

            if($provincia->save()){
                return back()->with(['mensajePInfoProvincia'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoProvincia'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoProvincia'=>'No se pudo realizar el registro','estadoP'=>'danger']);
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
            $provincia=ProvinciaModel::find($id);
            return response()->json([
                'error'=>false,
                'resultado'=>$provincia
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
                'detalle'=>$request->get('provincia')
               
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoProvincia'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };

             if(is_null($request->get('provincia')))
            {
                    return back()->with(['mensajePInfoProvincia'=>'Ingrese un nombre de provincia','estadoP'=>'danger']);

            }
            //ACTUALIZAMOS LA PROVINCIA EN LA BASE DE DATOS
            $provincia= ProvinciaModel::find(decrypt($id));
            $provincia->detalle=$request->get('provincia');
           
            $idprovincia= decrypt($id);
             $sieslamismaprovincia=ProvinciaModel::where('detalle',$provincia->detalle)
             ->where('estado','Activo')->where('idprovincia',$idprovincia)->first();
            if(!is_null($sieslamismaprovincia))
           {
            $provincia->save();
            return back()->with(['mensajePInfoProvincia'=>'Actualización exitosa ','estadoP'=>'success']);
            //return back()->with(['mensajePInfoProvincia'=>'Provincia no actualizada, el registro ya existe','estadoP'=>'danger']);
           }

             $provinciaexistente=ProvinciaModel::where('detalle',$provincia->detalle)
             ->where('estado','Activo')->first();

            if(!is_null($provinciaexistente))
           {
            return back()->with(['mensajePInfoProvincia'=>'Provincia no actualizada, el registro ya existe','estadoP'=>'danger']);
           
           }

            $provinciaActualizar=ProvinciaModel::where('estado','Eliminado')->where('detalle',$provincia->detalle)->first();
           if(!is_null($provinciaActualizar))
           {
            // $provinciaActualizar->estado="Activo";
            // $provinciaActualizar->save();
            return back()->with(['mensajePInfoProvincia'=>'Provincia no actualizada, el dato ya se encuentra registrado','estadoP'=>'danger']);
           }

            if($provincia->save()){
                return back()->with(['mensajePInfoProvincia'=>'Actualización exitosa','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoProvincia'=>'No se pudo realizar la actualización','estadoP'=>'danger']);
            }

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoProvincia'=>'No se pudo actualizar el registro','estadoP'=>'danger']);
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
            return back()->with(['mensajePInfoProvincia'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        //BUSCAMOS EL REGISTRO
        $idprovinciaGet=decrypt($id);
        

        $canton=CantonModel::where('estado','Activo')->where('idprovincia',$idprovinciaGet)->first();
       // dd($canton);
        if(!is_null($canton))
        {
            return back()->with(['mensajePInfoProvincia'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }
        $provincia= ProvinciaModel::find(decrypt($id));
        $provincia->estado="Eliminado";
        if($provincia->save())
        {
             return back()->with(['mensajePInfoProvincia'=>'El registro fué eliminado','estadoP'=>'success']);
        }

       
    }
}
