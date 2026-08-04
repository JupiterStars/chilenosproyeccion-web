<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');
$base = rtrim(env('APP_URL', 'http://localhost:8010') ?? '', '/');
echo "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\nSitemap: {$base}/sitemap.xml\n";
