<?php
include '../config.php';

$response = @file_get_contents(API_URL . '/api/AsientosContables');
$asientos = json_decode($response, true);
if (!$asientos) $asientos = [];

$totalGeneral = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asientos Contables</title>
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

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
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
    <a href="../Gestion%20de%20proveedores/index.php"><i class="fas fa-users me-2"></i>Proveedores</a>
    <a href="index.php"><i class="fas fa-book me-2"></i>Asientos Contables</a>
</div>

<!-- Contenido principal -->
<div class="content">
    <div class="title-bar">
        <h2 class="fw-bold">Asientos Contabilizados</h2>
        <a href="crear_asiento.php" class="btn btn-create"><i class="fas fa-plus"></i> Crear Asiento</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered shadow-sm rounded">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $hayAsientos = false;
                foreach ($asientos as $a):
                    if (isset($a['estado']) && $a['estado'] === 'Contabilizado'):
                        $hayAsientos = true;
                        $total = isset($a['totalMonto']) ? floatval($a['totalMonto']) : 0;
                        $totalGeneral += $total;
                ?>
                    <tr>
                        <td><?= $a['id'] ?? '' ?></td>
                        <td><?= $a['descripcion'] ?? '' ?></td>
                        <td><?= isset($a['fechaAsiento']) ? date('d/m/Y', strtotime($a['fechaAsiento'])) : '' ?></td>
                        <td>$<?= number_format($total, 2) ?></td>
                        <td><span class="badge bg-success"><?= $a['estado'] ?></span></td>
                    </tr>
                <?php
                    endif;
                endforeach;
                if (!$hayAsientos): ?>
                    <tr>
                        <td colspan="5">No hay asientos contabilizados todavía.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <h5 class="mt-3"><strong>Total General:</strong> $<?= number_format($totalGeneral, 2) ?></h5>
    </div>
</div>

</body>
</html>
