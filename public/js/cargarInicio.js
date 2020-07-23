    //============================ ESTILOS PARA VENTANA DE CARGA ================================

    function vistacargando(estado){
        mostarOcultarVentanaCarga(estado,'');
    }

    function vistacargando(estado, mensaje){
        mostarOcultarVentanaCarga(estado, mensaje);
    }

    function mostarOcultarVentanaCarga(estado, mensaje){
        //estado --> M:mostrar, otra letra: Ocultamos la ventana
        // mensaje --> el texto que se carga al mostrar la ventana de carga
        if(estado=='M' || estado=='m'){
            $('#modal_cargando_title').html(mensaje);
            $('#modal_cargando').show();
        }else{
            $('#modal_cargando_title').html('Cargando');
            $('#modal_cargando').hide();
        }
    }

    // FUNCION PARA MOSTRAR UNA ALERTA DE PNotify PERSONALIZADA
    function alertNotificar(texto, tipo){
        PNotify.removeAll()
        new PNotify({
            title: 'Mensaje de Información',
            text: texto,
            type: tipo,
            hide: true,
            delay: 7000,
            styling: 'bootstrap3',
            addclass: ''
        });
    }


    //============================= PARA CARGAR LAS NOTIFICACIONES DE LAS INSPECCIONES NO ASIGNADAS ================================

    $(document).ready(function(){
   //     cargarNotificaciones();
    });

    function cargarNotificaciones(){
        $.get('/gestionAgenda/cargarNotificaciones', function(retorno){
            console.clear();
            $("#numero_notific").html("");
            $("#ul_notificaciones").html("");
            if(retorno.error==false){
                var numero_notific = retorno.lista_pendientes.length;
                if(numero_notific>0){
                    $("#numero_notific").html(`<span class="badge bg-red">${numero_notific}</span>`);
                }

                $.each(retorno.lista_pendientes, function(index, emision_certificado){
                    $("#ul_notificaciones").append(`
                        <li style="padding:10px 2px;">
                            <a>
                                <span class="image"><i class="fa fa-exclamation-triangle"></i></span>
                                <span>
                                    <span><b>INSPECCIÓN NO AGENDADA</b></span>
                                    <br><span class="time" style="position: inherit; color: #e74c3c;">${emision_certificado['fechaSolicitud']}</span>
                                </span>
                                <span class="message"><b>SOLICITUD: </b> ${emision_certificado['lista_certificados']['descripcion']}</span>
                                <hr style="margin: 4px 0px; border-top: 1px solid #d8d8d8;">
                                <span class="message"><b>CÉDULA: </b> ${emision_certificado['us001']['cedula']}</span>
                                <span class="message"><b>NOMBRE: </b> ${emision_certificado['us001']['name']}</span>
                            </a>
                        </li>     
                    `);
                });
            }
        });
    }

    // FUNCION PARA RETORNAR EL LENGUAJE DE LA TABLA DATETABLE
    function datatableLenguaje(data){
        var data = {
            "lengthMenu": 'Mostrar <select class="form-control input-sm">'+
                        '<option value="5">5</option>'+
                        '<option value="10">10</option>'+
                        '<option value="15">15</option>'+
                        '<option value="20">20</option>'+
                        '<option value="30">30</option>'+
                        '<option value="-1">Todos</option>'+
                        '</select> registros',
            "search": "Buscar:",
            "searchPlaceholder": data.placeholder,
            "zeroRecords": "No se encontraron registros coincidentes",
            "infoEmpty": "No hay registros para mostrar",
            "infoFiltered": " - filtrado de MAX registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
            }
        };
        return data;
    }