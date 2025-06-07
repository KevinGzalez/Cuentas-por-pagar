<?php
include 'config.php';

$response = @file_get_contents(API_URL);
$conceptos = json_decode($response, true);

if (!$conceptos) {
    $conceptos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Conceptos de Pago</title>
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
    <a href="index.php"><i class="fas fa-file-invoice me-2"></i>Conceptos de Pago</a>
    <a href="../Entradas%20de%20documentos%20x%20Pagar/index.php"><i class="fas fa-folder-open me-2"></i>Documentos</a>
    <a href="../Gestion%20de%20proveedores/index.php"><i class="fas fa-users me-2"></i>Proveedores</a>
    <a href="../Asientos%20Contables/index.php"><i class="fas fa-book me-2"></i>Asientos Contables</a>
</div>

<!-- Contenido principal -->
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Conceptos de Pago</h2>
        <a href="crear.php" class="btn btn-create"><i class="fas fa-plus"></i> Nuevo Concepto</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered shadow-sm rounded">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($conceptos as $concepto): ?>
                    <tr>
                        <td><?= $concepto['identificador'] ?></td>
                        <td><?= $concepto['descripcion'] ?></td>
                        <td>
                            <span class="badge <?= $concepto['estado'] === 'Activo' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $concepto['estado'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="editar.php?id=<?= $concepto['identificador'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar.php?id=<?= $concepto['identificador'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este concepto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($conceptos)): ?>
                    <tr><td colspan="4">No hay conceptos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
