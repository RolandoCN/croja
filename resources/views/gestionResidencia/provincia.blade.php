@extends('layouts.service')
@section('contenido')
<!-- ADMINISTRACIÓN DE LOS CERTIFICADOS CON SUS REQUISITOS -->
<!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!-- <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <style type>
 .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}

    </style>
  

    <div class="row">
        <div class="col-md-12">
            <div class="title_left">
                <h3>Gestión Residencia</h3>
            </div>
            <br>
        </div>
    </div>

    <div class="clearfix"></div>
      

    <div class="" id="administradorProvincia">
        <div class="x_panel">
            <div class="x_title">
                
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <h4><i class="fa fa-bars"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Administrador de Provincia </font></font></h4>
                
               
            </div>
            <div class="x_content x_content_border_mobil">

                    <form id="frm_Provincia" method="POST" action="{{url('gestionResidencia/provincia')}}"  enctype="multipart/form-data"  class="form-horizontal form-label-left">
                        {{csrf_field() }}
                        <input id="method_Provincia" type="hidden" name="_method" value="POST">
                        
                        @if(session()->has('mensajePInfoProvincia'))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre_menu"></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="alert alert-{{session('estadoP')}} alert-dismissible fade in" role="alert" style="margin-bottom: 0;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Información: </strong> {{session('mensajePInfoProvincia')}}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- <div class="row">
        <div id="content" class="col-lg-12">
            
        </div>
    </div> -->
    <!-- <div class="loader"></div> -->

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Nombre de la Provincia <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="provincia" name="provincia" placeholder="Provincia" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                      

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <button type="button" id="btn_provincia_cancelar" class="btn btn-warning hidden">Cancelar</button>
                            </div>
                        </div>
                        <div class="ln_solid"></div>

                    </form>

                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-sm-12">
                                <table style="color: black"  id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                    <thead>
                                        <tr role="row">
                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">#</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Provincia</th>
                                           
                                            <th  class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 10px;"></th>
                                        </tr>
                                    </thead>

                                    <tbody id="tb_listaZona">
                                        @if(isset($listaProvincia))
                                            @foreach($listaProvincia as $key=> $datos_provincia)
                                                <tr role="row" class="odd">
                                                   <td>{{$key+1}}</td>
                                                    <td>{{$datos_provincia->detalle}}</td>
                                                   
                                                    <td class="paddingTR">
                                                        <center>
                                                        <form method="POST" class="frm_eliminar" action="{{url('gestionResidencia/provincia/'.encrypt($datos_provincia->idprovincia))}}"  enctype="multipart/form-data">
                                                            {{csrf_field() }} <input type="hidden" name="_method" value="DELETE">
                                                            <button type="button" onclick="provincia_editar('{{encrypt($datos_provincia->idprovincia)}}')" class="btn btn-sm btn-primary marginB0"><i class="fa fa-edit"></i> Editar</button>
                                                            <button type="button" class="btn btn-sm btn-danger marginB0" onclick="btn_eliminar(this)"><i class="fa fa-trash"></i> Eliminar</button>
                                                        </form>
                                                        </center>
                                                    </td>
                                                </tr>                        
                                            @endforeach
                                        @endif                   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

    <!-- <br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br> -->



                
            </div>
        </div>
    
    </div>


<script type="text/javascript">
        $(document).ready(function () {
        // $('.button').on('click', function(){
        // //Añadimos la imagen de carga en el contenedor
        // $('#content').html('<div class="loader"></div>');

        
        
    //}); 


//        $(window).load(function() {
//     $(".loader").fadeOut("slow");
// });


  

            $("#datatable").DataTable({
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por pagina",
            "zeroRecords": "No se encontraron resultados en su busqueda",
            "searchPlaceholder": "Buscar registros",
            "info": "Mostrando registros de _START_ al _END_ de un total de  _TOTAL_ registros",
            "infoEmpty": "No existen registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
        }
    }); 
            $('.collapse-link').click();
            $('.datatable_wrapper').children('.row').css('overflow','inherit !important');
            $('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0','overflow-x':'inherit'});
          });
    </script>
   

    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script> -->
    <script src="{{asset('/js/gestionProvincia.js')}}"></script>
   
@endsection