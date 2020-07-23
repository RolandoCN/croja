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
use PDF;

class ValidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         try {    
           return view('validacion.carnet');
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
    

    public function carnet($idemision)
    {
        $desincriptarIdEmision=base64_decode($idemision);
        $datosDocumentos=EmisionModel::where('idemision',$desincriptarIdEmision)->get();

        return response()->json([
                'error'=>false,
                'resultado'=>$datosDocumentos
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 
    public function reportecarnet($idemision)
    {
         
       //  $desincriptarIdEmision=base64_decode($idemision);
         $ObtenerIdPersona=EmisionModel::where('codigo',$idemision)->first();
         $idpersona=$ObtenerIdPersona->idpersona;
         $fecha=$ObtenerIdPersona->fecha_emision;
         
         $datosPersonaCarnet=PersonaModel::where('idpersona',$idpersona)->first();
         $nombres=$datosPersonaCarnet->nombres;
         $apellidos=$datosPersonaCarnet->apellidos;
         $factor=$datosPersonaCarnet->factor;
         $grupo=$datosPersonaCarnet->grupo;
         $factor_du=$datosPersonaCarnet->factor_du;
         
         $pdf = PDF::loadView('gestionServicios.carnettiposangre',['nombres'=>$nombres,'apellidos'=>$apellidos,'factor'=>$factor,'grupo'=>$grupo,'factor_du'=>$factor_du,'fecha'=>$fecha,'idemision'=>$idemision]);
         
         //$pdf->setPaper([0, 0, 141.732,  85.0394]);// 5x3


         $pdf->setPaper([0, 0, 198.425,  155.906]); //7x5.5.com


         
        return $pdf->download('carnet_tipo_sangre'.$idemision.'.pdf');
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
       //$desincriptarIdEmision=base64_decode($id);
       $ObtenerIdPersona=EmisionModel::where('codigo',$id)->where('estado','Activo')->get();
       
        return view('validacion.show',['datosEmision'=>$ObtenerIdPersona,
                    'idencryp'=>$id]);
 
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function codigo($codigo)
    {
        try {
            //$id = base64_decode($codigo);
            $emision = EmisionModel::with('persona')
                ->where('codigo',$codigo)->where('estado','Activo')
                ->first();

            return response()->json([
                'error'=>false,
                'resultado'=>$emision,
                'codigo'=>$codigo
            ], 200);

        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return response()->json([
                'error'=>true,
                'message'=>$th->getMessage()
            ], 500);
        }
                
    
    }

    
}
