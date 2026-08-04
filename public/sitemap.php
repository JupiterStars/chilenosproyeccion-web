<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');
$base = rtrim(env('APP_URL', 'http://localhost:8010') ?? '', '/');
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
foreach (NoticiaModel::recientes(100) as $n) {
    $urls[] = '/noticia/' . $n['slug'];
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $u): ?>
  <url><loc><?= e($base . $u) ?></loc></url>
<?php endforeach; ?>
</urlset>
