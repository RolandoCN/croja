<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\funcionesGlobales;
use App\MenuModel;
use App\RutaModel;
use Log;

class RutaController extends Controller
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
        $listaRuta=RutaModel::all();
        //dd($listaRuta);
        $listaMenu=MenuModel::all();
        return view('gestionAccesos.ruta')
                    ->with(['listaRuta'=>$listaRuta,
                            'listaMenu'=>$listaMenu
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
                'descripcion'=>$request->get('descripcion')

               
            );

            if(is_null($request->get('cmb_menu')))
                {
                    return back()->with(['mensajePInfoRuta'=>'Ingrese una menú','estadoP'=>'danger']);

                }

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoRuta'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
            //GUARDAMOS LA RUTA EN LA BASE DE DATOS
            $ruta= new RutaModel();
            $ruta->descripcion=$request->get('descripcion');
            $ruta->ruta=$request->get('ruta');
            $ruta->icono=$request->get('icon_ruta');
            $ruta->idmenu=$request->get('cmb_menu');
           
            $rutaxistente=RutaModel::where('descripcion',$ruta->descripcion)
            ->where('ruta',$ruta->ruta)
            ->where('idmenu',$ruta->idmenu)
            ->where('icono',$ruta->icono)->first();
           if(!is_null($rutaxistente))
           {
            return back()->with(['mensajePInfoRuta'=>'Ruta no ingresada, el registro ya existe','estadoP'=>'danger']);
           }

           if($ruta->save()){
                return back()->with(['mensajePInfoRuta'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoRuta'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
                return back()->with(['mensajePInfoRuta'=>'No se pudo realizar el registro','estadoP'=>'danger']);
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
            $ruta=RutaModel::find($id);
            return response()->json([
                'error'=>false,
                'resultado'=>$ruta
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
                'descripcion'=>$request->get('descripcion')
               
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoRuta'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
             if(is_null($request->get('cmb_menu')))
                {
                    return back()->with(['mensajePInfoRuta'=>'Ingrese una menú','estadoP'=>'danger']);

                }
                
            //ACTUALIZAMOS LA Parroquia EN LA BASE DE DATOS
            $ruta= RutaModel::find(decrypt($id));
            $ruta->descripcion=$request->get('descripcion');
            $ruta->ruta=$request->get('ruta');
            $ruta->icono=$request->get('icon_ruta');
            $ruta->idmenu=$request->get('cmb_menu');

            $idruta= decrypt($id);
             $sieslamismaruta=RutaModel::where('descripcion',$ruta->descripcion)
             ->where('ruta',$ruta->ruta)
             ->where('icono',$ruta->icono)
             ->where('idmenu',$ruta->idmenu)
            ->where('idruta',$idruta)->first();
            if(!is_null($sieslamismaruta))
           {
            $ruta->save();
            return back()->with(['mensajePInfoRuta'=>'Actualización exitosa ','estadoP'=>'success']);
            //return back()->with(['mensajePInfoProvincia'=>'Provincia no actualizada, el registro ya existe','estadoP'=>'danger']);
           }

            $rutaexistente=RutaModel::where('descripcion',$ruta->descripcion)
            ->where('ruta',$ruta->ruta)->where('icono',$ruta->icono)
            ->where('idmenu',$ruta->idmenu)->first();

            if(!is_null($rutaexistente))
           {
            return back()->with(['mensajePInfoRuta'=>'Ruta no actualizada, el registro ya existe','estadoP'=>'danger']);
           
           }

            if($ruta->save()){
                return back()->with(['mensajePInfoRuta'=>'Actualización exitosa','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoRuta'=>'No se pudo realizar la actualización','estadoP'=>'danger']);
            }

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoRuta'=>'No se pudo actualizar el registro','estadoP'=>'danger']);
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
       try{
        $DesencriptarID=array(
            'id'=>decrypt($id)
        );
        //Validar si tiene caracteres especiales
        if(tieneCaracterEspecialRequest($DesencriptarID)){
            return back()->with(['mensajePInfoRuta'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        
        $ruta= RutaModel::find(decrypt($id));
        if($ruta->delete())
        {
             return back()->with(['mensajePInfoRuta'=>'El registro fué eliminado','estadoP'=>'success']);
        }
        else
        {
             return back()->with(['mensajePInfoRuta'=>'No se pudo eliminar el registro','estadoP'=>'danger']);
        }
     } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoRuta'=>'No se pudo eliminar el registro, ya que se encuentra relacionado','estadoP'=>'danger']);
   }
    }
}
