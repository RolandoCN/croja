$('#frm_buscarEmision').submit(function(){

		event.preventDefault();
		$('#divbtnbuscar').html(`<button disabled=true  type="button"  class="btn btn-primary"><span class="spinner-border " role="status" aria-hidden="true"></span> Buscando</button>`);  
		$('#tablaEmisiones').hide();
		$('#panelInfor').hide();
		$('#ResultEmi').html('');
		
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: "POST",
			url: '/comprobantes/listacomprobantesR',
			// data: e.serialize(),
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			timeout: 50000, //50 segundos
			success: function(data){
				console.log(data);

				
				if(data['error']==true){
					$('#divbtnbuscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);
					$('#infoBusqueda').html('');
		    		$('#infoBusqueda').append(`<div style="background-color: #f8d7da;color: black" class="alert  alert-dismissible fade in" role="alert">
		                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		                    </button>
		                    <strong>¡Atención!</strong> ${data['detalle']}.
		                  </div>`);
		    		$('#infoBusqueda').show(200);
		    		// setTimeout(function() {
		  	 		// $('#infoBusqueda').hide(200);
		  	 		// },  3000);
					return;
				}
				if(data['vacio']==true){
					$('#divbtnbuscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);
				    //MOSTRAR INFORMACION DE BUSQUEDAS
				    $('#infoBusqueda').html('');
		    		$('#infoBusqueda').append(`<div style="background-color: #cce5ff;color: black" class="alert  alert-dismissible fade in" role="alert">
		                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		                    </button>
		                    <strong>¡Atención!</strong> ${data['detalle']}.
		                  </div>`);
		    		$('#infoBusqueda').show(200);
		    		setTimeout(function() {
		  	 		$('#infoBusqueda').hide(200);
		  	 		},  3000);
					return;
				}
				if(data['error']==false){
				   $('#tablaEmisiones').show();

				    $('#divbtnbuscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);

					cargartablejs(data['detalle']);
					$('#panelInfor').html(`<div  class="x_content x_content_border_mobil">
												<center><p style="color:black"><b>DATOS DEL CONTRIBUYENTE</b></p></center>
					                        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
						                        <div class="form-group" style="color: black">
											            <b>Identificación:</b> ${data['detalle'][0]['identificacion']}<br>
														<b>Razón Social:</b> ${data['detalle'][0]['razonSocial']}<br>
						                        </div>
					                        </div>
	                    				</div>`);
					$('#panelInfor').show(200);

				}	
		},
		error: function(e){
	        if (e.statusText==='timeout'){
	          $('#divbtnbuscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);
			    //MOSTRAR INFORMACION DE BUSQUEDAS
			    $('#infoBusqueda').html('');
	    		$('#infoBusqueda').append(`<div style="background-color: #f8d7da;color: black" class="alert  alert-dismissible fade in" role="alert">
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	                    </button>
	                    <strong>¡Atención!</strong> Tiempo de espera agotado intente nuevamente.
	                  </div>`);
	    		$('#infoBusqueda').show(200);
	    		setTimeout(function() {
	  	 		$('#infoBusqueda').hide(200);
	  	 		},  3000);
				return;
	        }
	        else{
	           $('#divbtnbuscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);
			    //MOSTRAR INFORMACION DE BUSQUEDAS
			    $('#infoBusqueda').html('');
	    		$('#infoBusqueda').append(`<div style="background-color: #f8d7da;color: black" class="alert  alert-dismissible fade in" role="alert">
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	                    </button>
	                    <strong>¡Atención!</strong> Ocurrió un error intente nuevamente.
	                  </div>`);
	    		$('#infoBusqueda').show(200);
	    		setTimeout(function() {
	  	 		$('#infoBusqueda').hide(200);
	  	 		},  3000);
				return;
	        }
	    }
	});
});



function cargartablejs(data){
     $('#idtable_emisiones').DataTable({
         "destroy":true,
         pageLength: 10,
         sInfoFiltered:false,
         data: data,
         order: [[2,'desc']],

         columns:[
         {data: "idemision" },
         {data: "periodo" },
         // {data: "claveAcceso"},
         {data : "fechaIngreso"},
         {data: "fechaEnvio"},
         {data: "fechaAutorizacion" },
         // {data: "numeroautorizacion" },
         {data: "estado" },
         {data: "idemision" },
         {data: "idemision" },
         ],
         "rowCallback": function( row, detalle, index ){
             $('td', row).eq(5).prop('align','center');
      
            var estado;
            if(detalle['estado']=='INGRESADO'){
            	estado=`<span style="background-color:#007bff" class="badge badge-primary">${detalle['estado']}</span>`
        	}else if(detalle['estado']=='ENVIADO'){
            	estado=`<span style="background-color:#ffc107" class="badge badge-warning">${detalle['estado']}</span>`
        	}else if(detalle['estado']=='AUTORIZADO'){
            	estado=`<span style="background-color:#28a745" class="badge badge-success">${detalle['estado']}</span>`
        	}
         
            $('td', row).eq(5).html(estado);
            $('td', row).eq(5).css('text-align','center');
            $('td', row).eq(5).css('vertical-align','middle');
            if(detalle['estado']=='AUTORIZADO'){
	            $('td', row).eq(6).html(`<center><a style="font-size:30px" target="_blank" href="/comprobantes/pdfGeneradoComprobanteR/${detalle['claveAcceso']}"  >
	                                             <i style="color:red" class="fa fa-file-pdf-o" aria-hidden="true"></i>
	                                            </a>
	                                    </center>`);
	            $('td', row).eq(7).html(`<center>
	                                        <a style="font-size:30px" target="_blank" download href="/comprobantes/xmlGeneradoComprobanteR/${detalle['claveAcceso']}"  >
	                                                <i style="color:green" class="fa fa-file-code-o" aria-hidden="true"></i>
	                                            </a>
	                                    </center>`);
             }else{
          		$('td', row).eq(6).html(``);
          		$('td', row).eq(7).html(``);
          	}
              
         } 

    })
}


$('#cedulaRuc').on('input', function() {
   this.value = this.value.replace(/[^0-9]/g,'');
});


