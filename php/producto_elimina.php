<?php
// php/producto_elimina.php — elimina un producto
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

require_once __DIR__ . '/conexion.php';

try {
    $body = json_decode(file_get_contents('php://input'), true);
    $id   = intval($body['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'ID inválido']);
        exit;
    }

    $pdo  = getConexion();
    $stmt = $pdo->prepare('DELETE FROM producto WHERE id = :id');
    $stmt->execute([':id' => $id]);

    echo json_encode(['ok' => true, 'filas' => $stmt->rowCount()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
