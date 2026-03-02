<?php
// php/pedido_guarda.php — guarda un pedido completo con su detalle
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

require_once __DIR__ . '/conexion.php';

try {
    $body   = json_decode(file_get_contents('php://input'), true);
    $items  = $body['items']  ?? [];
    $total  = floatval($body['total'] ?? 0);

    if (empty($items)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'El carrito está vacío']);
        exit;
    }

    $pdo = getConexion();
    $pdo->beginTransaction();

    // Insertar cabecera del pedido
    $stmtP = $pdo->prepare('INSERT INTO pedido (total) VALUES (:total)');
    $stmtP->execute([':total' => $total]);
    $pedidoId = (int)$pdo->lastInsertId();

    // Insertar cada línea de detalle
    $stmtD = $pdo->prepare(
        'INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unit)
         VALUES (:pedido_id, :producto_id, :cantidad, :precio_unit)'
    );

    foreach ($items as $item) {
        $stmtD->execute([
            ':pedido_id'   => $pedidoId,
            ':producto_id' => intval($item['id']),
            ':cantidad'    => intval($item['qty']),
            ':precio_unit' => floatval($item['precio']),
        ]);
        // Descontar stock
        $pdo->prepare('UPDATE producto SET stock = stock - :qty WHERE id = :id')
            ->execute([':qty' => intval($item['qty']), ':id' => intval($item['id'])]);
    }

    $pdo->commit();
    echo json_encode(['ok' => true, 'pedido_id' => $pedidoId]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
