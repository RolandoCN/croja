@extends('layouts.service')
@section('contenido')
<!-- ADMINISTRACIÓN DE LOS CERTIFICADOS CON SUS REQUISITOS -->
<!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!-- <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
   

    <div class="row">
        <div class="col-md-12">
            <div class="title_left">
                <h3>Gestión Servicios</h3>
            </div>
            <br>
        </div>
    </div>

    <div class="clearfix"></div>
      

    <div class="" id="administradorServicio">
        <div class="x_panel">
            <div class="x_title">
                 <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
               
                <h4><i class="fa fa-bars"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Administrador de Servicios </font></font></h4>
               
            </div>
            <div class="x_content x_content_border_mobil">

                    <form id="frm_Servicio" method="POST" action="{{url('gestionServicios/registro')}}"  enctype="multipart/form-data"  class="form-horizontal form-label-left">
                        {{csrf_field() }}
                        <input id="method_Servicio" type="hidden" name="_method" value="POST">
                        
                        @if(session()->has('mensajePInfoServicio'))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre_menu"></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="alert alert-{{session('estadoP')}} alert-dismissible fade in" role="alert" style="margin-bottom: 0;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Información: </strong> {{session('mensajePInfoServicio')}}
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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Servicio <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="detalle" name="detalle" placeholder="Servicio" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                      

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <button type="button" id="btn_servicio_cancelar" class="btn btn-warning hidden">Cancelar</button>
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

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Servicio</th>
                                           
                                            <th  class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 10px;"></th>
                                        </tr>
                                    </thead>

                                    <tbody id="tb_listaZona">
                                        @if(isset($listaServicio))
                                            @foreach($listaServicio as $key=> $datos)
                                                <tr role="row" class="odd">
                                                   <td>{{$key+1}}</td>
                                                    <td>{{$datos->detalle_servicio}}</td>
                                                   
                                                    <td class="paddingTR">
                                                        <center>
                                                        <form method="POST" class="frm_eliminar" action="{{url('gestionServicios/registro/'.encrypt($datos->idservicio))}}"  enctype="multipart/form-data">
                                                            {{csrf_field() }} <input type="hidden" name="_method" value="DELETE">
                                                            <button type="button" onclick="servicio_editar('{{encrypt($datos->idservicio)}}')" class="btn btn-sm btn-primary marginB0"><i class="fa fa-edit"></i> Editar</button>
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
    <script src="{{asset('/js/gestionServicio.js')}}"></script>
   
@endsection