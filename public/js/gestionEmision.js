


// ============================= GESTIONES DE EMISIONES =================================
//FUNCIONES PARA EDITAR LOS REGISTROS 

function canton_editar(idcanton){
    $.get("/gestionResidencia/canton/"+idcanton+"/edit", function (data) {
        console.log(data);
        $('#canton').val(data.resultado.detalle);
         $('.option_canton_provincia').prop('selected',false); // deseleccioamos todas las zonas del combo
        $(`#cmb_canton_provincia option[value="${data.resultado['idprovincia']}"]`).prop('selected',true); // seleccionamos la zona que pertenece el sector seleccionado 
        $("#cmb_canton_provincia").trigger("chosen:updated"); 
        
         $('#clave').val(data.resultado.codClave);
       
        
    });

    $('#method_Canton').val('PUT'); 
    $('#frm_Canton').prop('action',window.location.protocol+'//'+window.location.host+'/gestionResidencia/canton/'+idcanton);
    $('#btn_canton_provincia_cancelar').removeClass('hidden');

    $('html,body').animate({scrollTop:$('#administradorCanton').offset().top},400);
}

// la funcion del boton cancelar, vacia los campos y el mismo se vuelve a desaparecer
$('#btn_canton_provincia_cancelar').click(function(){
    $('#canton').val('');
    $('.option_canton_provincia').prop('selected',false); // deseleccionamos las zonas seleccionadas
    $("#cmb_canton_provincia").trigger("chosen:updated"); // actualizamos el combo de zonas
    $('#clave').val('');    
    $('#method_Canton').val('POST'); 
    $('#frm_Canton').prop('action',window.location.protocol+'//'+window.location.host+'/gestionResidencia/canton/');
    $(this).addClass('hidden');
});

//ELIMINAR CERTIFICADO
function btn_eliminar(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}


//Gestion Requisitos con el tipo de funcionario


// show modal meta
    var con=1;
    $('#btnmetaadd').click(function(){
        con=con+1;
        $('#listMeta').append(`
            <div class="itemMeta text-ligth" style="margin-bottom:8px;">
             <span class=" right btn btn-xs fa fa-minus bg-danger right"onclick="eliminarMeta(this)" style="">  </span>
             <textarea class="form-control has-feedback-right" name="meta[null${con}new]" placeholder="Descripción de la meta"value="" required></textarea>
            </div>
        `);
    });


    //quitar meta de la lista
    function eliminarMeta(btn) {
         $(btn).parents('.itemMeta').remove();
         con=con-1;
    }