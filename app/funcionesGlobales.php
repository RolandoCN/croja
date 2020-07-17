<?php

    //origen de servidores de api
    function getHostServe($server){
        if($server==1){ // servidor de base de datos oracle
            return 'https://wsagua.chone.gob.ec';
            // return 'http://localhost/appServices/public';
        }else if($server==2){ // servidor de base de datos ????
            return '';
        }
    }

    //ruta donde guardar los documentos
    function getRutaDucumento(){
        return "C:/xampp/htdocs/appOnlineFP/public/certificadosDoc/";
        //return "ftp://localhost/appOnlineFP/public/$carpeta";
    }

    // para permitir los formatos de archivo a subir
    function permitirFormato($extension){
        $retorno=false;
        $arrFormatos=array('pdf','PDF','sql','txt');
        foreach ($arrFormatos as $value) {
            if($extension==$value): $retorno=true; continue; endif;
        }
        return $retorno;
    }

    function verificarCedulaRuc($pcedulaRuc){
        // esta funcion si esta registado el ruc o cedula retorna el id del usuario
        // si no es el caso retorna true si esta correcta la cedula o ruc
        // si no lo esta retorna falso
        if($pcedulaRuc==""){return 'I';}

        // primero verificamos que la cedula no exista en los registros
        $cedula = substr($pcedulaRuc, 0,10);
        $existe=App\User::where('cedula','like',"%$cedula%")->first();
        if(!is_null($existe)){
            return $existe->idus001;
        }

        // si no esta registrado procedemos a validar la cedula y el RUC
        // I:invalido
        // V:valido

        $estadoValidado='I';
        if(validarCedula($pcedulaRuc)){
            $estadoValidado='V';
        }

        if(validarRucPersonaNatural($pcedulaRuc)){
            $estadoValidado='V';
        }

        if(validarRucSociedadPrivada($pcedulaRuc)){
            $estadoValidado='V';
        }

        if(validarRucSociedadPublica($pcedulaRuc)){
            $estadoValidado='V';
        }
        return $estadoValidado;

    }


    // para verificar si un request tiene caracteres epeciales
    // retorna verdadero si almenos uno tiene CE
    function tieneCaracterEspecialRequest($request){
        foreach ($request as $key => $parametro) {
            if($key=='_token'):continue;endif; // para no validar el token de laravel
            $resultado=tieneCaracterEspecial($parametro);
            if($resultado==true){
                return true;
            } // si es 1 es porque se han encontrado CE
        }
        return false;
    }

    // para verificar si un campo tiene caracteres epeciales
    // retorna verdadero si  tiene CE   $resultado=preg_match("/[$%&|\/\<>#&=?¿'`*!¡\[\]{}()".'"'."]/",$texto);
    function tieneCaracterEspecial($texto){
        // return false;
        $resultado=preg_match("/[$%&|<>#&*!¡\[\]{}()\"]/",$texto);
        if($resultado==1){
            return true;
        }else{
            return false;
        } // si es 1 es porque se han encontrado CE
    }


    // para validar que la clave sea segura
    function validarClave($clave){
        $resultado=preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/',$clave);
        if($resultado==1):return true; else: return false; endif;
    }

    function tipoUsuario($usid=0){

        if(!auth()->guest()){
            if($usid==0){
                $usid=auth()->user()->idus001;
            }
            $tipousuario=DB::table('us001')
                    ->join('us001_tipoUsuario','us001_tipoUsuario.idus001','=','us001.idus001')
                    ->join('tipoUsuario','tipoUsuario.idtipoUsuario','=','us001_tipoUsuario.idtipoUsuario')
                    ->where('us001.idus001',$usid)
                    ->select('tipoUsuario.*')
                    ->get();

            return response()->json($tipousuario);
        }else{
             return null;
        }
    }

    function userEsTipo($ptipo=''){
        $retorno=false;
        if(!auth()->guest()){
            $tipos_usuario = tipoUsuario(auth()->user()->idus001);
            if(!is_null($tipos_usuario)){
                foreach ($tipos_usuario->original as $key => $item_tipo) {
                    if($item_tipo->tipo==$ptipo){
                        $retorno=true;
                    }
                }
            }
        }
        return $retorno;
    }

    function thisUserEsTipo($ptipo='',$usid){
        $retorno=false;
        if(!auth()->guest()){
            if(!is_null(tipoUsuario($usid))){
                foreach (tipoUsuario($usid)->original as $key => $item_tipo) {
                    if($item_tipo->tipo==$ptipo){
                        $retorno=true;
                    }
                }
            }
        }
        return $retorno;
    }

    function usuarioTieneVariosRoles(){

        if(auth()->guest()){ // si no hay usuarios logueados no retornamos nada en el menu
            return false;
        }
        $listatipoFPasignados = App\td_us001_tipofpModel::where('idus001',auth()->user()->idus001)->get(); // obtenemos todos los idtipoFP asignados al usuario logueado
        if(sizeof($listatipoFPasignados)>1){
            return true;
        }else if(sizeof($listatipoFPasignados)==1 && userEsTipo('ADFP')){
            return true;
        }
        return false;

    }

    function departamentoLogueado(){
        try {
            if(auth()->guest()){ // si no hay usuarios logueados no retornamos nada en el menu
                goto MENU1;
            }else if(auth()->user()->idtipoFP <=0 || is_null(auth()->user()->idtipoFP)){
                goto MENU1;
            }else{
                $objUs001_tipofp = App\td_us001_tipofpModel::with('departamento','tipofp')
                    ->where('idus001',auth()->user()->idus001)
                    ->where('idtipoFP',auth()->user()->idtipoFP)
                    ->first();

                return [
                    'iddepartamento'=>(string)$objUs001_tipofp->departamento->iddepartamento,
                    'departamento' => (string)$objUs001_tipofp->departamento->nombre,
                    'tipoFP' =>(string)$objUs001_tipofp->tipofp->descripcion
                ];

            }
        } catch (\Throwable $th) {
            goto MENU1;
        }

        MENU1:
        return [
            'iddepartamento' => 0,
            'departamento' => "Sin departamento",
            'tipoFP' =>"Sin Tipo"
        ];

    }

    function listarMenuSession(){
        // FP= funcionario publico

        $consultaMenu = array(); // iniciamos la variable de retorno como un arreglo vacio
        if(auth()->guest()){ // si no hay usuarios logueados no retornamos nada en el menu
            goto FINALM;
        }

        $isAdmin = userEsTipo('ADFP');

        $consultaMenu= App\MenuModel::with(['gestion'=>function($query_gestion){
            $query_gestion->orderBy('nombregestion','ASC');    
        }])->get(); // obtenemos todo el menu de opciones

        if($isAdmin == true && auth()->user()->idtipoFP==0 ){goto FINALM;} // preguntamos si es un usuario FP administrador en ese caso dejamos que pueda ver todos los menus

            $listatipoFPasignados = App\td_us001_tipofpModel::where('idus001',auth()->user()->idus001)->get(); // obtenemos todos los idtipoFP asignados al usuario logueado

            //validamos si no a seleccionado un tipo de usuario
            if(sizeof($listatipoFPasignados)==0){
                // si no tiene ningun tipo asignado retornamos el menu como vacio
                $consultaMenu = array();
                goto FINALM;
            }else if(sizeof($listatipoFPasignados)==1 && auth()->user()->idtipoFP==0){

                $usuarioLogueado = App\User::find(auth()->user()->idus001); // buscamos el usuario logueado
                $usuarioLogueado->idtipoFP=$listatipoFPasignados[0]->idtipoFP; //actualizamos el idtipoFP con el id del unico tipoFP que tiene asignado
                $usuarioLogueado->save();
            }else if(sizeof($listatipoFPasignados)>1 && auth()->user()->idtipoFP==0){ // si tiene mas de un tipo de usuaio asignado y no a seleccionado uno para iniciar sesion
                return array(); // no retornamos nada porque el usuario no a seleccionado un tipofp
            }




        $idtipoFP=auth()->user()->idtipoFP; // si no FP administrador obtenemos el tipo de usuario
        $tipoFPGestion = App\TipoFPGestionModel::where('idtipoFP',$idtipoFP)->get(); // obtenemos todas las gestiones que tiene asignadas dicho tipo de FP

        foreach ($consultaMenu as $m => $menu){ // recorremos cada uno de los menus
            foreach ($menu->gestion as $g => $gestion) { // recorremos cada una de las gestiones de cada menu
                $gestionasignada=false; // falso si la gestion actual no esta asignada al usuario logueado
                foreach ($tipoFPGestion as $tg => $tipoFP) { // recorremos las gestiones asignadas al usuario y la comparamos con la gestion del menu
                    if($gestion->idgestion==$tipoFP->idgestion){
                        $gestionasignada=true;
                        break;
                    }
                }
                if(!$gestionasignada){ // si es falso quiere decir que la gestion no esta asignada al usuario
                    unset($menu->gestion[$g]); // eliminamos la gestion del menu
                }
            }
            // verificamos si el menu ahún contiene gestiones
            if(sizeof($menu->gestion)<=0){ // si no tiene ninguna gestion eliminamos el menu
                unset($consultaMenu[$m]);
            }
        }

        FINALM:
        //dd($consultaMenu);
        return $consultaMenu; // retornamos el menu solo con las gestiones que le pertenecen al usuario

    }
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function listarMenu()
    {
        $consultaMenu = array(); // iniciamos la variable de retorno como un arreglo vacio
        if(auth()->guest()){ // si no hay usuarios logueados no retornamos nada en el menu
            goto FINALM;
        }
        $consultaMenu=App\MenuModel::with(['ruta'=>function($query_gestion){
                $query_gestion->orderBy('descripcion','ASC');    
        }])->get(); 

        $isAdministrador=App\tipoUsuarioModel::where('detalle','administrador')->where('idtipo_usuario',auth()->user()->idtipo_usuario)->first();
        if(!is_null($isAdministrador))
        {
            goto FINALM;
        }

         // if($isAdmin == true && auth()->user()->idtipoFP==0 ){goto FINALM;} // preguntamos si es un usuario FP administrador en ese caso dejamos que pueda ver todos los menus


        $opciones=App\OpcionesModel::where('idtipo_usuario',auth()->user()->idtipo_usuario)->get();

        foreach ($consultaMenu as $keym => $menu) {
        foreach ($menu->ruta as $keyr => $ruta) {
            $rutaasignada=false;
            foreach ($opciones as $keyo => $tipou) {
                if($ruta->idruta==$tipou->idruta)
                {
                $rutaasignada=true;
                break;
                }
            }
                if(!$rutaasignada)
                {
                    unset($menu->ruta[$keyr]);
                }
        }
                if(sizeof($menu->ruta)<=0){ // si no tiene ninguna gestion eliminamos el menu
                        unset($consultaMenu[$keym]);
                    }
            }

                FINALM:
        //dd($consultaMenu);
        return $consultaMenu; // retornamos el menu solo con las gestiones que le pertenecen al usuario


    }


    // función para separar los nombres  y apellidos unidos un una sola cadena
    function separarNombre($full_name){

        $datos =  [];

        //$full_name ='MOREIRA CRUZ RAMONA';

        /* separar el nombre completo en espacios */
        $tokens = explode(' ', trim($full_name));
        /* arreglo donde se guardan las "palabras" del nombre */
        $names = array();
        /* palabras de apellidos (y nombres) compuetos */
        $special_tokens = array('da', 'de', 'del', 'la', 'las', 'los', 'mac', 'mc', 'van', 'von', 'y', 'i', 'san', 'santa');

        $prev = "";
        foreach($tokens as $token) {
            $_token = strtolower($token);
            if(in_array($_token, $special_tokens)) {
                $prev .= "$token ";
            } else {
                $names[] = $prev. $token;
                $prev = "";
            }
        }

        $num_nombres = count($names);
        $nombres = $apellidos = "";
        switch ($num_nombres) {
            case 0:
                $nombres = '';
                break;
            case 1:
                $nombres = $names[0];
                break;
            case 2:
                $nombres    = $names[0];
                $apellidos  = $names[1];
                break;
            case 3:
                $apellidos = $names[0] . ' ' . $names[1];
                $nombres   = $names[2];
            default:
                $apellidos = $names[0] . ' '. $names[1];
                unset($names[0]);
                unset($names[1]);

                $nombres = implode(' ', $names);
                break;
        }

        $datos =  [];

        $datos[0]=$nombres;

        $datos[1]=$apellidos;

        return $datos;

        //return 'Nombres: ' . $nombres . 'Apellidos: '. $apellidos ;

        //$nombres    = mb_convert_case($nombres, MB_CASE_TITLE, 'UTF-8');
        //$apellidos  = mb_convert_case($apellidos, MB_CASE_TITLE, 'UTF-8');

    }

    function tipoUsuarioName(){
        if(auth()->guest()){ // si no hay usuarios logueados no retornamos nada en el menu
             return [
            
            'departamento' => ""
            
        ];

            }
        else{
        $idusuario=auth()->user()->idusuario;
        $tipousuario=App\User::where('idusuario',$idusuario)->pluck('idtipo_usuario');
        $nombreTipoUsuario=App\tipoUsuarioModel::where('idtipo_usuario',$tipousuario)->first();
         return [
                    'departamento' => $nombreTipoUsuario->detalle                    
                ];
    }

}
?>