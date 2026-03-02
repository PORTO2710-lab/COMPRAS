<?php
// php/producto_agrega.php — inserta un nuevo producto
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

require_once __DIR__ . '/conexion.php';

try {
    $body = json_decode(file_get_contents('php://input'), true);

    $nombre      = trim($body['nombre']      ?? '');
    $descripcion = trim($body['descripcion'] ?? '');
    $precio      = floatval($body['precio']  ?? 0);
    $stock       = intval($body['stock']     ?? 0);
    $imagen      = $body['imagen']           ?? null;   // base64 o null

    if ($nombre === '' || $precio <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Nombre y precio son requeridos']);
        exit;
    }

    $pdo  = getConexion();
    $stmt = $pdo->prepare(
        'INSERT INTO producto (nombre, descripcion, precio, stock, imagen)
         VALUES (:nombre, :descripcion, :precio, :stock, :imagen)'
    );
    $stmt->execute([
        ':nombre'      => $nombre,
        ':descripcion' => $descripcion,
        ':precio'      => $precio,
        ':stock'       => $stock,
        ':imagen'      => $imagen,
    ]);

    echo json_encode(['ok' => true, 'id' => (int)$pdo->lastInsertId()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
