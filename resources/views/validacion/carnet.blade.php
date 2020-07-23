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
                <h3>Validación de Certificados</h3>
            </div>
            <br>
        </div>
    </div>

    <div class="clearfix"></div>
      

    <div class="" id="administradorCanton">
        <div class="x_panel">
            <div class="x_title">
                  <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                 <h4><i class="fa fa-bars"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Carnet tipo sangre </font></font></h4>
              
                {{-- <div class="clearfix"></div> --}}
            </div>

            <div class="x_content x_content_border_mobil">
                <div style="display: none"  id="infoBusqueda"></div>

                     <div>
                       <div> 
                            <center><i style="font-size: 80px; color: #337ab7;" class="fa fa-search"></i></center><br><br>
                        </div>
                    </div>

                     <form id="frmBuscar" action="detalle/" class="form-horizontal form-label-left input_mask">

                     
                    <div class="form-group">
                      <!-- <div class="col-md-4 col-sm-6 col-xs-12 form-group "> -->
                        <label class="control-label col-md- col-sm-3 col-xs-12" for=""for="cmb_departamento">Código:</label>
                         <div class="col-md-6 col-sm-6 col-xs-12 form-group ">
                          <input type="text" name="busqueda" id="busqueda" class="form form-control col-md-6 col-sm-6 col-xs-12">

                        </div>
                      </div>
                     
                       <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"id="buscar">
                               <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>

                            </div>
                        </div>
                        <div class="ln_solid"></div>
                            
                  </form>
                  <div class="table-responsive hidden" id="j">
                        <div class="row">
                            <div class="col-sm-12">
                                <table style="color: black"  id="datatableee" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                    <thead>
                                        <tr role="row">
                                          
                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Nombres</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Grupo Sanguíneo</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Factor</th>

                                           <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Factor_du</th>

                                           <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Fecha Emision</th>
                                            
                                            <th  class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 10px;"></th>
                                        </tr>
                                    </thead>

                                    <tbody id="tb_listaZona">
                                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

    

                    
                
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

    {{-- Datatables --}}
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('/js/gestionCarnet.js')}}"></script>
   
@endsection