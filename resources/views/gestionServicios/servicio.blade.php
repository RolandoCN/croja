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
                <h3>Gestión Atención</h3>
            </div>
            <br>
        </div>
    </div>

    <div class="clearfix"></div>
      

    <div class="" id="administradorAtención">
        <div class="x_panel">
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>

                <h4><i class="fa fa-bars"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Administrador de Atención</font></font></h4>
                
                
            </div>
            <div class="x_content x_content_border_mobil">

                    <form id="frm_Atención" method="POST" action="{{url('gestionServicios/servicio')}}"  enctype="multipart/form-data"  class="formulario form-horizontal form-label-left">
                        {{csrf_field() }}
                        <input id="method_Detalle" type="hidden" name="_method" value="POST">
                        
                        @if(session()->has('mensajePInfoAtención'))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre_menu"></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="alert alert-{{session('estadoP')}} alert-dismissible fade in" role="alert" style="margin-bottom: 0;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Información: </strong> {{session('mensajePInfoAtención')}}
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="ver" value="{{session('estadoP')}}"id="sms">
                            <input type="hidden" name="id" value="{{session('id')}}"id="persona">
                        @endif

                          <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icono_gestione">Cliente<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="chosen-select-conten">
                        
                                <select data-placeholder="Seleccione una persona"  name="cmb_persona" id="cmb_persona"  class="chosen-select form-control" tabindex="5">
                                    @if(isset($listaPersonas))
                                        @foreach($listaPersonas as $persona) 
                                          <option value=""></option>
                                            <option class="option_servicio" value="{{$persona->idpersona}}">{{$persona->nombres." ".$persona->apellidos}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Fecha de Atención <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="date" id="recibida" name="recibida"  required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icono_gestione">Servicio<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="chosen-select-conten">
                        
                                <select data-placeholder="Seleccione un servicio"  name="cmb_servicio" id="cmb_servicio"  class="chosen-select form-control" tabindex="5">
                                    @if(isset($listaServicios))
                                        @foreach($listaServicios as $servicio) 
                                          <option value=""></option>
                                            <option class="option_servicio" value="{{$servicio->idservicio}}">{{$servicio->detalle_servicio}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                   
                 
                     <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Total <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="number" id="total" name="total" placeholder="Total"step="0.01" min="0" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">interes <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="number" id="interes" name="interes" placeholder="Interes" step="0.01" min="0"required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                       

                         <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_parroquia">Descuento <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="number" id="descuento" name="descuento" placeholder="Descuento" step="0.01" min="0"required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>



                
                         
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <button type="button" id="btn_detalle_cancelar" class="btn btn-warning hidden">Cancelar</button>
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
                                          
                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Cliente</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Fecha Recibida</th>
                                            
                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Servicio</th>

                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Detalles del Servicio</th>
                                           
                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">SubTotal</th>

                                             <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Interés</th>
                                             

                                              <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Descuento</th>

                                               <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10px;">Total</th>
                                          
                                           
                                           
                                            <th  class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 10px;"></th>
                                        </tr>
                                    </thead>

                                    <tbody id="tb_listaZona">
                                        @if(isset($listaEmision))
                                            @foreach($listaEmision as $key=> $datos)
                                           
                                                <tr role="row" class="odd">
                                                    <td>{{$datos->persona['nombres']." ".$datos->persona['apellidos']}}</td>
                                                    <td>{{$datos->fecha_recibida}}</td>
                                                    <td>{{$datos->servicio['detalle_servicio']}}</td>
                                                   
                                                    
                                                    <td>
                                                    	<ul>
                                                    		@foreach($datos->servicio['detalle'] as $detalle2)
                                                    		@if($detalle2->estado=="Activo")
                                                    		<li>{{$detalle2->detalle}}</li>
                                                    		@endif
                                                    		@endforeach
                                                    	</ul>
                                                    </td> 
                                                    <td align="right">{{$datos->subtotal}}</td>
                                                    <td align="right">{{$datos->interes}}</td>
                                                    <td align="right">{{$datos->descuento}}</td>
                                                     <td align="right">{{$datos->valor_total}}</td>
                                                      
                                                    <td class="paddingTR">
                                                        <center>
                                                        <form method="POST" class="frm_eliminar" action="{{url('gestionServicios/servicio/'.encrypt($datos->idemision))}}"  enctype="multipart/form-data">
                                                            {{csrf_field() }} <input type="hidden" name="_method" value="DELETE">
                                                           {{--  <button type="button" onclick="detalle_editar('{{encrypt($datos->idemision)}}')" class="btn btn-sm btn-primary marginB0"><i class="fa fa-edit"></i> Editar</button> --}}
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

{{--                 kk**************************************************************************** --}}
    		{{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<table id="detalles"class="table table-striped table-bordered table-condensed table-hover">
				<thead style="background-color:#0033FF">
					<th>Opciones</th>
				<th>Servicio</th>
				<th>Precio</th>
				<th>Interes</th>
				<th>Descuento</th>

				<th>Subtotal</th>
				</thead>
				<tfoot>
				<th>Total</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>

				<th><h3 id="total">S/. 0.00</h4></th>
				</tfoot>
				<tbody>
				</tbody>
				</table>
			</div>  --}}

                
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

	<script >
//	    $('#bt-add').click(function(){
//			agregar();
//	});

  // var cont=0;
  // total=0;
  // subtotal=[];
 // $("#guardar").hide();
  
  //function agregar()
  // {
	 //  idservicio= $('#cmb_servicio').val();
	 //  servicio=$('#cmb_servicio option:selected').text();
	 //  precio= $('#total').val();
	 //  interes= $('#interes').val();
	 //  descuentop= $('#descuento').val();

	 //  if(idservicio!="" && precio!="" && interes!="" && descuentop!="")
	 //  {
	 //  	subtotal[cont]=(precio + interes -descuentop);
	 //  	total=total+subtotal[cont];

	 //  	var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button"class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idservicio[]"value="'+idservicio+'">'+servicio+'</td> <td><input type="number" name="precio[]"value="'+precio+'"></td><td><input type="number" name="interes[]"value="'+interes+'"></td><td><input type="number" name="descuentop[]"value="'+descuentop+'"></td><td>'+subtotal[cont]+'</td></tr>';
	  

	

	  // idarticulo=$("#pidarticulo").val();
	  // articulo=$("#pidarticulo option:selected").text();
	  // cantidad=$("#pcantidad").val();
	  // precio_compra=$("#pprecio_compra").val();
	  // precio_venta=$("#pprecio_venta").val();
	  
	  // if(idarticulo!="" && cantidad!="" && cantidad>0 && precio_compra!="" && precio_venta!="")
	  // {
	  // subtotal[cont]=(cantidad*precio_compra);
	  // total=total+subtotal[cont];
	  // var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button"class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]"value="'+idarticulo+'">'+articulo+'</td> <td><input type="number" name="cantidad[]"value="'+cantidad+'"></td><td><input type="number" name="precio_compra[]"value="'+precio_compra+'"></td><td><input type="number" name="precio_venta[]"value="'+precio_compra+'"></td><td>'+subtotal[cont]+'</td></tr>';
	 //  cont++;
	 //  limpiar();
	 //  $("#total").html("$/. "+total);
	 //  evaluar();
	 //  $("#detalles").append(fila);
  // }
  // else
  // {
	 //  alert("Error al ingresar el detalle del  ingreso, revise los datos del articulo");
  // }
  // }
  // function limpiar()
  // {
	 //  $("#pcantidad").val("");
	 //  $("#pprecio_compra").val("");
	 //  $("#pprecio_venta").val("");
  // }
  // function evaluar()
  // {
	 //  if(total>0)
	 //  {
		//   $("#guardar").show();
	 //  }
	 //  else
	 //  {
	 //   $("#guardar").hide();
	 //  }
	  
  // }
  // function eliminar(index)
  // {
	 //  total=total-subtotal[index];
	 //  $("#total").html("S/. "+ total);
	 //  $("#fila" + index).remove();
	 //  evaluar();
	  
  // }
  </script>

    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script> -->
    <script src="{{asset('/js/gestionEmision2.js')}}"></script>
   
@endsection