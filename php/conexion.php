<?php
// ============================================================
//  Configuración de conexión — basecompra
//  Ajusta host/user/pass según tu entorno local
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // usuario MySQL (XAMPP: root)
define('DB_PASS', '12345678');           // contraseña  (XAMPP: vacía)
define('DB_NAME', 'basecompra');
define('DB_PORT', 3306);

function getConexion(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT .
               ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
    }
    return $pdo;
}
