//ENVIAR PLAN CONTRATACION
    $("#frm_Atención").on("submit", function(e){
        e.preventDefault();
        
        if($('#method_Detalle').val()=='POST')
        {
          console.log('Guardar');
          guardarAtencion();
          
        }
        else{
          console.log('editar');
          editarAtencion();
          $('#btn_medio_cancelar').addClass('hidden');

        }
    });



 function guardarAtencion() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       
        
        var data=$("#frm_Atención").serialize();
        $.ajax({
            url:'/gestionServicios/servicio', // Url que se envia para la solicitud
            method: 'POST',              // Tipo de solicitud que se enviará, llamado como método
            data: data,               // Datos enviados al servidor, un conjunto de pares clave / valor (es decir, campos de formulario y valores)
            dataType: 'json',
            success: function(requestData)   // Una función a ser llamada si la solicitud tiene éxito
            {
                console.log(requestData);
                //console.log(requestData);
                $('#msmMedioV').html(`
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre_menu"></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="alert alert-${requestData.estadoP} alert-dismissible fade in" id="idalert" role="alert" style="margin-bottom: 0;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Información: </strong> ${requestData.mensajePInfoAtención}
                            </div>
                        </div>
                    `);

                     limpiarCampos();
                     $(location).attr('href','/gestionServicios/reportecarnet/'+requestData.id);

                     vistacargando("M", "Espere...");  
                     setTimeout(() => {
                      vistacargando();
                    }, 40000);    

                    cargartabla();

               }, error:function (requestData) {
                console.log(requestData);
            }
            });
       

    }

function limpiarCampos()
{
    $('#recibida').val('');
    $('#total').val('');
    $('#interes').val('');
    $('#descuento').val('');

    $('.option_persona').prop('selected',false); 
    $("#cmb_persona").trigger("chosen:updated"); 

    $('.option_servicio').prop('selected',false); 
    $("#cmb_servicio").trigger("chosen:updated"); 

}

 //obtener data para llenara lista de plan de contratacion
    function cargartabla() {
        var id=2;
         $.get("/gestionServicios/servicio/"+id+"/edit", function (data) {

           var tabla = $('#datatable').DataTable({
         dom: ""
                +"<'row' <'form-inline' <'col-sm-12 inputsearch'f>>>"
                +"<rt>"
                +"<'row'<'form-inline'"
                +" <'col-sm-6 col-md-6 col-lg-6'l>"
                +"<'col-sm-6 col-md-6 col-lg-6'p>>>",
                "destroy":true,
                pageLength: 10,
                sInfoFiltered:false,
               language: datatableLenguaje(datatable),
         data: data,
          columns:[
               {"render":
                     function ( data, type, row ) {
                    return (row.persona.nombres + ' ' + row.persona.apellidos);
                        }
                     },
               {data: "fecha_recibida" },

               {data: "servicio.detalle_servicio" },
               {data: "subtotal" },
               {data: "subtotal" },
               {data: "interes" },
               {data: "descuento" },
               {data: "valor_total" },
               {data: "valor_total" },
           ],
            "rowCallback": function( row, data, index ){
                var set=[''];
                   var hr='';
       
            $.each(data.servicio.detalle,function(i,item){
                console.log(item)

                          if(i>=0){hr=`<li>`;}
                          set[i]= ` ${hr} ${item.detalle} `;
                    });
              $('td', row).eq(3).html(set);
              $('td', row).eq(8).html(`<button type="button" class="btn btn-sm btn-danger marginB0" onclick="eliminar('${data.idemision_encrypt}')"><i class="fa fa-trash"></i> Eliminar</button>`);
             }
         });
       });
    }

     $(document).ready(function () {
     cargartabla();
             
     });

//eliminar Emision
  function eliminar(id) {
    console.log(id);
    if(confirm('¿Quiere eliminar el registro?')){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
          url:'/gestionServicios/servicio/'+id, // Url que se envia para la solicitud
          method: 'DELETE',              // Tipo de solicitud que se enviará, llamado como método
          dataType: 'json',
          success: function(requestData)   // Una función a ser llamada si la solicitud tiene éxito
          {
            console.log(requestData);
            $('#msmMedioV').html(`
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre_menu"></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="alert alert-${requestData.estadoP} alert-dismissible fade in" id="idalert" role="alert" style="margin-bottom: 0;">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <strong>Información: </strong> ${requestData.mensajePInfoAtención}
                  </div>
              </div>
              `);
            cargartabla();
            limpiarCampos();
          }, error:function (requestData) {
            console.log('Error no se puedo completar la acción');
          }
      });
      }

   limpiarCampos();

  }


