<?php
    //parametros de nuestro servidor
  
    define('DB_HOST','localhost');
    define('DB_NAME','loshorneros');
    define('DB_USER','root');
    define('DB_PASS','');
    define('DB_CHARSET','utf8mb4');

    // Ruta base (ajusta si usás VirtualHost o subcarpeta)
    define('BASE_URL', 'http://localhost/loshorneros/');

    // Ruta absoluta al directorio del proyecto
    define('BASE_PATH', dirname(__DIR__) . '/');

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



   