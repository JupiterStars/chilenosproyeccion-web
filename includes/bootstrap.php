<?php
declare(strict_types=1);

/**
 * Bootstrap — carga .env, sesión, helpers y Database.
 * Fuera de public/ (no accesible por HTTP en HostGator si docroot = public/).
 */

define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', __DIR__);
define('PUBLIC_PATH', ROOT_PATH . '/public');

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

// Default seguro: debug off salvo APP_DEBUG=1 explícito
$appEnv = strtolower((string) ($_ENV['APP_ENV'] ?? 'production'));
$debugDefault = in_array($appEnv, ['local', 'dev', 'development'], true) ? '0' : '0';
$debug = filter_var($_ENV['APP_DEBUG'] ?? $debugDefault, FILTER_VALIDATE_BOOLEAN);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
ini_set('expose_php', '0');

date_default_timezone_set('America/Santiago');

// Cookies de sesión endurecidas (antes de session_start).
// sitemap/robots: sin sesión ni Set-Cookie (Google Search Console a veces falla "No se ha podido obtener").
$scriptBase = basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['SCRIPT_NAME'] ?? ''));
$skipSession = in_array($scriptBase, ['sitemap.php', 'robots.php'], true)
    || (defined('CP_SKIP_SESSION') && CP_SKIP_SESSION);

$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
    || (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https');

if (!$skipSession && session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('CPSESSID');
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

// Cabeceras de seguridad en todas las respuestas HTML/JSON
if (function_exists('send_security_headers')) {
    send_security_headers();
}
