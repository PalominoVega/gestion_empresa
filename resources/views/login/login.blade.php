
 <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lexend+Deca|Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/material-dashboard.css') }}">

</head>

<body id="#" class="" >
  <!-- Navigation-->
  <div class="login">
    <div class="modal-dialog h text-center">
        <div class="col-sm-9 col-10 main-section">
            <div class="modal-content h">
                <div class="col-12 user-img">
                    <img src="{{asset('img/logo.png')}}" alt="">
                </div>
                <div class="col-12 form-input">
                    <form  method="POST" action="{{ route('postlogin') }}" id="login">
                        {{ csrf_field() }}
                         <div class="form-group">
                            <input id="email" type="text" class="form-control {{ $errors->has('email') ? ' input-error' : '' }}" name="email" value="{{ old('email') }}" required  placeholder="Ingrese Usuario"> {{-- autofocus --}}
                             @if ($errors->has('email'))
                                <span class='error'>
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' input-error' : '' }}">
                            <input id="password" type="password" class="form-control" name="password" required placeholder="Ingrese contraseña">

                            @if ($errors->has('password'))
                                <span class="error">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-lg btn-success"> LOGIN</button>                        
                    </form>
                </div>
                <div class="col-12 form-input">
                    <a class="" href="#">
                        <span class="nav-link-text">Olvide mi contraseña</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/material-dashboard.min.js') }}"></script>
<script src="https://www.gstatic.com/firebasejs/5.8.4/firebase.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery.number.js') }}"></script>
  
</body>

</html>
