<?php
require_once __DIR__ . '/../config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener lista de proveedores para el formulario
$proveedores_url = API_URL . "/api/Proveedores";
$ch = curl_init($proveedores_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $proveedores = json_decode($response, true);
} else {
    $proveedores = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que la cédula tenga exactamente 11 dígitos
    if (strlen($_POST['cedula_rnc']) != 11) {
        echo "<script>alert('La cédula debe tener exactamente 11 dígitos.');</script>";
    } else {
        $data = [
            "nombre" => $_POST['nombre'],
            "tipoDePersona" => $_POST['tipo_de_persona'],
            "cedulaRNC" => $_POST['cedula_rnc'], // 👈 Asegurar que coincida exactamente
            "balance" => (float)$_POST['balance'], // 👈 Convertir a número
            "estado" => $_POST['estado']
        ];

        $ch = curl_init(API_URL . "/api/Proveedores");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code == 201) {
            echo "<script>alert('Proveedor agregado correctamente'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error al agregar el proveedor. Código HTTP: " . $http_code . "');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Proveedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Crear Nuevo Proveedor</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="tipo_de_persona" class="form-label">Tipo de Persona:</label>
                <select class="form-control" id="tipo_de_persona" name="tipo_de_persona" required>
                    <option value="Fisica">Física</option>
                    <option value="Juridica">Jurídica</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cedula_rnc" class="form-label">Cédula/RNC (11 dígitos):</label>
                <input type="text" class="form-control" id="cedula_rnc" name="cedula_rnc" required pattern="[0-9]{11}" title="Debe ingresar exactamente 11 dígitos">
                <div class="form-text">La cédula debe tener exactamente 11 dígitos.</div>
            </div>
            <div class="mb-3">
                <label for="balance" class="form-label">Balance:</label>
                <input type="number" class="form-control" id="balance" name="balance" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
