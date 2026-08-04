<?php
declare(strict_types=1);

/**
 * Sitemap XML para Google Search Console / crawlers.
 * Sin cookies de sesión, cacheable, Content-Type estricto.
 */
define('CP_SKIP_SESSION', true);
// Local: public/ → ../includes · HostGator: root aplanado → ./includes
$__boot = is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php';
require_once $__boot;

// Evitar que PHP meta session cache headers si algo arrancó sesión
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}

header_remove('Set-Cookie');
header('Content-Type: application/xml; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=3600');
header('X-Robots-Tag: noindex'); // el sitemap no se indexa como página

$base = rtrim(env('APP_URL', 'https://futbolistaschilenos.cl') ?? '', '/');
// Forzar https en prod si .env quedó mal
if (str_starts_with($base, 'http://') && !str_contains($base, 'localhost')) {
    $base = 'https://' . substr($base, 7);
}

$urls = [
    '/',
    '/futbol-joven',
    '/futbol-joven/sub-20',
    '/futbol-joven/sub-18',
    '/futbol-joven/sub-16',
    '/futbol-joven/sub-15',
    '/goleadores/sub-20',
    '/posiciones/sub-20',
    '/programacion/sub-20',
    '/newsletter',
    '/contacto',
    '/quienes-somos',
    '/legales/politica-privacidad',
    '/legales/terminos-y-condiciones',
    '/legales/politica-cookies',
    '/legales/politica-editorial',
];
try {
    foreach (NoticiaModel::recientes(100) as $n) {
        if (!empty($n['slug'])) {
            $urls[] = '/noticia/' . $n['slug'];
        }
    }
} catch (Throwable $e) {
    // sin DB: solo URLs estáticas
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    $loc = $base . $u;
    echo '  <url><loc>' . e($loc) . '</loc></url>' . "\n";
}
echo '</urlset>' . "\n";
