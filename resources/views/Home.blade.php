<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CMR Admin | Iniciar Sesión</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../../public/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../public/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../../public/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="../../public/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../../public/plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background-image: url(../../public/dist/img/bg-login.png);background-size:100% 100%;bakground-position:center center;">
<div class="login-box">
  <div class="login-logo">
    <a href="#" style="color:white;"><img height="100" src="../../public/dist/img/NombreTest.png"></a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Debe Iniciar Sesion</p>

    <form action="{{ route('login')}}" method="POST">
        {{ csrf_field() }}
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      {!! $errors->first('email','<span class="help-block">:message</span>') !!}
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      {!! $errors->first('password','<span class="help-block">:message</span>') !!}
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Recordar
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
      </div>
    </form>
    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="redirectgo" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
    <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>

  </div>
</div>
<script src="../../public/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../public/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
  });
</script>
</body>
</html>
