<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CantonModel;
use App\ServicioModel;
use App\DetalleServicioModel;
use Log;

class DetalleServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try {        
            $listaDetalleServicio = DetalleServicioModel::with('servicio')->where('estado','Activo')->get(); // obtenemos todas los detalles con los respectivo servicios a la que petenece
            
            $listaServicio = ServicioModel::where('estado','Activo')->get();// obtenemos todas los servicios para cargar en el combo
           ;
            return view('gestionServicios.detalle',[
                'listaDetalleServicio'=> $listaDetalleServicio,
                'listaServicio'=>$listaServicio
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
                
                'detalle'=>$request->get('detalle'),
                'idservicio'=>$request->get('cmb_servicio')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoDetalleServicio'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };

            if(is_null($request->get('detalle')))
               {
                return back()->with(['mensajePInfoDetalleServicio'=>'Ingrese un detalle','estadoP'=>'danger']);

               }


            if(is_null($request->get('cmb_servicio')))
               {
                return back()->with(['mensajePInfoDetalleServicio'=>'Seleccione un servicio','estadoP'=>'danger']);

               }

             

            //GUARDAMOS LOS DETALLES EN LA BASE DE DATOS
            $detalle= new DetalleServicioModel();
            $detalle->detalle=$request->get('detalle');
            $detalle->estado="Activo";
            $detalle->idservicio = $request->get('cmb_servicio');

            
           $detalleexistente=DetalleServicioModel::where('detalle',$detalle->detalle)
            ->where('idservicio',$detalle->idservicio)->where('estado','Activo')->first();
            if(!is_null($detalleexistente))
           {
            return back()->with(['mensajePInfoDetalleServicio'=>'Detalle no ingresado, el registro ya existe','estadoP'=>'danger']);
           }

           $detalleActualizar=DetalleServicioModel::where('detalle',$detalle->detalle)
            ->where('idservicio',$detalle->idservicio)->where('estado','Eliminado')->first();

             if(!is_null($detalleActualizar))
           {
            $detalleActualizar->estado="Activo";
            $detalleActualizar->save();
            return back()->with(['mensajePInfoDetalleServicio'=>'Registro exitoso','estadoP'=>'success']);
           }

            if($detalle->save()){
                return back()->with(['mensajePInfoDetalleServicio'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoDetalleServicio'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoDetalleServicio'=>'Error el realizar el registro','estadoP'=>'danger']);
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
            $iddetalle = decrypt($id);
            $etalle = DetalleServicioModel::with('servicio')
                ->where('iddetalle_servicio',$iddetalle)
                ->first();

            return response()->json([
                'error'=>false,
                'resultado'=>$etalle
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
            $iddetalle = decrypt($id);
            // CREAMOS ARREGLO PARA ENVIAR A LA FUNCION A VALIDAR CARACTERES ESPECIALES
            $validaCE=array(
                'detalle'=>$request->get('detalle'),
                'idservicio'=>$request->get('cmb_servicio')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoDetalleServicio'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };

            if(is_null($request->get('detalle')))
               {
                return back()->with(['mensajePInfoDetalleServicio'=>'Ingrese un detalle','estadoP'=>'danger']);

               }


            if(is_null($request->get('cmb_servicio')))
               {
                return back()->with(['mensajePInfoDetalleServicio'=>'Seleccione un servicio','estadoP'=>'danger']);

               }


            //GUARDAMOS Los detalles EN LA BASE DE DATOS
            $detalle= DetalleServicioModel::find($iddetalle);
            $detalle->detalle=$request->get('detalle');
            $detalle->estado="Activo";
            $detalle->idservicio = $request->get('cmb_servicio');

            $iddetalle= decrypt($id);
            $sieselmismocdetalle=DetalleServicioModel::where('detalle',$detalle->detalle)
             ->where('estado',$detalle->estado)
             ->where('idservicio',$detalle->idservicio)
             ->where('iddetalle_servicio',$iddetalle)->first();
            if(!is_null($sieselmismocdetalle))
           {
            $detalle->save();
            return back()->with(['mensajePInfoDetalleServicio'=>'Actualización exitosa ','estadoP'=>'success']);
            
           }


            $detalleexistente=DetalleServicioModel::where('detalle',$detalle->detalle)
            ->where('idservicio',$detalle->idservicio)->first();
            if(!is_null($detalleexistente))
           {
            return back()->with(['mensajePInfoDetalleServicio'=>'Detalle no actualizado, el registro ya existe','estadoP'=>'danger']);
           }

         

            if($detalle->save()){
                return back()->with(['mensajePInfoDetalleServicio'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoDetalleServicio'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoDetalleServicio'=>'Error el realizar el registro','estadoP'=>'danger']);
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
            return back()->with(['mensajePInfoDetalleServicio'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        
        $detalle= DetalleServicioModel::find(decrypt($id));
        $detalle->estado="Eliminado";
        if($detalle->save())
        {
             return back()->with(['mensajePInfoDetalleServicio'=>'El registro fué eliminado','estadoP'=>'success']);
        }
    }
}
