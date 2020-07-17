$(document).ready(function(){
    cargarContenidoTablas('datatablebuttons');
    cargarContenidoTablas('datatablecheckbox');
    cargarContenidoTablas('datatablefixedheader');
    
});
function cargarContenidoTablas(tabla) {
    $(`#${tabla}`).DataTable( {
        "language": {
            "lengthMenu": 'Mostrar <select class="form-control input-sm">'+
                        '<option value="5">5</option>'+
                        '<option value="10">10</option>'+
                        '<option value="20">20</option>'+
                        '<option value="30">30</option>'+
                        '<option value="40">40</option>'+
                        '<option value="-1">Todos</option>'+
                        '</select> registros',
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros coincidentes",
            "infoEmpty": "No hay registros para mostrar",
            "infoFiltered": " - filtrado de MAX registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
        }
    }
} );
}



// ============================= GESTIONES DE PARROQUIAS =================================
//FUNCIONES PARA EDITAR LOS REGISTROS 
// Gestion parroquia
function tipoUsuario_editar(idtipoUsuario){
    $.get("/gestionUsuario/tipo/"+idtipoUsuario+"/edit", function (data) {
        console.log(data);
        $('#detalle').val(data.resultado.detalle);
        $('#clave').val(data.resultado.codClave);
       
        
    });

    $('#method_TipoUsuario').val('PUT'); 
    $('#frm_TipoUsuario').prop('action',window.location.protocol+'//'+window.location.host+'/gestionUsuario/tipo/'+idtipoUsuario);
    $('#btn_tipoUsuario_cancelar').removeClass('hidden');

    $('html,body').animate({scrollTop:$('#administradorTipoUsuario').offset().top},400);
}

// la funcion del boton cancelar, vacia los campos y el mismo se vuelve a desaparecer
$('#btn_tipoUsuario_cancelar').click(function(){
    $('#detalle').val('');
       
    $('#method_TipoUsuario').val('POST'); 
    $('#frm_TipoUsuario').prop('action',window.location.protocol+'//'+window.location.host+'/gestionUsuario/tipo/');
    $(this).addClass('hidden');
});

//ELIMINAR CERTIFICADO
function btn_eliminar(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}


//Gestion Requisitos con el tipo de funcionario


