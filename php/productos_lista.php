<?php
// php/productos_lista.php — devuelve todos los productos en JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/conexion.php';

try {
    $pdo  = getConexion();
    $stmt = $pdo->query('SELECT id, nombre, descripcion, precio, stock, imagen FROM producto ORDER BY id');
    $rows = $stmt->fetchAll();
    echo json_encode(['ok' => true, 'datos' => $rows]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
