<?php
require_once __DIR__ . '/../config.php';

$proveedores_url = API_URL . '/api/Proveedores';
$ch = curl_init($proveedores_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$proveedores = $http_code == 200 ? json_decode($response, true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Proveedores</title>
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
    <a href="../Entradas%20de%20documentos%20x%20Pagar/index.php"><i class="fas fa-folder-open me-2"></i>Documentos</a>
    <a href="index.php"><i class="fas fa-users me-2"></i>Proveedores</a>
    <a href="../Asientos%20Contables/index.php"><i class="fas fa-book me-2"></i>Asientos Contables</a>
</div>

<!-- Contenido Principal -->
<div class="content">
    <h2 class="fw-bold mb-4">Lista de Proveedores</h2>
    <a href="crear.php" class="btn btn-create mb-3"><i class="fas fa-plus"></i> Agregar Nuevo Proveedor</a>

    <div class="table-responsive">
        <table class="table table-striped table-bordered shadow-sm rounded">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cédula / RNC</th>
                    <th>Balance</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proveedores)): ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td><?= $proveedor['identificador'] ?></td>
                            <td><?= $proveedor['nombre'] ?></td>
                            <td><?= $proveedor['cedulaRNC'] ?></td>
                            <td>$<?= number_format($proveedor['balance'], 2) ?></td>
                            <td><?= $proveedor['estado'] ?></td>
                            <td>
                                <a href="editar.php?id=<?= $proveedor['identificador'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar.php?id=<?= $proveedor['identificador'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este proveedor?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay proveedores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
