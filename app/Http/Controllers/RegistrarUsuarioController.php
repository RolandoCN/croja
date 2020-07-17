<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TipoUsuarioModel;
use App\ProvinciaModel;
use App\PersonaModel;
use App\User;
use Log;
use Hash;
use Illuminate\Support\Facades\Validator;


class RegistrarUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         try {        
            $listaUsuario = User::with('tipoUsuario')->get(); // obtenemos todas los usuarios con el respectivo tipousuario a la que petenece
            $listaTipoUsuario = TipoUsuarioModel::where('estado','Activo')->get();// obtenemos todas los tipos usuarios para cargar en el combo
            //dd($listaProvincias);
            return view('gestionUsuario.registro',[
                'listaUsuario'=> $listaUsuario,
                'listaTipoUsuario'=>$listaTipoUsuario
          ]);
        } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back();
        }

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // try {

            // CREAMOS ARREGLO PARA ENVIAR A LA FUNCION A VALIDAR CARACTERES ESPECIALES
            $validaCE=array(
                
                'name'=>$request->get('name'),
                'idtipo_usuario'=>$request->get('cmb_tipo')
            );

            if(tieneCaracterEspecialRequest($validaCE)){
                return back()->with(['mensajePInfoUsuario'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
            };
             

            $email=$request->get('email');

            $dataCorreo=[
                    'email' => $request->email
                   
                ];
            
              $reglacorreo = 'required|string|email|max:255';

                $validator = Validator::make($dataCorreo, [
                    'email' => $reglacorreo
                  
                ]);
            
            if($validator->fails()) {    
                  return back()->with(['mensajePInfoUsuario'=>'Formato de correo electrónico incorrecto','estadoP'=>'danger']);
                }

            $password=$request->get('password');
            $dataPassword=[
                    'password' => $request->password
                   
                ];
            
              $reglaPassword = 'string|required|min:8';

                $validator = Validator::make($dataPassword, [
                    'password' => $reglaPassword
                  
                ]);
            
            if($validator->fails()) {    
                  return back()->with(['mensajePInfoUsuario'=>'La contraseña debe tener al menos 8 dígitos','estadoP'=>'danger']);
                }

            $confirma_pass=$request->get("pass2");
            $contra=$request->get('password');

            if($contra!=$confirma_pass)
            {
                return back()->with(['mensajePInfoUsuario'=>'La contraseñas de confirmación no es igual','estadoP'=>'danger']);
            }

             if(is_null($request->get('cmb_tipo')))
                {
                    return back()->with(['mensajePInfoUsuario'=>'Ingrese un tipo de usuario','estadoP'=>'danger']);

                }

            //GUARDAMOS LOS USUARIOS EN LA BASE DE DATOS
            $usuario= new User();
            $usuario->name=$request->get('name');
            $usuario->email=$request->get('email');
            $usuario->password=bcrypt($request->get('password'));
            $usuario->idtipo_usuario = $request->get('cmb_tipo');
            //$usuario->created_at=date('d/m/Y');

            $correoexistente=User::where('email',$usuario->email)->first();
             if(!is_null($correoexistente))
            {
             return back()->with(['mensajePInfoUsuario'=>'Usuario no ingresado, el correo ya existe','estadoP'=>'danger']);
            }

            $usuarioexistente=User::where('name',$usuario->name)
            ->where('email',$usuario->email)->where('password',$usuario->password)->first();
            if(!is_null($usuarioexistente))
            {
             return back()->with(['mensajePInfoUsuario'=>'Usuario no ingresado, el registro ya existe','estadoP'=>'danger']);
            }

          

            if($usuario->save()){
                return back()->with(['mensajePInfoUsuario'=>'Registro exitoso','estadoP'=>'success']);
            }else{
                return back()->with(['mensajePInfoUsuario'=>'No se pudo realizar el registro','estadoP'=>'danger']);
            }
        // } catch (\Throwable $th) {
        //     Log::error("Error get Request Id ".$th->getMessage());
        //     return back()->with(['mensajePInfoUsuario'=>'Error el realizar el registro','estadoP'=>'danger']);
        // }

    }

        // PARA REALIZAR EL CAMBIO DE CONTRASEÑA 
    public function cambiarcontrasena(Request $request){
        $usuario= auth()->User();
            if (Hash::check($request['passwordActaul'], $usuario->password))
            {
                if($request['passwordCambio']==$request['password_confirmationCambio']){
                    if($this->validarclavecambio($request['passwordCambio'])==false){
                          return back()->with(['estado'=>'1','validaclave'=>'La contraseña debe tener al menos 8 caracteres, incluir letras mayúsculas, minúsculas y numeros']);
                    }else{
                        if (Hash::check($request['passwordCambio'], $usuario->password))
                        {
                            return back()->with(['estado'=>'1','mensajeigualActual'=>'La nueva contraseña no puede ser igual a la anterior']);
                        }else{
                            $usuario->password=bcrypt($request['passwordCambio']);
                            $usuario->save();
                            return back()->with(['estado'=>'1','mensajeCambio'=>'Cambio de contraseña exitoso']);
                        }
                    }
                }else{
                      return back()->with(['estado'=>'1','errorcoincide'=>'La nueva contraseña no coincide']);
                } 
            }else{
                return back()->with(['estado'=>'1','errorclaveactual'=>'La contraseña actual no es la correcta']);
            }
    }

    function validarclavecambio($clave){
        $resultado=preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/',$clave);
        if($resultado==1):return true; else: return false; endif;
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
        $DesencriptarID=array(
            'id'=>decrypt($id)
        );
        //Validar si tiene caracteres especiales
        if(tieneCaracterEspecialRequest($DesencriptarID)){
            return back()->with(['mensajePInfoUsuario'=>'No puede ingresar caracteres especiales','estadoP'=>'danger']);
        };
        
        $usuario= User::find(decrypt($id));
        if($usuario->delete())
        {
             return back()->with(['mensajePInfoUsuario'=>'El registro fué eliminado','estadoP'=>'success']);
        }
         else{
                return back()->with(['mensajePInfoUsuario'=>'No se pudo eliminar el registro','estadoP'=>'danger']);
            }

         } catch (\Throwable $th) {
            Log::error("Error get Request Id ".$th->getMessage());
            return back()->with(['mensajePInfoUsuario'=>'Error al eliminar el dato, registro se encuentra relacionado','estadoP'=>'danger']);
        }
    }
}
