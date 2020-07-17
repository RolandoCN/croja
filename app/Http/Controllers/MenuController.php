<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\funcionesGlobales;
use App\RutaModel;
use App\MenuModel;
use Log;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        //LISTAMOS LOS MENUS REGISTRADAS EN LA BASE DE DATOS Y LA PASAMOS A LA VISTA
        $listaMenu=MenuModel::all();
        
        return view('gestionAccesos.menu')->with(['listaMenu'=>$listaMenu]);
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

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoMenu'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
            //GUARDAMOS LOS MENUS EN LA BASE DE DATOS
            $menu= new MenuModel();
            $menu->descripcion=$request->get('descripcion');
            $menu->icono=$request->get('icon_menu');
           
            $menuexistente=MenuModel::where('descripcion',$menu->descripcion)->first();
           if(!is_null($menuexistente))
           {
            return back()->with(['mensajePInfoMenu'=>'Menú no ingresado, el registro ya existe','estadoP'=>'danger']);
           }

           if($menu->save()){
                return back()->with(['mensajePInfoMenu'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoMenu'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoMenu'=>'No se pudo realizar el registro','estadoP'=>'danger']);
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
            $menu=MenuModel::find($id);
            return response()->json([
                'error'=>false,
                'resultado'=>$menu
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
                return back()->with(['mensajePInfoMenu'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
            //ACTUALIZAMOS LOS MENUS EN LA BASE DE DATOS
            $menu= MenuModel::find(decrypt($id));
            $menu->descripcion=$request->get('descripcion');
            $menu->icono=$request->get('icon_menu');

            $idmenu= decrypt($id);
             $sieselmismomenu=MenuModel::where('descripcion',$menu->descripcion)
             ->where('icono',$menu->icono)
            ->where('idmenu',$idmenu)->first();
            if(!is_null($sieselmismomenu))
           {
            $menu->save();
            return back()->with(['mensajePInfoMenu'=>'Actualización exitosa ','estadoP'=>'success']);
            //return back()->with(['mensajePInfoProvincia'=>'Provincia no actualizada, el registro ya existe','estadoP'=>'danger']);
           }

            $menuexistente=MenuModel::where('descripcion',$menu->descripcion)
            ->where('icono',$menu->icono)->first();

            if(!is_null($menuexistente))
           {
            return back()->with(['mensajePInfoMenu'=>'Menú no actualizado, el registro ya existe','estadoP'=>'danger']);
           
           }

            if($menu->save()){
                return back()->with(['mensajePInfoMenu'=>'Actualización exitosa','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoMenu'=>'No se pudo realizar la actualización','estadoP'=>'danger']);
            }

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoMenu'=>'No se pudo actualizar el registro','estadoP'=>'danger']);
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
            return back()->with(['mensajePInfoMenu'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };

        $idmenuGet=decrypt($id);
        
        $ruta=RutaModel::where('idmenu',$idmenuGet)->first();
      
        if(!is_null($ruta))
        {
            return back()->with(['mensajePInfoMenu'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }

        $menu= MenuModel::find(decrypt($id));
        if($menu->delete())
        {
             return back()->with(['mensajePInfoMenu'=>'El registro fué eliminado','estadoP'=>'success']);
        }
        else
        {
             return back()->with(['mensajePInfoMenu'=>'No se pudo eliminar el registro','estadoP'=>'danger']);
        }
    }
}
