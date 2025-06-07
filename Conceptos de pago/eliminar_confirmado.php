<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $options = [
        "http" => [
            "method" => "DELETE"
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents(API_URL . "/$id", false, $context);

    if ($result !== false) {
        echo "<script>alert('Concepto eliminado correctamente.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el concepto.'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Solicitud inválida.'); window.location='index.php';</script>";
}
?>
