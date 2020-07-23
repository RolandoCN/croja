//



$('#frmBuscar').submit(function(){
  event.preventDefault();
       
       $('#buscar').html(`<button disabled=true  type="button"  class="btn btn-primary"><span class="spinner-border " role="status" aria-hidden="true"></span> Buscando</button>`);  

       var busqueda = $("#busqueda").val();
        if(busqueda === ''){
        //alert("Ingrese una descripción");
        $('#infoBusqueda').html('');
            $('#infoBusqueda').append(`<div style="background-color: #f8d7da;color: black" class="alert  alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>¡Atención!</strong> Ingrese el Código.
                      </div>`);
            $('#infoBusqueda').show(200);
            setTimeout(function() {
            $('#infoBusqueda').hide(200);
            },  3000);
          //return;
        $('#buscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);

        return false;
         }
         else{
       
        $.get(`/validacion/${busqueda}/codigo`, function(retorno){
         console.log(retorno);
          $('#tb_listaZona').html("");
      
          if(retorno.resultado==null){
            $('#j').addClass('hidden');
          $('#buscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);
          //$('#tabla_compatibilidad').html('');
          $('#infoBusqueda').html('');
            $('#infoBusqueda').append(`<div style="background-color: #f8d7da;color: black" class="alert  alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>¡Atención!</strong> Documento no encontrado.
                      </div>`);
            $('#infoBusqueda').show(200);
            setTimeout(function() {
            $('#infoBusqueda').hide(200);
            },  3000);
          return;
        }

          else{
            $('#j').removeClass('hidden');
            //$('#buscador').addClass('hidden');
            // console.log(retorno.datosPredio.pro_cedula);
           $('#tb_listaZona').append(
             `<tr>
                 
                  <td>${retorno.resultado.persona.nombres+" "+retorno.resultado.persona.apellidos} </td>
                  <td>${retorno.resultado.persona.grupo} </td>
                  <td>${retorno.resultado.persona.factor} </td>
                  <td>${retorno.resultado.persona.factor_du} </td>
                  <td>${retorno.resultado.fecha_recibida} </td>
                  
                   <td>
                   <a href="/validacion/reportecarnet/${retorno.codigo}"><button type="button" class="btn btn-sm btn-primary marginB0"><i class="fa fa-print"></i> Imprimir</button></a>
                   </td>
                   </tr>`);


}
        $('#buscar').html(`<button  type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>`);

});
}
});