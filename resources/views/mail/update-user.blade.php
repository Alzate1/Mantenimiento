<body>
    <center>


        <div style="font-family: Arial, sans-serif; text-align: center; margin: 20px;">

            <h1 style="color: #333; font-size: 24px;">
               Actualización de usuario exitoso
                <img src="{{ asset('img/icons/comprobado.png') }}" alt="" style="width: 50px; top: 10px; position: relative;">
                <ul style="list-style-type: none; padding: 0;">
                    <li style="margin-top: 10px;">
                        <strong>Usuario:</strong> {{ $data['user'] }}
                    </li>
                    <li style="margin-top: 10px;">
                        <strong>Contraseña:</strong>
                        {{ $data['password'] }}
                    </li>
                    <li style="margin-top: 10px;">
                        <strong>Tipo de Usuario:</strong> {{ $data['userType'] }}
                    </li>
                </ul>
            </h1>
            <div style="position: relative;">
                <img src="{{ asset('img/marcaS.jpg') }}" alt="" style="width: 200px; position: relative; top: -10; left: 0;">
            </div>
        </div>
    </center>

</body>
