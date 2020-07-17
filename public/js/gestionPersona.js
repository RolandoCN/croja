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
function persona_editar(idpersona){
    $.get("/gestionPersona/registro/"+idpersona+"/edit", function (data) {
        console.log(data);
        $('#nombres').val(data.resultado.nombres);
        $('#apellidos').val(data.resultado.apellidos);
        $('#fecha_nacimiento').val(data.resultado.fecha_nacimiento);
        $('#direccion').val(data.resultado.direccion);
        $('.option_canton').prop('selected',false); // deseleccioamos todas las zonas del combo
        $(`#cmb_canton option[value="${data.resultado['idcanton']}"]`).prop('selected',true); // seleccionamos la zona que pertenece el sector seleccionado 
        $("#cmb_canton").trigger("chosen:updated"); 
        $('#correo').val(data.resultado.email);
        $('#factor').val(data.resultado.factor);
        $('#factor_du').val(data.resultado.factor_du);
        $('#grupo').val(data.resultado.grupo);
        $('#direccion').val(data.resultado.factor_du);
        
        $('#clave').val(data.resultado.codClave);

        if(data.resultado.sexo=="Masculino")
        {
           $('#check_masculino').iCheck('check');
        }

         if(data.resultado.sexo=="Femenino")
        {
           $('#check_femenino').iCheck('check');
        }
       
        
    });

    $('#method_Persona').val('PUT'); 
    $('#frm_Persona').prop('action',window.location.protocol+'//'+window.location.host+'/gestionPersona/registro/'+idpersona);
    $('#btn_personacancelar').removeClass('hidden');

    $('html,body').animate({scrollTop:$('#administradorPersona').offset().top},400);
}

// la funcion del boton cancelar, vacia los campos y el mismo se vuelve a desaparecer
$('#btn_personacancelar').click(function(){
    $('#nombres').val('');
    $('.option_canton').prop('selected',false); // deseleccionamos las zonas seleccionadas
    $("#cmb_canton").trigger("chosen:updated"); // actualizamos el combo de zonas
    $('#apellidos').val('');
    $('#fecha_nacimiento').val('');
    $('#direccion').val('');
    $('#correo').val('');    
    $('#factor').val('');
    $('#factor_du').val('');
    $('#grupo').val('');
    $('#direccion').val('');
    $('#method_Persona').val('POST'); 
    $('#frm_Persona').prop('action',window.location.protocol+'//'+window.location.host+'/gestionResidencia/canton/');
    $(this).addClass('hidden');
});

//ELIMINAR CERTIFICADO
function btn_eliminar(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}


//////////////////////////////

        $('#check_femenino').on('ifChecked', function(event){
            
             $('#check_masculino').iCheck('uncheck');
                          
         
        });


         $('#check_femenino').on('ifUnchecked', function(event){
            $('#check_femenino').iCheck('uncheck');
            $('#check_masculino').iCheck('check');
            
           
        });


         $('#check_masculino').on('ifChecked', function(event){
            
             $('#check_femenino').iCheck('uncheck');
                          
         
        });

          $('#check_masculino').on('ifUnchecked', function(event){
            $('#check_masculino').iCheck('uncheck');
            $('#check_femenino').iCheck('check');
            
           
        });