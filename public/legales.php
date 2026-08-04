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
    'politica-editorial' => null, // inline fallback
];

if (!isset($map[$doc])) {
    abort_404('Documento legal no encontrado');
}

$file = $map[$doc];
if ($file && is_file(__DIR__ . '/legales/' . $file)) {
    require __DIR__ . '/legales/' . $file;
    return;
}

// Política editorial (corta) si no hay archivo dedicado
$pageTitle = 'Política editorial | ChilenosProyección';
$metaDescription = 'Criterios editoriales y uso de IA en ChilenosProyección.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Política editorial</h1></div>
    <p>ChilenosProyección publica información de fútbol juvenil y formativas con verificación de fuentes (planillas, clubes, federación y corresponsales).</p>
    <h2>Asistencia de IA</h2>
    <p>Podemos apoyarnos en herramientas digitales para redactar o resumir, pero la publicación editorial pasa por revisión humana cuando el criterio del medio lo exige.</p>
    <h2>Menores de edad</h2>
    <p>Respetamos la Ley 21.430 y buenas prácticas de imagen de menores: no publicamos datos sensibles innecesarios y priorizamos consentimiento cuando corresponde al uso de imágenes identificables.</p>
    <h2>Correcciones</h2>
    <p>Errores se corrigen de forma visible. Contacto: <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a>.</p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
