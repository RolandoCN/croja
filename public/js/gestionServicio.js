

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// GESTION DE SERVICIO

function servicio_editar(idservicio){
  
    $.get("/gestionServicios/registro/"+idservicio+"/edit", function (data) {
        console.log(data);
        $('#detalle').val(data.resultado.detalle_servicio);
        
     

    $('#method_Servicio').val('PUT'); // decimo que sea un metodo put
    $('#frm_Servicio').prop('action',window.location.protocol+'//'+window.location.host+'/gestionServicios/registro/'+idservicio);
    $('#btn_servicio_cancelar').removeClass('hidden');

      });

    $('html,body').animate({scrollTop:$('#administradorServicio').offset().top},400);
}

$('#btn_servicio_cancelar').click(function(){
    $('#detalle').val('');
   
    $('#method_Servicio').val('POST'); // decimo que sea un metodo put
    $('#frm_Servicio').prop('action',window.location.protocol+'//'+window.location.host+'/gestionServicios/registro/');
    $(this).addClass('hidden');

});

//ELIMINAR RUTA
function btn_eliminar(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}
