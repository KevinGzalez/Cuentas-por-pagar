<?php
require_once __DIR__ . '/../config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener lista de proveedores
$proveedores_url = API_URL . "/api/Proveedores";
$ch = curl_init($proveedores_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$proveedores = ($http_code == 200) ? json_decode($response, true) : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entrada_url = API_URL . "/api/EntradaDeDocumentos";

    $data = [
        'numeroFacturaAPagar' => $_POST['numero_factura'],
        'fechaDocumento' => $_POST['fecha_documento'],
        'monto' => $_POST['monto'],
        'fechaRegistro' => date('Y-m-d'),
        'proveedor' => $_POST['proveedor'],
        'estado' => $_POST['estado']
    ];

    $ch = curl_init($entrada_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 201) {
        echo "<script>alert('Documento creado correctamente'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al crear el documento. Por favor, intente nuevamente.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Entrada de Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            display: flex;
            background-color: #f0f2f5;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background-color: #1f2937;
            color: white;
            padding-top: 20px;
            height: 100vh;
            position: fixed;
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 12px 25px;
            text-decoration: none;
            font-size: 15px;
            border-left: 4px solid transparent;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #374151;
            border-left: 4px solid #0d6efd;
        }

        .content {
            margin-left: 240px;
            padding: 40px;
            width: 100%;
        }

        .form-container {
            background-color: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.08);
        }

        .form-label {
            font-weight: 500;
        }

        .btn-success {
            background-color: #198754;
            border: none;
        }

        .btn-success:hover {
            background-color: #157347;
        }

        .btn-secondary {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Menú</h4>
    <a href="../Index/Index.php"><i class="fas fa-home me-2"></i>Inicio</a>
    <a href="../Conceptos%20de%20pago/index.php"><i class="fas fa-file-invoice me-2"></i>Conceptos de Pago</a>
    <a href="../Entradas%20de%20documentos%20x%20Pagar/index.php"><i class="fas fa-folder-open me-2"></i>Documentos</a>
    <a href="../Gestion%20de%20proveedores/index.php"><i class="fas fa-users me-2"></i>Proveedores</a>
    <a href="../Asientos%20Contables/index.php"><i class="fas fa-book me-2"></i>Asientos Contables</a>
</div>

<!-- Contenido principal -->
<div class="content">
    <div class="container mt-4">
        <div class="form-container">
            <h2 class="fw-bold mb-4">Crear Nueva Entrada de Documento</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="numero_factura" class="form-label">Número de Factura:</label>
                    <input type="number" class="form-control" id="numero_factura" name="numero_factura" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_documento" class="form-label">Fecha del Documento:</label>
                    <input type="date" class="form-control" id="fecha_documento" name="fecha_documento" required>
                </div>
                <div class="mb-3">
                    <label for="monto" class="form-label">Monto:</label>
                    <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                </div>
                <div class="mb-3">
                    <label for="proveedor" class="form-label">Proveedor:</label>
                    <select name="proveedor" id="proveedor" class="form-control" required>
                        <option value="">Seleccione un proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?= $proveedor['identificador'] ?>"><?= htmlspecialchars($proveedor['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Pagado">Pagado</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
