$(document).ready(function(){
     var sms=$('#sms').val();
            var persona=$('#persona').val();
            if(sms=="success" && persona!="")
            {
                //alert("guardado");
            
            $(location).attr('href','/gestionServicios/reportecarnet/'+persona);

            vistacargando("M", "Espere...");  
             setTimeout(() => {
              vistacargando();
            }, 40000);    
           


            }

    
});

function btn_eliminar(btn){
    if(confirm('¿Quiere eliminar el registro?')){
        $(btn).parent('.frm_eliminar').submit();
    }
}


//Gestion Requisitos con el tipo de funcionario


