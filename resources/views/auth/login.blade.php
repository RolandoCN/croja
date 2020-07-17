<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CruzRoja</title>
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
             <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
              <img  class="" src="{{asset('images/aa.jpg')}}" width="30%">
              <h2>Inciar Sesión</h2>
              <div class="col-xs-12 form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                  <input id="email" type="email" class="form-control has-feedback-left" name="email" placeholder="Email" style="margin-bottom: 8px;" required autofocus>
                  <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                  @if ($errors->has('email'))
                      <span class="help-block" role="alert" style="margin-bottom: 0px;">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
              </div>

              <div class="col-xs-12 form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                  <input id="password" type="password" class="form-control has-feedback-left" name="password" placeholder="Clave" style="margin-bottom: 8px;" required>
                  <span class="glyphicon glyphicon-lock form-control-feedback left" aria-hidden="true"></span>
                  @if ($errors->has('password'))
                      <span class="help-block" role="alert" style="margin-bottom: 0px;">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
              </div>

              <!-- el recaptcha  -->
              <!-- <div class="g-recaptcha" style="display: inline-table; padding-bottom: 20px;" data-sitekey="6Lfzup0UAAAAADVKrqLTY_K8frn7l2eme6S50yNq"></div> -->

            


              <div>
                <button type="submit" class="btn btn-default submit">Aceptar</button>
                <!-- <a class="reset_pass" href="{{route('restaurarCont')}}">Olvidó su clave?</a> -->
              </div>
              <div class="clearfix"></div>

              <div class="separator">
             

                <div class="clearfix"></div>
                <br/>

                <div>
                  <i class="fa fa-"></i> Cruz Roja
                  <p>©2020 Todos los derechos Reservados</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">

          </section>
        </div>
      </div>
    </div>

  <!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
    <!-- <script src='https://www.google.com/recaptcha/api.js?hl=es'></script> -->
  </body>
</html>

