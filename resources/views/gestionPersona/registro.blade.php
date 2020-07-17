@extends('layouts.service')
@section('contenido')
<!-- ADMINISTRACIÓN DE LOS CERTIFICADOS CON SUS REQUISITOS -->
<!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!-- <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
     <link href="{{asset('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">


    <style type="text/css">
        .check_rqs{
            background-color: transparent;
            border: 1px solid #ccc;
            /* border-radius: 30px; */
            /* padding: top right bottom left */
            padding: 8px 5px 6px 15px;
            width: 100%;
        }
        .check_rqs label{
            margin-bottom: 0px !important;
            margin-right: 10px !important;
        }
        .check_rqs .icheckbox_flat-green{
            margin-right: 8px !important;
        }

        /* estilos solo para telefonos */
        @media screen and (max-width: 767px){
            .check_rqs{
                width: 100%;
            }
        }
    </style>
    
  
    <div class="row">
        <div class="col-md-12">
            <div class="title_left">
                <h3>Gestión Persona</h3>
            </div>
            <br>
        </div>
    </div>

    <div class="clearfix"></div>
      

    <div class="" id="administradorPersona">
        <div class="x_panel">
            <div class="x_title">

                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>

                <h4><i class="fa fa-bars"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Administrador de Persona </font></font></h4>
                
               
            </div>
            <div class="x_content x_content_border_mobil">

                    <form id="frm_Persona" method="POST" action="{{url('gestionPersona/registro')}}"  enctype="multipart/form-data"  class="formulario form-horizontal form-label-left">
                        {{csrf_field() }}
                        <input id="method_Persona" type="hidden" name="_method" value="POST">
                        
                        @if(session()->has('mensajePInfoPersona'))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre_menu"></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="alert alert-{{session('estadoP')}} alert-dismissible fade in" role="alert" style="margin-bottom: 0;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Información: </strong> {{session('mensajePInfoPersona')}}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Nombres:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="nombres" name="nombres" placeholder="Nombres" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Apellidos:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Fecha de Nacimiento:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Sexo: <span class="required"></span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <div class="check_rqs">
                                <label for="check_lunes">
                                    <input type="checkbox" value="Femenino" id="check_femenino" class="flat check_rqs" name="check_femenino"> <strong class="no_selecionar">Femenino</strong>
                                </label>
                                <label for="check_martes">
                                        <input type="checkbox" value="Masculino" id="check_masculino" class="flat check_rqs" name="check_masculino" > <strong class="no_selecionar">Masculino</strong>
                                </label>
                                
                             

                                
                            </div>

                        </div>
                      </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Dirección:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="direccion" name="direccion" placeholder="Dirección" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Correo:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="correo" name="correo" placeholder="Correo" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>


                         <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icono_gestione">Cantón<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="chosen-select-conten">
                        
                                <select data-placeholder="Seleccione un cantón"  name="cmb_canton" id="cmb_canton" class="chosen-select form-control" tabindex="5">
                                    @if(isset($listaCantones))
                                        @foreach($listaCantones as $canton) 
                                          <option value=""></option>
                                            <option class="option_canton" value="{{$canton->idcanton}}">{{$canton->detalle}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Factor:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="factor" name="factor" placeholder="Factor" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Grupo:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="grupo" name="grupo" placeholder="Grupo" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Factor_du:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="factor_du" name="factor_du" placeholder="Factor_du" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>


                
                         
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <button type="button" id="btn_personacancelar" class="btn btn-warning hidden">Cancelar</button>
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
                                          
                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Nombres</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Fecha de Nacimiento</th>
                                              <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Correo</th>

                                              <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Cantón</th>

                                               <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Factor</th>

                                                <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Grupo</th>

                                              <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Factor_du</th>

                                             
                                           
                                            
                                            <th  class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 10px;"></th>
                                        </tr>
                                    </thead>

                                    <tbody id="tb_listaZona">
                                        @if(isset($listaPersona))
                                            @foreach($listaPersona as $key=> $datos)
                                                <tr role="row" class="odd">
                                                    
                                                    <td>{{$datos->nombres." ".$datos->apellidos}}</td>
                                                    <td>{{$datos->fecha_nacimiento}}</td>
                                                    <td>{{$datos->email}}</td>
                                                    <td>{{$datos->canton['detalle']}}</td>
                                                    <td>{{$datos->factor}}</td>
                                                    <td>{{$datos->grupo}}</td>
                                                    <td>{{$datos->factor_du}}</td>
                                                   
                                                      </td>
                                                    <td class="paddingTR">
                                                        <center>
                                                        <form method="POST" class="frm_eliminar" action="{{url('gestionPersona/registro/'.encrypt($datos->idpersona))}}"  enctype="multipart/form-data">
                                                            {{csrf_field() }} <input type="hidden" name="_method" value="DELETE">
                                                            <button type="button" onclick="persona_editar('{{encrypt($datos->idpersona)}}')" class="btn btn-sm btn-primary marginB0"><i class="fa fa-edit"></i> Editar</button>
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
     <script src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>
    <!-- <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script> -->
    <script src="{{asset('/js/gestionPersona.js')}}"></script>
   
@endsection