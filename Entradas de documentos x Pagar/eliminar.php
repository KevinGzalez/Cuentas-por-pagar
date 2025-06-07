<?php
include 'config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID no válido'); window.location.href='index.php';</script>";
    exit;
}

// Configurar la solicitud DELETE
$options = [
    'http' => [
        'method' => 'DELETE',
        'header' => 'Content-Type: application/json'
    ]
];

$context = stream_context_create($options);

try {
    $response = @file_get_contents(API_URL . "/api/EntradaDeDocumentos/$id", false, $context);
    $http_code = $http_response_header[0] ?? '';
    
    if (strpos($http_code, '200') !== false || strpos($http_code, '204') !== false) {
        echo "<script>alert('Documento eliminado correctamente'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el documento. Código HTTP: $http_code'); window.location.href='index.php';</script>";
    }
} catch (Exception $e) {
    echo "<script>alert('Error al eliminar el documento: " . addslashes($e->getMessage()) . "'); window.location.href='index.php';</script>";
}
?> 