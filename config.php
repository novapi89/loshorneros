<?php
/**
 * Configuración global del proyecto "Los Horneros"
 */

// ========================
// 1. Configuración DB
// ========================
define('DB_HOST', 'localhost');     // Servidor de base de datos
define('DB_NAME', 'loshorneros');   // Nombre de la base
define('DB_USER', 'root');          // Usuario (en XAMPP suele ser root)
define('DB_PASS', '');              // Contraseña (en XAMPP root no tiene pass por defecto)
define('DB_CHARSET', 'utf8mb4');    // Charset recomendado

// ========================
// 2. Rutas del proyecto
// ========================

// Ruta base (ajusta si usás VirtualHost o subcarpeta)
define('BASE_URL', 'http://loshorneros.test/');

// Ruta absoluta al directorio del proyecto
define('BASE_PATH', dirname(__DIR__) . '/');

// Carpetas importantes
define('PUBLIC_PATH', BASE_PATH . 'public/');
define('UPLOADS_PATH', BASE_PATH . 'uploads/');

// ========================
// 3. Opciones generales
// ========================

// Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Activar errores en desarrollo (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ========================
// 4. Conexión PDO
// ========================
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Modo de errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch en arrays asociativos
        PDO::ATTR_EMULATE_PREPARES => false, // Mejor seguridad
    ]);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
