<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$configPath = realpath(__DIR__ . '/../config.php');
if ($configPath === false) die('Error: No se encontró config.php');
require_once $configPath;

if (!defined('API_URL')) die('Error: API_URL no definida');

$response = @file_get_contents(API_URL . "/api/EntradaDeDocumentos");
if ($response === false) die('Error al conectarse a la API');

$entradas = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) die('Error al parsear respuesta: ' . json_last_error_msg());

$proveedores_response = @file_get_contents(API_URL . "/api/Proveedores");
$proveedores = json_decode($proveedores_response, true) ?? [];

$proveedores_map = [];
foreach ($proveedores as $p) {
    $proveedores_map[$p['identificador']] = $p['nombre'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas de Documentos</title>
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

        .table th {
            background-color: #1f2937 !important;
            color: white;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .btn-create {
            background-color: #0d6efd;
            color: white;
            font-weight: 500;
        }

        .btn-create:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Menú</h4>
    <a href="../Index/Index.php"><i class="fas fa-home me-2"></i>Inicio</a>
    <a href="../Conceptos%20de%20pago/index.php"><i class="fas fa-file-invoice me-2"></i>Conceptos de Pago</a>
    <a href="index.php"><i class="fas fa-folder-open me-2"></i>Entradas de Documentos</a>
    <a href="../Gestion%20de%20proveedores/index.php"><i class="fas fa-users me-2"></i>Proveedores</a>
    <a href="../Asientos%20Contables/index.php"><i class="fas fa-book me-2"></i>Asientos Contables</a>
</div>

<!-- Contenido Principal -->
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Entradas de Documentos</h2>
        <a href="crear.php" class="btn btn-create"><i class="fas fa-plus"></i> Nueva Entrada</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered shadow-sm rounded">
            <thead>
                <tr>
                    <th>Número Factura</th>
                    <th>Fecha Documento</th>
                    <th>Monto</th>
                    <th>Fecha Registro</th>
                    <th>Proveedor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entradas as $entrada): ?>
                    <tr>
                        <td><?= htmlspecialchars($entrada['numeroFacturaAPagar']) ?></td>
                        <td><?= date('d/m/Y', strtotime($entrada['fechaDocumento'])) ?></td>
                        <td>$<?= number_format($entrada['monto'], 2) ?></td>
                        <td><?= date('d/m/Y', strtotime($entrada['fechaRegistro'])) ?></td>
                        <td><?= htmlspecialchars($proveedores_map[$entrada['proveedor']] ?? 'No encontrado') ?></td>
                        <td>
                            <span class="badge <?= $entrada['estado'] == 'Pagado' ? 'bg-success' : 'bg-warning' ?>">
                                <?= $entrada['estado'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="editar.php?id=<?= $entrada['numeroDocumento'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar.php?id=<?= $entrada['numeroDocumento'] ?>" onclick="return confirm('¿Eliminar este documento?')" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($entradas)): ?>
                    <tr>
                        <td colspan="7">No hay entradas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
