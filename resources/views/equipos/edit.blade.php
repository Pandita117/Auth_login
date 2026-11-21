<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <x-navbar />

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning fw-bold">Editar equipo: {{ $equipo->codigo }}</div>
                    <div class="card-body">
                        <x-equipo-form :equipo="$equipo" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>