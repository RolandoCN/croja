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
                <h3>Gestión Accesos</h3>
            </div>
            <br>
        </div>
    </div>

    <div class="clearfix"></div>
      

    <div class="" id="administradorOpciones">
        <div class="x_panel">
            <div class="x_title">
                  <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                 <h4><i class="fa fa-bars"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Administrador de Rutas por Tipos Usuario </font></font></h4>
              
                {{-- <div class="clearfix"></div> --}}
            </div>

            <div class="x_content x_content_border_mobil">

                  <form id="frm_Opciones" method="POST" action="{{url('gestionAccesos/opciones')}}"  enctype="multipart/form-data"  class="formulario form-horizontal form-label-left">
                        {{csrf_field() }}
                        <input id="method_Opciones" type="hidden" name="_method" value="POST">
                        
                        @if(session()->has('mensajePInfoOpciones'))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre_menu"></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="alert alert-{{session('estadoP')}} alert-dismissible fade in" role="alert" style="margin-bottom: 0;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Información: </strong> {{session('mensajePInfoOpciones')}}
                                    </div>
                                </div>
                            </div>
                        @endif
                     
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icono_gestione">Tipo Usuario<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="chosen-select-conten">
                        
                                <select data-placeholder="Seleccione un tipo usuario"  name="cmb_tipousuario" id="cmb_tipousuario"  class="chosen-select form-control" tabindex="5">
                                    @if(isset($listaUsuario))
                                        @foreach($listaUsuario as $dato) 
                                          <option value=""></option>
                                            <option class="option_tipoUsuario" value="{{$dato->idtipo_usuario}}">{{$dato->detalle}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icono_gestione">Gestión<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="chosen-select-conten">
                        
                                <select data-placeholder="Seleccione una gestión" multiple="" name="cmb_ruta[]" id="cmb_ruta" class="chosen-select form-control" tabindex="5">
                                    @if(isset($listaRuta))
                                     <option value=""></option>
                                        @foreach($listaRuta as $dato) 
                                          <optgroup label="{{$dato->descripcion}}">
                                            @foreach($dato->ruta as $ruta)
                                            <option class="opcion_gestion" value="{{$ruta->idruta}}">{{$ruta->descripcion}}</option>
                                            @endforeach
                                        <optgroup>
                                        @endforeach
                                      
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                
                         
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <button type="button" id="btn_opciones_cancelar" class="btn btn-warning hidden">Cancelar</button>
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

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Tipo Usuario</th>
                                              <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Gestión</th>

                                           
                                            
                                            <th  class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 10px;"></th>
                                        </tr>
                                    </thead>

                                    <tbody id="tb_listaZona">
                                        @if(isset($listaOpciones))
                                            @foreach($listaOpciones as $key=> $datos)
                                                <tr role="row" class="odd">

                                                      <td>{{$key+1}}</td>
                                                      <td>{{$datos->detalle}}</td>
                                                        <td>
                                                            @if($datos->detalle=="Administrador")
                                                            <ul style="margin-bottom: 0px; padding-left: 18px;">
                                                             <li> <i class="fa fa-star"></i> Todas </li> 
                                                         </ul>
                                                         @else


                                                         <ul style="margin-bottom: 0px; padding-left: 18px;">
                                                        @foreach($datos->tipoUsuarioGestion as $g=> $gestion)       
                                                                                              
                                                            <li> <i class="{{$gestion->ruta['icoono']}}"></i> {{$gestion->ruta['descripcion']}}  </li> 
                                                                                                               
                                                        @endforeach
                                                        </ul> 
                                                        @endif
                                                    </td>  
                                                   
                                                      </td>
                                                    <td class="paddingTR">
                                                        <center>
                                                        <form method="POST" class="frm_eliminar" action="{{url('gestionAccesos/opciones/'.encrypt($datos->idtipo_usuario))}}"  enctype="multipart/form-data">
                                                            {{csrf_field() }}
                                                             @if($datos->detalle=="Administrador")
                                                             <input type="hidden" name="_method" value="DELETE">
                                                            <button type="button" disabled="" onclick="opciones_editar('{{encrypt($datos->idtipo_usuario)}}')" class="btn btn-sm btn-primary marginB0"><i class="fa fa-edit"></i> Asignar</button>
                                                            @else
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="button" onclick="opciones_editar('{{encrypt($datos->idtipo_usuario)}}')" class="btn btn-sm btn-primary marginB0"><i class="fa fa-edit"></i> Asignar</button>
                                                            @endif
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
    <script src="{{asset('/js/gestionOpciones.js')}}"></script>
   
@endsection