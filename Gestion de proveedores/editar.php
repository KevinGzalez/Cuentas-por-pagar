<?php
require_once __DIR__ . '/../config.php';

// Obtener el ID del proveedor a editar
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Obtener datos del proveedor
$proveedor_url = API_URL . "/api/Proveedores/$id";
$ch = curl_init($proveedor_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $proveedor = json_decode($response, true);
} else {
    echo "<script>alert('Proveedor no encontrado'); window.location.href='index.php';</script>";
    exit;
}

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que la cédula tenga exactamente 11 dígitos
    if (strlen($_POST['cedula_rnc']) != 11) {
        echo "<script>alert('La cédula debe tener exactamente 11 dígitos.');</script>";
    } else {
        $data = [
            "identificador" => $id,
            "nombre" => $_POST['nombre'],
            "tipoDePersona" => $_POST['tipo_de_persona'],
            "cedulaRNC" => $_POST['cedula_rnc'],
            "balance" => (float)$_POST['balance'],
            "estado" => $_POST['estado']
        ];

        $ch = curl_init(API_URL . "/api/Proveedores/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code == 200) {
            echo "<script>alert('Proveedor actualizado correctamente'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el proveedor. Código HTTP: " . $http_code . "');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Proveedor</h1>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= $proveedor['nombre'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Persona</label>
                <select name="tipo_de_persona" class="form-control">
                    <option value="Fisica" <?= $proveedor['tipoDePersona'] == 'Fisica' ? 'selected' : '' ?>>Física</option>
                    <option value="Juridica" <?= $proveedor['tipoDePersona'] == 'Juridica' ? 'selected' : '' ?>>Jurídica</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cedula_rnc" class="form-label">Cédula/RNC (11 dígitos):</label>
                <input type="text" class="form-control" id="cedula_rnc" name="cedula_rnc" value="<?= htmlspecialchars($proveedor['cedulaRNC']) ?>" required pattern="[0-9]{11}" title="Debe ingresar exactamente 11 dígitos">
                <div class="form-text">La cédula debe tener exactamente 11 dígitos.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Balance</label>
                <input type="number" step="0.01" name="balance" class="form-control" value="<?= $proveedor['balance'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-control">
                    <option value="Activo" <?= $proveedor['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="Inactivo" <?= $proveedor['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
