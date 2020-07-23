
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('images/logotitulo.ico')}}" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('images/logotitulo.ico')}}" type="image/png">

    <title>Cruz Roja</title>

    <!-- Bootstrap -->
      <link href="{{ asset('../vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
      <link href="{{ asset('/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    {{--   <link href="{{ asset('/vendors/nprogress/nprogress.css') }}" rel="stylesheet"> --}}
    <!-- jQuery custom content scroller -->
      <link href="{{ asset('/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
      <link rel="stylesheet" href="{{asset('/css/nuevosEstilosBootstrap4.css')}}">
      <link href="{{ asset('/build/css/custom.css') }}" rel="stylesheet">
    <!-- estilo para modulo registro  -->
      <link href="{{ asset('/css/estilosRegistrar.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/estilosGenerales.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/iconosdecarga.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/estilosGestionPermisos.css') }}" rel="stylesheet">

    <!-- Librerias para Sweet Alert -->
      <link rel="stylesheet" href="{{asset('sweetalert/sweetalert.css')}}">

    <!-- PNotify -->
      <link href="{{asset('vendors/pnotify/dist/pnotify.css')}}" rel="stylesheet">
      <link href="{{asset('vendors/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
      <link href="{{asset('vendors/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    {{-- fin --}}


  </head>

  <body class="nav-md footer_fixed">
    <div class="container body">
      <div class="main_container" style="user-select: auto;">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title">

                <img style="width: 18%" src=" {{asset('images/55.png')}}" >
                @guest
                  <img class="imagen_nav soloMobil" src="{{asset('/images/img.jpg')}}" alt="">
                @else
                  @if(Auth::user()->sexo=="Masculino")
                      <img class="imagen_nav soloMobil" src="{{asset('/images/img.jpg')}}" alt="">
                  @elseif(Auth::user()->sexo=="Femenino")
                      <img class="imagen_nav soloMobil" src="{{asset('/images/img.jpg')}}" alt="">
                  @else
                      <img class="imagen_nav soloMobil" src="{{asset('/images/img.jpg')}}" alt="">
                  @endif
                @endguest
                   <span  style="font-size: 18px; font-family: bold">CRUZ ROJA</span>
               
              </a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick informacion -->
            <div class="profile clearfix">
              <div class="profile_pic" style="width: 30%;">
                <img src="{{asset('/images/img.jpg')}}" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info" style="width: 70%; padding-left:0 !important;">
                {{-- <span>Bienvenido</span> --}}
                @guest
                  <span>Bienvenido</span>
                  <h2><a href=" {{route('login')}}" > <strong>Iniciar Sesión</strong></a></h2>
                @else
                    <br>
                  <h2 class="nameMenu">{{ Auth::user()->name }}</h2>
                  {{-- <h2 class="tipoFPMenu">{{departamentoLogueado()["tipoFP"]}}</h2> --}}
                @endguest
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br/>
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
               {{--  <h3>{{departamentoLogueado()["departamento"]}}</h3> --}}
               <h3>{{tipoUsuarioName()["departamento"]}}</h3>
                <ul class="nav side-menu" id="ul_listarMenu">

                    <li><a href="{{asset('/')}}"><i class="fa fa-home"></i> Inicio </span></a>
                    </li>

                    @foreach(listarMenu() as $menu)
                        <li><a style="background: #544546"><i class="{{$menu->icono}}"></i>{{$menu->descripcion}} <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            @foreach($menu->ruta as $ruta)
                              <li><a class="submenu_a" href="{{url($ruta->ruta)}}">
                                  <i style="font-size: 12px; width: 18px;"  class="{{$ruta->icono}} i_submenu"></i>
                                  <span>{{$ruta->descripcion}}</span>
                              </a></li>
                            @endforeach
                          </ul>
                        </li>
                    @endforeach

                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" style="background-color: #544546" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"style="background-color: #544546"></span>
              </a>
              <a data-toggle="tooltip"  style="background-color: #544546"data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"style="background-color: #544546"></span>
              </a>
              <a data-toggle="tooltip"  style="background-color: #544546" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"style="background-color: #544546"></span>
              </a>

              <a data-toggle="tooltip"  style="background-color: #544546" data-placement="top" title="Cerrar Sesión" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
               <span class="glyphicon glyphicon-off" aria-hidden="true"style="background-color: #544546"></span>
              </a>

            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle" style="width: auto;">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right" style="width: 80%;">

                @guest

                    <li class="li_nav_name_mobil">
                      <a href="{{route('login')}}" style="float:right; text-align: right; height: inherit;" class="user-profile dropdown-toggle" style="text-align: right;">
                        <img src="{{asset('/images/img.jpg')}}" alt="">
                        Iniciar Sesión
    <!--                     <span class=" fa fa-angle-down"></span> -->
                      </a>
                    </li>

                @else

                    <li class="li_nav_name_mobil">
                      <a href="javascript:;" style="float:right; text-align: right;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <div style="float:left;" class="soloEscritorio">
                            @if(Auth::user()->sexo=="Masculino")
                                <img src="{{asset('images/img.jpg')}}" alt="">
                            @elseif(Auth::user()->sexo=="Femenino")
                                <img src="{{asset('images/img.jpg')}}" alt="">
                            @else
                                <img src="{{asset('images/img.jpg')}}" alt="">
                            @endif
                        </div>
                        <div style="float:right;" class="div_nav_name_mobil">{{ Auth::user()->name }} <span class=" fa fa-angle-down"></span></div>

                      </a>
                      <ul class="dropdown-menu dropdown-usermenu pull-right">
                       {{--  <li><a onclick="$('#Perfil_ModalFP').modal();"><i class="fa fa-user pull-right"></i>Ver Perfil</a></li> --}}
                        <li><a onclick="$('#Cambio_Contraseña_Modal').modal();"><i class="fa fa-key pull-right"></i>Cambiar Contraseña</a></li>
                       {{--  @if(usuarioTieneVariosRoles())
                            <li><a href="{{asset('loginTipoFP')}}"><i class="fa fa-group pull-right"></i>Cambiar Tipo FP</a></li>
                        @endif --}}
                        <li>
                            <a  href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out pull-right"></i>Cerrar Sesión
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                      </ul>
                    </li>

                @endguest

                <li role="presentation" class="dropdown li_nav_notif_mobil">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bell-o"></i>
                    <span id="numero_notific"></span>                  
                  </a>
                  <ul id="ul_notificaciones" style="overflow-y: scroll; max-height: 370px;" class="dropdown-menu list-unstyled msg_list ul_notific" role="menu"></ul>
                </li>

              </ul>
            </nav>
          </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
         <!-- jQuery -->
        <script src="{{ asset('../vendors/jquery/dist/jquery.min.js') }}"></script>

        <div class="right_col" role="main">
                @yield('contenido')
        </div>
        @if(!auth()->guest())
          @include('auth.modalperfil')
          @include('auth.modalCambioContraseña')
        @endif

      </div>
    </div>


    <!-- Bootstrap -->
     <script src="{{ asset('../vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- FastClick -->
     <script src="{{ asset('../vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
     <script src="{{ asset('../vendors/nprogress/nprogress.js') }}"></script>
    <!-- jQuery custom content scroller -->
     <script src="{{ asset('../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    {{-- Archivo de config inicio --}}
     <script src="{{ asset('/js/cargarInicio.js')}}"></script>
    <!-- Custom Theme Scripts -->
     <script src="{{ asset('../build/js/custom.js') }}"></script>
    {{-- Libreria para usar el combo filter --}}
      <link href="{{ asset('/css/bootstrap_combofilter.css') }}" rel="stylesheet">
      <script src="{{ asset('/css/chosen.jquery.js') }}"></script>
      <script>
          $(function() {
            $('.chosen-select').chosen();
            $('.chosen-select-deselect').chosen({ allow_single_deselect: false });
          });
        </script>

      <!-- Librerias para Sweet Alert -->
        <script src="{{asset('sweetalert/sweetalert.js')}}"></script>

      <!-- PNotify -->
        <script src="{{asset('vendors/pnotify/dist/pnotify.js')}}"></script>
        <script src="{{asset('vendors/pnotify/dist/pnotify.buttons.js')}}"></script>
      {{-- FIN --}}


      @include('divcargando')
  </body>
</html>