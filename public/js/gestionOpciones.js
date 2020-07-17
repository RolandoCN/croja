

// ============================= GESTIONES DE OPCIONES =================================
//FUNCIONES PARA EDITAR LOS REGISTROS 
// Gestion OPCIONES
function  opciones_editar(id){
    $.get("/gestionAccesos/opciones/"+id+"/edit", function (data) {
        console.log(data);
        $('#frm_Opciones').removeClass('hidden');
        $('.option_tipoUsuario').prop('selected',false); // deseleccioamos todas las zonas del combo
        $(`#cmb_tipousuario option[value="${data.idtipo_usuario}"]`).prop('selected',true); // seleccionamos la zona que pertenece el sector seleccionado 
        $("#cmb_tipousuario").trigger("chosen:updated"); 
        

        
        $('.option_ruta').prop("selected", false);
        $.each(data.resultado, function(index, gestion){            
            $(`#cmb_ruta option[value="${gestion.idruta}"]`).prop("selected", true);  
            $("#cmb_ruta").trigger("chosen:updated");          
        }); 
       
        
    });

    $('#method_Opciones').val('PUT'); 
    $('#frm_Opciones').prop('action',window.location.protocol+'//'+window.location.host+'/gestionAccesos/opciones/'+id);
    $('#btn_opciones_cancelar').removeClass('hidden');

    $('html,body').animate({scrollTop:$('#administradorOpciones').offset().top},400);
}

// la funcion del boton cancelar, vacia los campos y el mismo se vuelve a desaparecer
$('#btn_opciones_cancelar').click(function(){
    $('#cmb_tipousuario').val('');
    $('.option_tipoUsuario').prop('selected',false); // deseleccionamos las zonas seleccionadas
    $("#cmb_tipousuario").trigger("chosen:updated"); // actualizamos el combo de zonas
    $('#cmb_ruta').val('');
    $('.option_ruta').prop('selected',false); // deseleccionamos las zonas seleccionadas
    $("#cmb_ruta").trigger("chosen:updated"); // actualizamos el combo de zonas    
    $('#method_Opciones').val('POST'); 
    $('#frm_Opciones').prop('action',window.location.protocol+'//'+window.location.host+'gestionAccesos/opciones/');
    $(this).addClass('hidden');
    $('#frm_Opciones').addClass('hidden');
});

//ELIMINAR CERTIFICADO
function btn_eliminar(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}


//Gestion Requisitos con el tipo de funcionario


