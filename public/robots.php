<?php
declare(strict_types=1);

define('CP_SKIP_SESSION', true);
$__boot = is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php';
require_once $__boot;

if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}
header_remove('Set-Cookie');
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$base = rtrim(env('APP_URL', 'https://futbolistaschilenos.cl') ?? '', '/');
if (str_starts_with($base, 'http://') && !str_contains($base, 'localhost')) {
    $base = 'https://' . substr($base, 7);
}

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /admin/\n";
echo "Disallow: /api/\n";
echo "Sitemap: {$base}/sitemap.xml\n";
