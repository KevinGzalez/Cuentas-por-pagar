<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cuentas por Pagar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            display: flex;
            background-color: #f0f2f5;
            min-height: 100vh;
            overflow: hidden;
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

        .text-center h1 {
            font-size: 2rem;
            font-weight: bold;
        }

        .card {
            transition: transform 0.3s ease;
            border-radius: 10px;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-body {
            text-align: center;
        }

        .icon-large {
            font-size: 3rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Menú</h4>
    <a href="../Conceptos%20de%20pago/index.php"><i class="fas fa-file-invoice me-2"></i>Conceptos de Pago</a>
    <a href="../Entradas%20de%20documentos%20x%20Pagar/index.php"><i class="fas fa-folder-open me-2"></i>Entradas de Documentos</a>
    <a href="../Gestion%20de%20proveedores/index.php"><i class="fas fa-users me-2"></i>Gestión de Proveedores</a>
    <a href="../Asientos%20Contables/index.php"><i class="fas fa-book me-2"></i>Asientos Contables</a>
</div>

<!-- Contenido Principal -->
<div class="content">
    <h1 class="text-center mb-4">Gestión de Cuentas por Pagar</h1>
    <div class="container">
        <div class="row justify-content-center">

            <!-- Tarjeta Conceptos de Pago -->
            <div class="col-md-3 d-flex">
                <a href="../Conceptos%20de%20pago/index.php" class="text-decoration-none w-100">
                    <div class="card shadow p-3 mb-5 bg-white rounded">
                        <div class="card-body">
                            <i class="fas fa-file-invoice icon-large"></i>
                            <h5 class="card-title">Conceptos de Pago</h5>
                            <p class="card-text">Gestión de los conceptos relacionados con pagos.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Tarjeta Entradas de Documentos -->
            <div class="col-md-3 d-flex">
                <a href="../Entradas%20de%20documentos%20x%20Pagar/index.php" class="text-decoration-none w-100">
                    <div class="card shadow p-3 mb-5 bg-white rounded">
                        <div class="card-body">
                            <i class="fas fa-folder icon-large"></i>
                            <h5 class="card-title">Entradas de Documentos x Pagar</h5>
                            <p class="card-text">Registro y gestión de documentos por pagar.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Tarjeta Gestión de Proveedores -->
            <div class="col-md-3 d-flex">
                <a href="../Gestion%20de%20proveedores/index.php" class="text-decoration-none w-100">
                    <div class="card shadow p-3 mb-5 bg-white rounded">
                        <div class="card-body">
                            <i class="fas fa-truck icon-large"></i>
                            <h5 class="card-title">Gestión de Proveedores</h5>
                            <p class="card-text">Administración de los proveedores y sus pagos.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Tarjeta Asientos Contables -->
            <div class="col-md-3 d-flex">
                <a href="../Asientos%20contables/index.php" class="text-decoration-none w-100">
                    <div class="card shadow p-3 mb-5 bg-white rounded">
                        <div class="card-body">
                            <i class="fas fa-calculator icon-large"></i>
                            <h5 class="card-title">Asientos Contables</h5>
                            <p class="card-text">Contabilización de entradas de documentos por pagar.</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
