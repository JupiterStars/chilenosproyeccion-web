<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$doc = trim($_GET['doc'] ?? '');
$map = [
    'politica-privacidad' => 'politica-privacidad.php',
    'terminos-y-condiciones' => 'terminos-y-condiciones.php',
    'politica-cookies' => 'politica-cookies.php',
    'aviso-legal' => 'aviso-legal.php',
    'propiedad-intelectual' => 'propiedad-intelectual.php',
    'contacto-legal' => 'contacto-legal.php',
    'politica-editorial' => 'politica-editorial.php',
];

if (!isset($map[$doc])) {
    abort_404('Documento legal no encontrado');
}

$file = $map[$doc];
$path = __DIR__ . '/legales/' . $file;
if (!is_file($path)) {
    abort_404('Documento legal no encontrado');
}

require $path;
