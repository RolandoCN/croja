<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ServicioModel;
use App\DetalleServicioModel;
use App\PersonaModel;
use App\EmisionModel;
use App\DetalleEmisionModel;
use Log;

class EmisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         try {        
            $listaEmision = EmisionModel::with('persona','servicio')->where('estado','Activo')->get();

            $listaServicios=ServicioModel::where('estado','Activo')->get();            
            $listaPersonas = PersonaModel::where('estado','Activo')->get();
            return view('gestionServicios.servicio',[
                'listaEmision'=> $listaEmision,
                'listaServicios'=> $listaServicios,
                'listaPersonas'=>$listaPersonas
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
                
                'idcliente'=>$request->get('cmb_persona'),
                'idservicio'=>$request->get('cmb_servicio')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoAtención'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
             //dd($request->get('clave'));

            $st=$request->get('total');
            $subtotal=number_format($st, 2, '.', '');
            //dd($subtotal);

            $int=$request->get('interes');
            $interes=number_format($int, 2, '.', '');

            $des=$request->get('descuento');
            $descuento=number_format($des, 2, '.', '');
                      
            $valor_total_aux=($subtotal+$interes)-$descuento;
            $valor_total=number_format($valor_total_aux, 2, '.', '');

            if(is_null($request->get('cmb_persona')))
            {
                return back()->with(['mensajePInfoAtención'=>'Seleccione un cliente','estadoP'=>'danger']);

            }

            if(is_null($request->get('cmb_servicio')))
            {
                return back()->with(['mensajePInfoAtención'=>'Seleccione un servicio','estadoP'=>'danger']);

            }
            if(is_null($request->get('recibida')))
            {
                return back()->with(['mensajePInfoAtención'=>'Seleccione la fecha de recibida','estadoP'=>'danger']);

            }

            


            //GUARDAMOS LA EMISION EN LA BASE DE DATOS
            $emision= new EmisionModel();
            $emision->idpersona=$request->get('cmb_persona');
            $emision->fecha_recibida=$request->get('recibida');
            $emision->idservicio=$request->get('cmb_servicio');
            $emision->valor_total=$valor_total;
            $emision->interes=$interes;
            $emision->subtotal=$subtotal;
            $emision->descuento=$descuento;
            $emision->fecha_emision=date('d/m/Y H:i:s');
            $emision->idusuario=auth()->User()->idusuario;
            $emision->estado="Activo";
           
                    
          
            if($emision->save()){
            $idemision=$emision->idemision;
            $idservicio=$emision->idservicio;
            $detalleservicio=DetalleServicioModel::where('idservicio',$idservicio)->where('estado','Activo')->get();
            foreach ($detalleservicio as $key => $value) {
                $guardaDetalleEmision=new DetalleEmisionModel();
                $guardaDetalleEmision->idemision=$idemision;
                $guardaDetalleEmision->iddetalle_servicio=$value->iddetalle_servicio;
                $guardaDetalleEmision->valor=$valor_total;
                $guardaDetalleEmision->save();
            }

           return back()->with(['mensajePInfoAtención'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoAtención'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoAtención'=>'Error el realizar el registro','estadoP'=>'danger']);
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
            return back()->with(['mensajePInfoAtención'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        //BUSCAMOS EL REGISTRO
        $idemisionGet=decrypt($id);
        
        $emision= EmisionModel::find(decrypt($id));
        $emision->estado="Eliminado";
        if($emision->save())
        {
             return back()->with(['mensajePInfoAtención'=>'El registro fué eliminado','estadoP'=>'success']);
        }
    }
}
