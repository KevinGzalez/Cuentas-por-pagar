<?php
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1); // Mostrar errores en pantalla

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'descripcion' => $_POST['descripcion'],
        'estado' => $_POST['estado']
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($data),
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents(API_URL, false, $context);

    if ($result === FALSE) {
        echo "<p class='alert alert-danger'>Error al conectar con la API.</p>";
    } else {
        header('Location: index.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Concepto de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1>Crear Nuevo Concepto de Pago</h1>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <input type="text" name="descripcion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado:</label>
                <select name="estado" class="form-control">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
