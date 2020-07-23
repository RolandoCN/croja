<!DOCTYPE html>
<html>
<head>
  <title></title>

 {{--  <link rel="stylesheet" type="text/css" href="css/estilotablaUsoSuelo.css"> --}}
     <style type="text/css">

            @page {
            margin-top:0.5em;
            margin-left: 0.5em;
            margin-right:0.5em;
            margin-bottom: 0.3em;}
         

     body {
            width: 100%;
            height: 100%;
            margin: 0px;
            padding: 0;
            
            font:12px "Tahoma";
            line-height:1.3em;


        }
#a,#bod{
  border-top: red 1px solid;
  border-left: red 1px solid;
  border-right: red 1px solid;
  border-bottom: 0px;
}
#foo{
  border: red 1px solid;
}
tr {
  border: red 1px solid;
}
td {
/*  border: red 1px solid;*/
}
h3 {
  line-height: 0.5em;
}

     </style>

  
</head>

<body>
   

{{-- <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($codigo_imprimir, 'C39E')}}" width="100%" height="20" alt="barcode" /><br><br> --}}


{{--  <center><strong>CÉDULA / RUC: </strong>{{$cedula_imprimir}}</center><br>


  <center><strong>FECHA:</strong> {{$fecha}}</center>
    --}}
<center>
<table width="90%">
  
  <tr>
    <td width="10%"><img src="images/551.png"width="60px" height="70px"></td>
    <td><center><strong><span style="color:black">Cruz Roja Ecuatoriana</span></strong></center>
     
       <center><strong><span style="color:black">Chone - Manabí</span></strong></center>
        <center><strong><span style="color:black">Grupo Sanguíneo "{{$grupo}}"</span></strong></center>
    </td>
   
  </tr>


</table>
</center>

<table width="100%">
 <thead id="a"> 
  <tr>
   
    <td style="background-color: red;"><span style="font-size: 10px;color: white">FACTOR</span></td>
    <td><span style="font-size:10px;text-transform: uppercase;">{{$factor}}</span><</td>
    <td align="right"><span style="font-size:10px;text-transform: uppercase;">DU:{{$factor_du}}</span></td>
  </tr>
  </thead>
  <tbody id="bod">
  <tr>
     <td style="background-color: red;"><span style="font-size: 10px;color: white">NOMBRES</span></td>
    <td colspan="2">
      <span style="font-size:10px;text-transform: uppercase;">{{$nombres}}</span><br>
      <span style="font-size:10px;text-transform: uppercase;">{{$apellidos}}</span>

    </td>
  </tr>
  </tbody>
  <tfoot id="foo">
   <tr>
     <td with="50%" style="background-color: red"><span style="font-size: 10px;color: white">FECHA EMISIÓN</span></td>
    <td ><span style="font-size:10px">{{$fecha}}</span></td>
    <td></td>
   
  </tr>
</tfoot>
 


</table>

<div style="page-break-after:always;"></div>

<?php $link="www.facebook.com";?>

 <center>
 <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')
                  ->size(155)->backgroundColor(255,255,255)
                  ->generate(env('APP_URL').'/validacion/carnet/'.$idemision))}}">

</center>
<center><strong><span style="font-size: 7">Verifica la válidez de este documento leyendo el código QR</span></strong></center>
</body>
</html>