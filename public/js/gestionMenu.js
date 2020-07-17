
// evento para los botones de seleccionar icono

$('.buttonIconoSeleccionado').click(function(){
    $('#modalSeleccionarIcono').modal('show');
    // obtenemos y guardamos el id del boton que llama la ventana modal de iconos
    $('#idButtonSeleccionado').val($(this)[0].id);
    $('#idInputSeleccionado').val($(this).siblings('input')[0].id);
});

$('#contenedor_iconos')
.children('section')
.children('div')
.children('div')
.children('a')
.click(function(){
    var icono = $(this).children('i').prop('class');
    //$('#idButtonSeleccionado').ht
    $(`#${$('#idButtonSeleccionado').val()}`).html(`<i class="${icono}"></i>`);
    $(`#${$('#idInputSeleccionado').val()}`).val(icono); // asignamos el nombre del icono seleccioando
    $('#modalSeleccionarIcono').modal('hide');
});


$('.buscarIcono').keyup(function(e){
    var buscar=$(this).val();
    $('#contenedor_iconos section').each(function (s, section) {
        var totalIconos=0;
        var iconosOcultos=0;
        $(section).children('div').each(function (d1, div1) {
            $(div1).children('div').each(function (d2, div2) {
                textoicono=$(div2).children('a').html();
                if(textoicono.indexOf(buscar)>-1){
                    $(div2).show();
                }else{
                    $(div2).hide();
                    iconosOcultos++;
                }
                totalIconos++;
            });
        });
        if(iconosOcultos==totalIconos){
            $(section).hide();
        }else{
            $(section).show();
        }
    });
});


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// GESTION DE RUTA

function menu_editar(idmenu){
    vistacargando('M','Espere...'); // mostramos la ventana de espera
    $.get("/gestionAccesos/menu/"+idmenu+"/edit", function (data) {
        console.log(data);
        $('#descripcion').val(data.resultado.descripcion);
        $('#ruta').val(data.resultado.ruta);
        $('#icon_menu').val(data.resultado.icono);
        $('#icon_menu_btn').html(`<i class="${data.resultado.icono}"></i>`);

        vistacargando(); // oculatamos la vista de carga
    }).fail(function(){
        // si ocurre un error
        vistacargando(); // ocultamos la vista de carga
        alert('Se produjo un error al realizar la petición. Comunique el problema al departamento de tecnología');
    });

    $('#method_Menu').val('PUT'); // decimo que sea un metodo put
    $('#frm_Menu').prop('action',window.location.protocol+'//'+window.location.host+'/gestionAccesos/menu/'+idmenu);
    $('#btn_menu_cancelar').removeClass('hidden');

    $('html,body').animate({scrollTop:$('#administradorMenu').offset().top},400);
}

$('#btn_menu_cancelar').click(function(){
    $('#descripcion').val('');
    $('#ruta').val('');
    $('#icon_menu').val('fa fa-circle-o');
    $('#icon_menu_btn').html(`<i class="fa fa-circle-o"></i>`);

    $('#method_Menu').val('POST'); // decimo que sea un metodo put
    $('#frm_Menu').prop('action',window.location.protocol+'//'+window.location.host+'/gestionAccesos/menu/');
    $(this).addClass('hidden');

});

//ELIMINAR RUTA
function btn_eliminar_menu(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}
