<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

$id = $_GET['id'];

// Obtener los datos actuales del concepto
$response = @file_get_contents(API_URL . "/$id");
$concepto = json_decode($response, true);

if (!$concepto) {
    die("No se encontró el concepto.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        "identificador" => $id,
        "descripcion" => $_POST['descripcion'],
        "estado" => $_POST['estado']
    ];

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json",
            "method"  => "PUT",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents(API_URL . "/$id", false, $context);

    if ($result !== false) {
        echo "<script>alert('Concepto actualizado correctamente.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el concepto.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Concepto de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Concepto de Pago</h1>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <input type="text" name="descripcion" class="form-control" value="<?= $concepto['descripcion'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado:</label>
                <select name="estado" class="form-control">
                    <option value="Activo" <?= $concepto['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="Inactivo" <?= $concepto['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
