<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Almacen - Ventas - Compras</title>

    {{-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lexend+Deca|Source+Sans+Pro&display=swap" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/material-dashboard.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    {{-- <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css"> --}}
</head>
<body>
    <div id="app">

    </div>
    <script>
        var api_url="{{ asset('api') }}";
    </script>
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/funcionalidad.js') }}"></script>
    <script src="{{ asset('js/material-dashboard.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" ></script> --}}
    {{-- <script src="{{ asset('js/bootstrap-material-desing.js') }}"></script> --}}
    <script src="https://www.gstatic.com/firebasejs/5.8.4/firebase.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.number.js') }}"></script>
</body>
</html>