<?php
declare(strict_types=1);

/**
 * Bootstrap — carga .env, sesión, helpers y Database.
 * Fuera de public/ (no accesible por HTTP en HostGator si docroot = public/).
 */

define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', __DIR__);
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Errores
$debug = true;
error_reporting(E_ALL);

// Cargar .env simple (sin Composer)
$envFile = ROOT_PATH . '/.env';
if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\"'");
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

$debug = filter_var($_ENV['APP_DEBUG'] ?? '0', FILTER_VALIDATE_BOOLEAN);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');

date_default_timezone_set('America/Santiago');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once INCLUDES_PATH . '/helpers.php';
require_once INCLUDES_PATH . '/Database.php';
require_once INCLUDES_PATH . '/models/NoticiaModel.php';
require_once INCLUDES_PATH . '/models/CategoriaModel.php';
require_once INCLUDES_PATH . '/models/GoleadorModel.php';
require_once INCLUDES_PATH . '/models/PosicionModel.php';
require_once INCLUDES_PATH . '/models/ProgramacionModel.php';
require_once INCLUDES_PATH . '/models/ClubModel.php';
require_once INCLUDES_PATH . '/models/JugadorModel.php';
require_once INCLUDES_PATH . '/models/TagModel.php';
require_once INCLUDES_PATH . '/models/SuscriptorModel.php';
require_once INCLUDES_PATH . '/models/EntrevistaModel.php';
