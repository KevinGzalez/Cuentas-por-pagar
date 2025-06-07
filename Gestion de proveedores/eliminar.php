<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID no válido'); window.location.href='index.php';</script>";
    exit;
}

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

// Si se confirmó la eliminación
if (isset($_POST['confirmar']) && $_POST['confirmar'] == 'si') {
    $ch = curl_init(API_URL . "/api/Proveedores/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200) {
        echo "<script>alert('Proveedor eliminado correctamente'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el proveedor. Código HTTP: " . $http_code . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Proveedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Eliminar Proveedor</h1>
        <p>¿Estás seguro de que deseas eliminar al proveedor <strong><?= $proveedor['nombre'] ?></strong>?</p>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
