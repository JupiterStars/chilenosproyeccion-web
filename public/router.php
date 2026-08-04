<?php
/**
 * Router para PHP built-in server (php -S ... router.php)
 * En HostGator/Apache se usa .htaccess — este archivo no se usa allí.
 */
declare(strict_types=1);

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
$file = __DIR__ . $uri;

// Estáticos
if ($uri !== '/' && is_file($file)) {
    return false;
}

// Categorías: nacional, regional e infantil
$catPattern = 'sub-20|sub-18|sub-16|sub-15|sub-13|sub-20-regional|sub-18-regional|sub-16-regional|sub-15-regional|sub-14-infantil|sub-13-infantil|sub-12-infantil|sub-11-infantil';

$routes = [
    '#^/$#' => 'index.php',
    '#^/futbol-joven/?$#' => 'futbol-joven.php',
    '#^/futbol-joven/(' . $catPattern . ')/?$#' => 'categoria.php',
    '#^/futbol-joven/(' . $catPattern . ')/pagina/(\d+)/?$#' => 'categoria.php',
    '#^/noticia/([a-z0-9\-]+)/?$#' => 'noticia.php',
    '#^/goleadores/?$#' => 'goleadores.php',
    '#^/goleadores/([a-z0-9\-]+)/?$#' => 'goleadores.php',
    '#^/posiciones/?$#' => 'posiciones.php',
    '#^/posiciones/([a-z0-9\-]+)/?$#' => 'posiciones.php',
    '#^/programacion/?$#' => 'programacion.php',
    '#^/programacion/([a-z0-9\-]+)/?$#' => 'programacion.php',
    '#^/club/([a-z0-9\-]+)/?$#' => 'club.php',
    '#^/jugador/([a-z0-9\-]+)/?$#' => 'jugador.php',
    '#^/tema/([a-z0-9\-]+)/?$#' => 'tema.php',
    '#^/entrevistas/?$#' => 'entrevistas.php',
    '#^/entrevista/([a-z0-9\-]+)/?$#' => 'entrevista.php',
    '#^/buscador/?$#' => 'buscador.php',
    '#^/newsletter/?$#' => 'newsletter.php',
    '#^/contacto/?$#' => 'contacto.php',
    '#^/quienes-somos/?$#' => 'quienes-somos.php',
    '#^/legales/(politica-privacidad|terminos-y-condiciones|politica-cookies|politica-editorial|aviso-legal|propiedad-intelectual|contacto-legal)/?$#' => 'legales.php',
    '#^/sitemap\.xml$#' => 'sitemap.php',
    '#^/robots\.txt$#' => 'robots.php',
    '#^/api/noticias/?$#' => '../api/noticias.php',
    '#^/admin/?$#' => '../admin/index.php',
    '#^/admin/login/?$#' => '../admin/login.php',
];

foreach ($routes as $pattern => $script) {
    if (preg_match($pattern, $uri, $m)) {
        if (str_contains($script, 'categoria.php')) {
            $_GET['cat'] = $m[1] ?? '';
            $_GET['pagina'] = $m[2] ?? ($_GET['pagina'] ?? '1');
        } elseif ($script === 'noticia.php' || $script === 'entrevista.php') {
            $_GET['slug'] = $m[1] ?? '';
        } elseif (in_array($script, ['goleadores.php', 'posiciones.php', 'programacion.php'], true)) {
            $_GET['categoria'] = $m[1] ?? ($_GET['categoria'] ?? '');
        } elseif ($script === 'club.php' || $script === 'jugador.php' || $script === 'tema.php') {
            $_GET['slug'] = $m[1] ?? '';
        } elseif ($script === 'legales.php') {
            $_GET['doc'] = $m[1] ?? '';
        }

        $path = str_starts_with($script, '../')
            ? dirname(__DIR__) . '/' . substr($script, 3)
            : __DIR__ . '/' . $script;

        if (is_file($path)) {
            require $path;
            return true;
        }
    }
}

http_response_code(404);
require (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');
abort_404();
