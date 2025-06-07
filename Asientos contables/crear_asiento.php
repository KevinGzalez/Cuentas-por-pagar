<?php
define('API_LOCAL_URL', 'http://localhost:5038/api/EntradaDeDocumentos/Pendientes');
define('API_ASIENTOS_URL', 'http://localhost:5038/api/AsientosContables');
define('API_CONTABILIDAD_URL', 'https://iso810-contabilidad.azurewebsites.net/api/EntradaContable');

$token_bearer = "eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoiS2V2aW4iLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IjY3ZjVjNjkyYTI2MjkxZDFkNjgyZTM1OSIsIlNpc3RlbWFBdXhpbGlhcklkIjoiNjdkMGEwNDY3ZGEzYTNmMDQzZDc5NTI2IiwibmJmIjoxNzQ0Njc2MTIwLCJleHAiOjE3NDcyNjgxMjB9.ejIIg8RCfqVJBb53FQJiWJxJGZ18Rwf9SYATf6QJd_4"; 

$descripcion = "";
$fechaAsiento = date('Y-m-d');
$desde = "";
$hasta = "";
$documentos = [];
$total = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['confirmar'], $_GET['descripcion'], $_GET['fecha_asiento'], $_GET['desde'], $_GET['hasta'])) {
    $descripcion = $_GET['descripcion'];
    $fechaAsiento = $_GET['fecha_asiento'];
    $desde = $_GET['desde'];
    $hasta = $_GET['hasta'];

    $url = API_LOCAL_URL . "?desde=$desde&hasta=$hasta";
    $response = @file_get_contents($url);
    if ($response !== false) {
        $documentos = json_decode($response, true);
        foreach ($documentos as $doc) {
            $total += floatval($doc['monto']);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'];
    $fechaAsiento = $_POST['fecha_asiento'];
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    $data = [
        'descripcion' => $descripcion,
        'sistemaAuxiliarId' => 6,
        'fechaAsiento' => gmdate('Y-m-d\T00:00:00\Z', strtotime($fechaAsiento)),
        'detalles' => []
    ];

    $url = API_LOCAL_URL . "?desde=$desde&hasta=$hasta";
    $response = @file_get_contents($url);
    if ($response !== false) {
        $documentos = json_decode($response, true);
        $total = 0;

        foreach ($documentos as $doc) {
            $monto = floatval($doc['monto']);
            $total += $monto;
            $data['detalles'][] = [
                'cuentaId' => 82,
                'tipoMovimiento' => 'DB',
                'montoAsiento' => $monto
            ];
        }

        $data['detalles'][] = [
            'cuentaId' => 4,
            'tipoMovimiento' => 'CR',
            'montoAsiento' => $total
        ];

        if (isset($_POST['contabilizar'])) {
            $data['totalMonto'] = $total;
            $data['estado'] = 'Contabilizado';
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);

            $ch = curl_init(API_CONTABILIDAD_URL);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer $token_bearer"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200 || $http_code === 201) {
                foreach ($documentos as $doc) {
                    $docId = $doc['numeroDocumento'];
                    $estadoJson = json_encode("Pagado");

                    $ch = curl_init("http://localhost:5038/api/EntradaDeDocumentos/Estado/$docId");
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $estadoJson);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($estadoJson)
                    ]);
                    curl_exec($ch);
                    curl_close($ch);
                }

                $opts = [
                    'http' => [
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($data)
                    ]
                ];
                $context = stream_context_create($opts);
                @file_get_contents(API_ASIENTOS_URL, false, $context);

                header("Location: index.php");
                exit;
            } else {
                echo "<h4 style='color:red'>❌ Error al enviar el asiento contable a la API externa. Código: $http_code</h4>";
                echo "<pre>$response</pre>";
                echo "<h5>JSON Enviado:</h5><pre>$json</pre>";
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Asiento Contable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-success:hover {
            background-color: #157347;
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

<!-- Contenido -->
<div class="content">
    <h2 class="fw-bold">Crear Asiento Contable</h2>
    <h6><strong>ID Auxiliar:</strong> 6</h6>

    <form method="GET" class="mb-4">
        <input type="hidden" name="confirmar" value="1">
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" required value="<?= htmlspecialchars($descripcion) ?>">
        </div>
        <div class="mb-3">
            <label>Fecha del Asiento</label>
            <input type="date" name="fecha_asiento" class="form-control" value="<?= $fechaAsiento ?>" required>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Contabilizar Desde</label>
                <input type="date" name="desde" class="form-control" value="<?= $desde ?>" required>
            </div>
            <div class="col">
                <label>Contabilizar Hasta</label>
                <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Confirmar</button>
    </form>

    <?php if (!empty($documentos)): ?>
        <h4>Entradas de Documentos</h4>
        <table class="table table-bordered shadow-sm">
            <thead>
                <tr>
                    <th>ID Cuenta</th>
                    <th>Descripción</th>
                    <th>Tipo Movimiento</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documentos as $doc): ?>
                    <tr>
                        <td>82</td>
                        <td>Cuentas x Pagar Proveedor <?= $doc['proveedor'] ?></td>
                        <td>DB</td>
                        <td>$<?= number_format($doc['monto'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="table-success fw-bold">
                    <td>4</td>
                    <td>Cuenta Corriente Banco</td>
                    <td>CR</td>
                    <td>$<?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <form method="POST">
            <input type="hidden" name="descripcion" value="<?= htmlspecialchars($descripcion) ?>">
            <input type="hidden" name="fecha_asiento" value="<?= $fechaAsiento ?>">
            <input type="hidden" name="desde" value="<?= $desde ?>">
            <input type="hidden" name="hasta" value="<?= $hasta ?>">
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" name="contabilizar" class="btn btn-success">Contabilizar</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
