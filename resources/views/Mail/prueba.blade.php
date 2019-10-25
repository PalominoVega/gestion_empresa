<html>
    <head>
        <style>
            *{
                font-family: Calibri,Arial,sans-serif;
            }

            body{
                background-color: #fafafa;
            }
            .container-vespro{
                /* border: 1px solid #eee; */
                /* padding: 15px 30px; */
                width: 600px;
                margin-left: auto;
                margin-right: auto;
                background-color: #ffffff;
            }
            .logo{
                width: 300px;
            }
            .car-border{
                border: 1px solid #403d391a;
                padding: 10px 10px;
            }
            .title{
                color: #d8232a;
            }
            .card-logo{
                background-color: #f6f6f6;
                color: #fff;
                padding: 20px 20px;
                text-align: center;
                border: 1px solid #403d391a;
            }
            .card{
                color: black;
                margin-bottom: 20px;
            }
            .table{
                 border-spacing: 0;
                border-collapse: collapse;
                width: 75%;
                text-align: left;
                margin: auto;
                margin-bottom: 20px;
            }
            .table td, .table th{
                padding: 5px 10px;
                border: 1px solid #403D39;
            }
            .card-link-web{
                background-color: yellowgreen;
                color: black;
                padding: 10px 20px;
                text-align: center;
            }
            .text-right{
                text-align: right;
            }
            
            .block{
                display: block;
            }
            .center{
                text-align: center;
            }
            a {
            text-decoration: none;
            background-color: transparent;
            -webkit-text-decoration-skip: objects;
            }
            .card-footer{
                background-color: #403D39;
                color: #fff;
                padding: 10px 20px;
            }

        </style>
    </head>
    <body>
        <div class="container-vespro">
            <div class="card-logo">
                <img class="logo" src="{{ asset('img/vespro.png') }}" alt="">
            </div>
            <div class="car-border">

                <div class="title">
                    <h3>Estimado(a) {{ strtoupper($usuario->nombre.' '.$usuario->apellido) }} de {{ strtoupper($usuario->empresa->nombre) }}</h3>
                </div>
                <div class="card">
                    <span class="block">
                        Gracias por confiar en nosotros.
                    </span>
                    <span class="block">
                        Este mensaje contiene toda la información que necesita para empezar a usar la cuenta:
                    </span>
                    
                </div>
                <div>
                    <h3>DATOS DE ACCESO</h3>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td style="width: 30px;">usuario</td>
                                <td>{{$usuario->email}}</td>
                            </tr>
                            <tr>
                                <td style="width: 30px;">contraseña</td>
                                <td >{{$contrasenia}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-link-web">
                    Para ingresar a su cuenta debe visitar al sitio web: <a href="https://gestiondeempresa.vespro.ml/login"> <b> https://gestiondeempresa.vespro.ml</b></a>
                </div>
                <div class="card">
                    <h3>ASISTENCIA - CANALES DE ATENCIÓN</h3>
                    <div class="center">
                        <span class="block"><b>Teléfono: </b>(+51)997630413</span>
                        <span class="block">Lun a vie de 9:00AM - 6:00PM</span>
                        <span class="block"><b>Correo:</b> ventas@corporacionvespro.com</span>
                        <b>Siguemos en facebook</b>
                        <a class="block" href="https://www.facebook.com/www.vespro.com.pe/">facebook.com/www.vespro.com.pe</a>
                    </div>
                </div>
                <div class="card-footer text-right">
                    Gestione tu empresa con Nosotros,
                    <b>Corporacion Vespro.</b>   
                </div>
            </div>
        </div>
    </body>    
</html>