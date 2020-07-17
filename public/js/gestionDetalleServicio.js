


// ============================= GESTIONES DE DETALLE SERVICIOS =================================
//FUNCIONES PARA EDITAR LOS REGISTROS 
// Gestion servicios
function detalle_editar(iddetalle){
    $.get("/gestionServicios/detalle/"+iddetalle+"/edit", function (data) {
        console.log(data);
        $('#detalle').val(data.resultado.detalle);
        $('.option_servicio').prop('selected',false); // deseleccioamos todas las zonas del combo
        $(`#cmb_servicio option[value="${data.resultado['idservicio']}"]`).prop('selected',true); // seleccionamos la zona que pertenece el sector seleccionado 
        $("#cmb_servicio").trigger("chosen:updated"); 
        
                
    });

    $('#method_Detalle').val('PUT'); 
    $('#frm_Detalle').prop('action',window.location.protocol+'//'+window.location.host+'/gestionServicios/detalle/'+iddetalle);
    $('#btn_detalle_cancelar').removeClass('hidden');

    $('html,body').animate({scrollTop:$('#administradorDetalle').offset().top},400);
}

// la funcion del boton cancelar, vacia los campos y el mismo se vuelve a desaparecer
$('#btn_detalle_cancelar').click(function(){
    $('#detalle').val('');
    $('.option_servicio').prop('selected',false); // deseleccionamos las zonas seleccionadas
    $("#cmb_servicio").trigger("chosen:updated"); // actualizamos el combo de zonas
    
    $('#method_Detalle').val('POST'); 
    $('#frm_Detalle').prop('action',window.location.protocol+'//'+window.location.host+'/gestionServicios/detalle/');
    $(this).addClass('hidden');
});

//ELIMINAR CERTIFICADO
function btn_eliminar(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}


//Gestion Requisitos con el tipo de funcionario


