<?php
// Puede incluirse solo o vía abort_404 (ya con header)
if (!defined('INCLUDES_PATH')) {
    require_once dirname(__DIR__) . '/includes/bootstrap.php';
    $pageTitle = 'Página no encontrada | ChilenosProyección';
    $metaDescription = 'No encontramos esa página en ChilenosProyección.';
    require INCLUDES_PATH . '/header.php';
}
$mensaje = $mensaje ?? 'No encontramos esa página.';
?>
<section class="error-404" aria-labelledby="error-404-title">
  <div class="container error-404-inner">
    <div class="error-404-visual" aria-hidden="true">
      <div class="error-404-pitch">
        <span class="error-404-arc"></span>
        <span class="error-404-mid"></span>
        <span class="error-404-ball"></span>
      </div>
      <p class="error-404-code">404</p>
    </div>

    <div class="error-404-copy">
      <p class="error-404-kicker">Fuera de juego</p>
      <h1 id="error-404-title">Esta página no está en la cancha</h1>
      <p class="error-404-text">
        <?= e($mensaje) ?>
        Puede que el enlace haya cambiado o que la dirección tenga un error.
        Volvé al inicio o buscá una nota, tabla o categoría.
      </p>
      <div class="error-404-actions">
        <a class="btn btn-primary" href="<?= e(app_url('/')) ?>">Ir al inicio</a>
        <a class="btn btn-ghost" href="<?= e(app_url('/buscador')) ?>">Buscar</a>
        <a class="btn btn-ghost" href="<?= e(app_url('/futbol-joven/sub-20')) ?>">Sub-20</a>
      </div>
      <ul class="error-404-links">
        <li><a href="<?= e(app_url('/goleadores/sub-20')) ?>">Goleadores</a></li>
        <li><a href="<?= e(app_url('/posiciones/sub-20')) ?>">Posiciones</a></li>
        <li><a href="<?= e(app_url('/programacion/sub-20')) ?>">Programación</a></li>
        <li><a href="<?= e(app_url('/entrevistas')) ?>">Entrevistas</a></li>
      </ul>
    </div>
  </div>
</section>
<?php
// Footer: solo si se cargó este archivo de forma directa (abort_404 ya incluye footer)
if (!defined('INCLUDES_PATH')) {
    // unreachable if bootstrap failed
}
