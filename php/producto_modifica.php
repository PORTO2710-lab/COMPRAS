<?php
// php/producto_modifica.php — actualiza un producto existente
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

require_once __DIR__ . '/conexion.php';

try {
    $body = json_decode(file_get_contents('php://input'), true);

    $id          = intval($body['id']          ?? 0);
    $nombre      = trim($body['nombre']        ?? '');
    $descripcion = trim($body['descripcion']   ?? '');
    $precio      = floatval($body['precio']    ?? 0);
    $stock       = intval($body['stock']       ?? 0);
    // imagen: si viene en el payload la actualizamos; si no se envía la clave, la dejamos intacta
    $actualizarImg = array_key_exists('imagen', $body);
    $imagen        = $body['imagen'] ?? null;

    if ($id <= 0 || $nombre === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'ID y nombre son requeridos']);
        exit;
    }

    $pdo = getConexion();

    if ($actualizarImg) {
        $stmt = $pdo->prepare(
            'UPDATE producto SET nombre=:nombre, descripcion=:descripcion,
             precio=:precio, stock=:stock, imagen=:imagen WHERE id=:id'
        );
        $stmt->execute([
            ':nombre'      => $nombre,
            ':descripcion' => $descripcion,
            ':precio'      => $precio,
            ':stock'       => $stock,
            ':imagen'      => $imagen,
            ':id'          => $id,
        ]);
    } else {
        $stmt = $pdo->prepare(
            'UPDATE producto SET nombre=:nombre, descripcion=:descripcion,
             precio=:precio, stock=:stock WHERE id=:id'
        );
        $stmt->execute([
            ':nombre'      => $nombre,
            ':descripcion' => $descripcion,
            ':precio'      => $precio,
            ':stock'       => $stock,
            ':id'          => $id,
        ]);
    }

    echo json_encode(['ok' => true, 'filas' => $stmt->rowCount()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
