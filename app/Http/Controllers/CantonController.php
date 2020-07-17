<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CantonModel;
use App\ProvinciaModel;
use App\PersonaModel;
use Log;

class CantonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try { 
        // obtenemos todas los cantones con la respectiva provincias a la que petenece     
            $listaCantones = CantonModel::with('provincia')->orderBy('idprovincia')->where('estado','Activo')->get();

        // obtenemos todas las provincias para cargar en el combo
            $listaProvincias = ProvinciaModel::where('estado','Activo')->get();
         
         //retornamos a la vista con los respectivos valores 
            return view('gestionResidencia.canton',[
                'listaCantones'=> $listaCantones,
                'listaProvincias'=>$listaProvincias
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
                
                'detalle'=>$request->get('canton'),
                'idprovincia'=>$request->get('cmb_canton_provincia')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoCanton'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
            
             if(is_null($request->get('canton')))
            {
                    return back()->with(['mensajePInfoCanton'=>'Ingrese un nombre de cantón','estadoP'=>'danger']);

            }

             if(is_null($request->get('cmb_canton_provincia')))
            {
                    return back()->with(['mensajePInfoCanton'=>'Seleccione una provincia','estadoP'=>'danger']);

            }
            //GUARDAMOS LOS CANTONES EN LA BASE DE DATOS
            $canton= new CantonModel();
            $canton->detalle=$request->get('canton');
            $canton->estado="Activo";
            $canton->idprovincia = $request->get('cmb_canton_provincia');

            //si existe un misnmo canton en una misma provincia no guardamos y manadamos un sms de error
            $cantonexistente=CantonModel::where('detalle',$canton->detalle)
            ->where('idprovincia',$canton->idprovincia)->where('estado','Activo')->first();
            if(!is_null($cantonexistente))
               {
                return back()->with(['mensajePInfoCanton'=>'Cantón no ingresado, el registro ya existe','estadoP'=>'danger']);
               }

            //si el registro a sido eliminaddo lo actualizamos el estado 
            $cantonActualizar=CantonModel::where('detalle',$canton->detalle)
            ->where('idprovincia',$canton->idprovincia)->where('estado','Eliminado')->first();

            if(!is_null($cantonActualizar))
               {
                $cantonActualizar->estado="Activo";
                $cantonActualizar->save();
                return back()->with(['mensajePInfoCanton'=>'Registro exitoso','estadoP'=>'success']);
               }

            if($canton->save())
                {
                return back()->with(['mensajePInfoCanton'=>'Registro exitoso','estadoP'=>'success']);
                 }
            else
              {
                return back()->with(['mensajePInfoCanton'=>'No se pudo realizar el registro','estadoP'=>'danger']);
              }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoCanton'=>'Error el realizar el registro','estadoP'=>'danger']);
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
            $idcanton = decrypt($id);
            $canton = CantonModel::with('provincia')
                ->where('idcanton',$idcanton)
                ->first();

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $idcanton = decrypt($id);
            // CREAMOS ARREGLO PARA ENVIAR A LA FUNCION A VALIDAR CARACTERES ESPECIALES
            $validaCE=array(
                'detalle'=>$request->get('canton'),
                'idprovincia'=>$request->get('cmb_canton_provincia')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoCanton'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };

              if(is_null($request->get('canton')))
            {
                    return back()->with(['mensajePInfoCanton'=>'Ingrese un nombre de cantón','estadoP'=>'danger']);

            }

             if(is_null($request->get('cmb_canton_provincia')))
            {
                    return back()->with(['mensajePInfoCanton'=>'Seleccione una provincia','estadoP'=>'danger']);

            }

            //GUARDAMOS EL CANTON EN LA BASE DE DATOS
            $canton= CantonModel::find($idcanton);
            $canton->detalle=$request->get('canton');
            $canton->estado="Activo";
            $canton->idprovincia = $request->get('cmb_canton_provincia');

            $idcanton= decrypt($id);
            $sieselmismocanton=CantonModel::where('detalle',$canton->detalle)
             ->where('estado',$canton->estado)
             ->where('idprovincia',$canton->idprovincia)
             ->where('idcanton',$idcanton)->first();
            if(!is_null($sieselmismocanton))
           {
            $canton->save();
            return back()->with(['mensajePInfoCanton'=>'Actualización exitosa ','estadoP'=>'success']);
            
           }


            $cantonexistente=CantonModel::where('detalle',$canton->detalle)
             ->where('estado',$canton->estado)->first();
            if(!is_null($cantonexistente))
           {
            return back()->with(['mensajePInfoCanton'=>'Cantón no actualizado, el registro ya existe','estadoP'=>'danger']);
           }

           $cantonExistenteEstadoEliminado=CantonModel::where('detalle',$canton->detalle)->where('estado','Eliminado')
           ->first();
            if(!is_null($cantonExistenteEstadoEliminado))
           {
            return back()->with(['mensajePInfoCanton'=>'Cantón no actualizado, el registro ya existe','estadoP'=>'danger']);
           }


            if($canton->save()){
                return back()->with(['mensajePInfoCanton'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoCanton'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoCanton'=>'Error el realizar el registro','estadoP'=>'danger']);
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
            return back()->with(['mensajePInfoCanton'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        //BUSCAMOS EL REGISTRO
        $idcantonGet=decrypt($id);
        

        $persona=PersonaModel::where('estado','Activo')->where('idcanton',$idcantonGet)->first();
       // dd($canton);
        if(!is_null($persona))
        {
            return back()->with(['mensajePInfoCanton'=>'No se pudo eliminar el registro ya que se encuentra relacionado','estadoP'=>'danger']);
        }
        $canton= CantonModel::find(decrypt($id));
        $canton->estado="Eliminado";
        if($canton->save())
        {
             return back()->with(['mensajePInfoCanton'=>'El registro fué eliminado','estadoP'=>'success']);
        }
    }
}
