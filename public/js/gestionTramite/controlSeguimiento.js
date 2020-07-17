
    var lenguajeTabla = {
        "lengthMenu": 'Mostrar <select class="form-control input-sm">'+
                    '<option value="5">5</option>'+
                    '<option value="10">10</option>'+
                    '<option value="20">20</option>'+
                    '<option value="30">30</option>'+
                    '<option value="40">40</option>'+
                    '<option value="-1">Todos</option>'+
                    '</select> registros',
        "search": "Buscar:",
        "searchPlaceholder": "Ingrese un criterio de busqueda",
        "zeroRecords": "No se encontraron registros coincidentes",
        "infoEmpty": "No hay registros para mostrar",
        "infoFiltered": " - filtrado de MAX registros",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "paginate": {
            "previous": "Anterior",
            "next": "Siguiente"
        }
    };

    $(document).ready(function(){
        var idtabla = "table_tramites";
        $(`#${idtabla}`).DataTable({
            dom: ""
            +"<'row' <'form-inline' <'col-sm-6 inputsearch'f>>>"
            +"<rt>"
            +"<'row'<'form-inline'"
            +" <'col-sm-6 col-md-6 col-lg-6'l>"
            +"<'col-sm-6 col-md-6 col-lg-6'p>>>",
            "destroy":true,
            order: [[ 2, "desc" ]],
            pageLength: 10,
            sInfoFiltered:false,
            "language": lenguajeTabla
        });

        // para posicionar el input del filtro
        $(`#${idtabla}_filter`).css('float', 'left');
        $(`#${idtabla}_filter`).children('label').css('width', '100%');
        $(`#${idtabla}_filter`).parent().css('padding-left','0');
        $(`#${idtabla}_wrapper`).css('margin-top','10px');
        $(`input[aria-controls="${idtabla}"]`).css('width', '100%');
        //buscamos las columnas que deceamos que sean las mas angostas
        $(`#${idtabla}`).find('.col_sm').css('width','10px');
    });


    // EVENTOS QUE SE DESENCADENAS AL CAMBIAR EL ESTADO DEL CHECK_FILTRAR_FECHA
    $('#check_filtrar_fecha').on('ifChecked', function(event){ // si se checkea
        $("#content_filtrar_fecha").show(200);
    });
    
    $('#check_filtrar_fecha').on('ifUnchecked', function(event){ // si se deschekea
        $("#content_filtrar_fecha").hide(200);
    });


    //FUNCION PARA FILTRAR LOS TRÁMITES
    function filtrarTramites(){

        var check_filtrar_fecha = false;
        if($("#check_filtrar_fecha").is(':checked')){
            check_filtrar_fecha = true;
        }

        var FrmData = {
            cmb_tipoTramite: $("#cmb_tipoTramite").val(),
            check_filtrar_fecha: check_filtrar_fecha,
            fechaInicio: $("#fechaInicio").val(),
            fechaFin: $("#fechaFin").val(),
            estado_tramite: $(".estado_tramite:checked").val()
        } 

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });  

        vistacargando("M", "Espere...");

        $.ajax({
			url: '/gestionTramite/filtrarTramite', 
			method: "POST", 
            data: FrmData,
            type: "json",             
			complete: function (request)   
			{
                vistacargando();
                var retorno = request.responseJSON;
                console.clear(); console.log(retorno);

                if(retorno.error == true){
                    mostrarMensaje(retorno.mensaje,retorno.status,'mensaje_info');
                }else{

                    //cargamos le resumen de la búsqueda
                    $("#r_totales").html(retorno.lista_tramites.length);
                    $("#r_pendientes").html(retorno.pendientes);
                    $("#r_finalizados").html(retorno.finalizados);
                    $("#content_resultado").show(); console.log();

                    var idtabla = "table_tramites";
                    $(`#${idtabla}`).DataTable({
                        dom: ""
                        +"<'row' <'form-inline' <'col-sm-6 inputsearch'f>>>"
                        +"<rt>"
                        +"<'row'<'form-inline'"
                        +" <'col-sm-6 col-md-6 col-lg-6'l>"
                        +"<'col-sm-6 col-md-6 col-lg-6'p>>>",
                        "destroy":true,
                        order: [[ 1, "asc" ]],
                        pageLength: 5,
                        sInfoFiltered:false,
                        "language": lenguajeTabla,
                        data: retorno.lista_tramites,
                        columnDefs: [
                            {  className: "col_sm", targets: 0 },
                            {  className: "sorting", targets: 1 },
                            {  className: "sorting col_sm", targets: 2 },
                            {  className: "sorting col_sm", targets: 3 }
                        ],
                        columns:[
                            {data: "tramite.fechaCreacion" },
                            {data: "tramite.index" },
                            {data: "tramite", render : function (tramite, type, row){ 
                                // para que busque por todos estos criterios
                                return `${tramite.cedula} ${tramite.proceso} ${tramite.asunto} ${tramite.descripcionTramite} ${retorno['listaAreaResp'][tramite.codigoTramite]}` 
                            }},
                            {data: "tramite.asunto" }
                        ],
                        "rowCallback": function( row, tramite, index ){
                            
                            //columna de fecha
                                $('td', row).eq(0).addClass('center_vertical user_selec');
                                $('td', row).eq(0).html(`
                                    <span class="label label-warning" style="padding: 5px 7px;">${tramite['tramite']['fechaCreacion'].substr(0,10)}</span>
                                `);

                            //columna informacion general del trámite
                                var area_responsable = retorno['listaAreaResp'][tramite['tramite']['codigoTramite']];
                                if(area_responsable==null) {area_responsable=""; }

                                $('td', row).eq(1).addClass('center_vertical td_info_tramite');
                                $('td', row).eq(1).html(`                    
                                    <div style="min-width:300px;"></div>
                                    <span class="cedula_contrib"><b><i class="fa fa-user"></i> Contribuyente:</b> ${tramite['tramite']['cedula']}<br></span>
                                    <b><i class="fa fa-tags"></i> Código:</b> ${tramite['tramite']['proceso']}<br>
                                    <b><i class="fa fa-exclamation-circle"></i> Asunto:</b> ${tramite['tramite']['asunto']}<br>                
                                    <b><i class="fa fa-file-text"></i> Tipo Trámite:</b> <span style="color: #337ab7; font-weight: 800;">${tramite['tramite']['descripcionTramite']}</span><br>
                                    <b><i class="fa fa-users"></i> Área Responsable:</b> ${area_responsable}
                                `);

                            //columna estado del trámite
                                var estado_tramite = "EN PROCESO";
                                var color_dias = "danger";
                                var mensaje_dias = "Han transcurrido";
                                if(tramite['tramite']['finalizado']==1){ 
                                    estado_tramite = "FINALIZADO"; 
                                    color_dias="success"; 
                                    mensaje_dias="Tardó un total de"
                                }
                                $('td', row).eq(2).addClass('center_vertical user_selec');
                                $('td', row).eq(2).html(`
                                    <b style="text-align: center; font-weight: 800; display: block;">${estado_tramite}</b>
                                    <span class="label2 label2-${color_dias}">${mensaje_dias} <br> ${tramite['dias_trans']} días</span>
                                `);

                            //columna de botones de acción
                                $('td', row).eq(3).addClass('center_vertical');
                                $('td', row).eq(3).html(`
                                    <button type="button" class="btn btn-info btn-sm btn-block" onclick="cargarDetalleTramite('${tramite['tramite']['proceso']}', '${tramite['tramite']['cedula']}', this)" data-toggle="tooltip" data-placement="left" title="" data-original-title="Ver información del ciudadno y el historial de recorriodo de departamentos del trámite"> 
                                        <i class="fa fa-exclamation-circle"></i> Ver detalle
                                    </button>
                                    <button type="button" onclick="imprimirCodigo('${tramite['tramite']['proceso']}','${tramite['tramite']['cedula']}', this)" class="btn btn-success btn-sm btn-block" data-toggle="tooltip" data-placement="left" title="" data-original-title="Imprimir el código de recepción de documentos"> 
                                        <i class="fa fa-download"></i> Imprimir
                                    </button>
                                    <span class="obs_tramite" style="display:none"> ${tramite['tramite']['observacion']} </span>
                                `);        
                                $('td', row).eq(3).find("button").tooltip();
    
                        }                                
                    }); 

                    // para posicionar el input del filtro
                    $(`#${idtabla}_filter`).css('float', 'left');
                    $(`#${idtabla}_filter`).children('label').css('width', '100%');
                    $(`#${idtabla}_filter`).parent().css('padding-left','0');
                    $(`#${idtabla}_wrapper`).css('margin-top','10px');
                    $(`input[aria-controls="${idtabla}"]`).css('width', '100%');
                    //buscamos las columnas que deceamos que sean las mas angostas
                    $(`#${idtabla}`).find('.col_sm').css('width','10px');
                    // $(`#${idtabla}`).find('.col_lg').css('width','300px');
                    
                }
                
			},error: function(){
                mostrarMensaje('Error al realizar la solicitud, Inténtelo más tarde','danger','mensaje_info');
                vistacargando();
            }
	    });

    }

    //IMPRIME UN PDF CON UN DODIGO DE BARRA (se hace asi porque en las etiquetas a no targan lo tooltip text)
    function imprimirCodigo(codigoRastreo, cedula, btn){
        $(btn).tooltip('hide');
        window.location.href=`/gestionIniciarTramite/imprimir/${codigoRastreo}/${cedula}`;
    }


    //FUNCIÓN PARA CARGAR LOS DETALLES DE UN TRÁMITE
    function cargarDetalleTramite(codigoTramite, cedula, btn){

        $(btn).tooltip('hide');
        var observacion = $(btn).siblings('.obs_tramite').html();
        $('#a_historial').click();

        //reiniciamos los datos del contribuyente

        vistacargando("M", "Espere..");
        
        $.get(`/gestionTramite/getDetalleTramite/${codigoTramite}/${cedula}`, function(retorno){
            vistacargando();
            if(retorno.error==true){
                mostrarMensaje(retorno['mensaje'],retorno['status'],'mensaje_info');
            }else{
                //cargamos mos la informacion del contribuyente
                
                    $("#informacion_tramite").html($(btn).parent().siblings('.td_info_tramite').html());
                    $("#informacion_tramite").find('.cedula_contrib').remove();
                    $("#info_observacion").html(observacion);

                    $("#codigo_tramite").html('DETALLE DEL TRÁMITE || '+codigoTramite);
                    if(retorno['contribuyente']!=null){
                        $("#info_nombre").html(retorno['contribuyente']['name']);
                        $("#info_cedula").html(retorno['contribuyente']['cedula']);
                        $("#info_direccion").html(retorno['contribuyente']['direccion']);
                        $("#info_email").html(retorno['contribuyente']['email']);
                        $("#info_celular").html(retorno['contribuyente']['celular']);
                        $("#info_telefono").html(retorno['contribuyente']['telefono']);
                    }else if(retorno['datos_dinardap'].length>0){
                        $("#info_cont_noregist").html('<b><i class="fa fa-exclamation-triangle"></i> Nota: <span> El contribuyente no está registrado en el sistema de <i class="fa fa-angle-double-left"></i> Servicios en línea <i class="fa fa-angle-double-right"></i></b></span><br>');
                        $("#info_nombre").html(retorno['datos_dinardap'][9]["valor"]);
                        $("#info_cedula").html(retorno['datos_dinardap'][0]["valor"]);
                        $("#info_direccion").html(" --");
                        $("#info_email").html(" --");
                        $("#info_celular").html(" --");
                        $("#info_telefono").html(" --");
                    }


                //cargamos el historial del trámite
                    cadena ="";
                    msg=retorno['detalle_tramite'];
                    $("#datosTabla").html("");

                    for( i = 0; i < msg.length; i++){
            
                        if(msg[i].estado == 'ATENDIDO' || msg[i].estado == 'FINALIZADO' ){
                        cadena = cadena + "<tr class='success'>";
                        }else{
                        cadena = cadena + "<tr class='danger'>";
                        }
                        
                        if (msg[i].fechaApr !== null && msg[i].fechaApr !== undefined) {
                        cadena = cadena + "<td>"+msg[i].fechaApr+"</td>";
                        }else{
                        cadena = cadena + "<td><center> - </center></td>";
                        }
            
                        if (msg[i].fechaAtiende !== null && msg[i].fechaAtiende !== undefined) {
                        cadena = cadena + "<td>"+msg[i].fechaAtiende+"</td>";
                        }else{
                        cadena = cadena + "<td> PENDIENTE </td>";
                        }
                        
                        cadena = cadena + "<td>"+msg[i].origen+"</td>";
                        cadena = cadena + "<td>"+msg[i].destino+"</td>";
                        cadena = cadena + "<td>"+msg[i].asunto+"</td>";
            
                        if (msg[i].final !== null && msg[i].final !== undefined) {
                        cadena = cadena + "<td>"+msg[i].final+"</td>";
                        }else{
                        cadena = cadena + "<td><center> - </center></td>";
                        }
                    
                        cadena = cadena + "<td>"+msg[i].estado+"</td>";
                        cadena = cadena  + "</tr>";
                    
                    }


                    //CARGAMOS LOS DOCUMENTOS SUBIDOS SI EL TRÁMITE ESTA FINALIZADO
                        $("#tbody_documentos").html(`<tr><td colspan="6"> <center>No hay documentos </center> </td></tr>`);
                        $("#alerta_sindocumentos").show();

                        if(retorno.tramite_ciudadano != null){
                            if(retorno.tramite_ciudadano.carga_documentos.length>0){

                            $("#tbody_documentos").html('');
                            $("#alerta_sindocumentos").hide();

                            $.each(retorno.tramite_ciudadano.carga_documentos, function(cd, documento){

                                var vigencia = `<span class="label label-danger estado_vigencia"><i class="fa fa-check-circle"></i> No Vigente</span>`;
                                var fecha_vigencia = "--";
                                if(documento.vigencia =="Si" || documento.vigencia=="SI" || documento.vigencia=="si"){
                                vigencia = `<span class="label label-success estado_vigencia"><i class="fa fa-check-circle"></i> Vigente</span>`;
                                fecha_vigencia = documento.fecha_vigencia;
                                }

                                $("#tbody_documentos").append(`
                                <tr>
                                    <td style="vertical-align: middle;">${documento.tipodocumentotramite.descripcion}</td>
                                    <td style="vertical-align: middle;">${documento.descripcion}</td>
                                    <td style="vertical-align: middle;">${documento.fecha_carga}</td>
                                    <td style="vertical-align: middle;"><center>${vigencia}</center></td>
                                    <td style="vertical-align: middle;">${fecha_vigencia}</td>
                                    <td style="vertical-align: middle;"><a target="_blank" href="/buscarDocumento/diskDocumentosTramitesFinalizados/${documento.documento}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Ver Documento</a></td>
                                </tr>
                                `);
                            });

                            }
                        }

                    //---------------------------------------------------------------

                
                    $("#datosTabla").html(cadena); 

                    if(msg.length==0){
                        $("#datosTabla").html(`<tr><td colspan="7"><center>Sin resultados</center></td></tr>`); 
                    }

                //mostramos la vista de detalle de trámite
                    $("#content_contSeg").hide(300);
                    $("#content_historial").show(300);
            }
            
        }).fail(function(){
            mostrarMensaje('Error al obtener el detalle del trámite, Inténtelo más tarde','danger','mensaje_info');
            vistacargando();
        });
    }

    //FUNCIÓN PARA SALIR DEL A VISTA DE DETALLE TRÁMITE
    function salirDetalleTramite(){       
        $("#content_historial").hide(200);
        $("#content_contSeg").show(200);
        $(".info_cont").html(" --");
        $("#info_cont_noregist").html("");
        $("#datosTabla").html(`<tr><td colspan="7"><center>Sin resultados</center></td></tr>`); 
    }


    //FUNCION PARA AGREGAR UN MENSAJE EN LA PANTALLA
    function mostrarMensaje(mensaje, status, idelement){
        var contenidoMensaje=`
            <div style="font-weight: 700;" class="alert alert-${status} alert-dismissible alert_sm" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>MENSAJE! </strong> <span> ${mensaje}</span>
            </div>
        `;
        $("#"+idelement).html(contenidoMensaje);
        $("#"+idelement).show(500);
        
        setTimeout(() => {
            $("#"+idelement).hide(500);
        }, 8000);
    }


    // función que se desencade al cambiar un combo de los tipo de trámite 
    function seleccionarCombo(cmb){
        var option_sel= $(cmb).find('option:selected');
        var valor_sel=$(option_sel).attr('data-id'); console.log(valor_sel);
        if(valor_sel==0){
            $(option_sel).prop('selected', false);
            $(cmb).find('.option').prop('selected', true);
            $(cmb).trigger("chosen:updated");
        }
    }