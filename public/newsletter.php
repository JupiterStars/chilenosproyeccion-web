<?php
declare(strict_types=1);
// Newsletter descontinuado: redirigir a contacto
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

header('Location: ' . app_url('/contacto'), true, 301);
exit;
