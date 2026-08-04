<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$categorias = categorias_futbol_joven();
$recientes = NoticiaModel::recientes(12);

$pageTitle = 'Fútbol joven | ChilenosProyección';
$metaDescription = 'Portada de fútbol joven: Sub-20, Sub-18, Sub-16 y Sub-15.';
$navActive = 'futbol-joven';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Fútbol joven</h1></div>
    <p class="page-intro">Cobertura de divisiones formativas e infantiles. Elegí una categoría.</p>
    <div class="chip-row" style="margin-bottom:2rem">
      <?php foreach ($categorias as $cat): ?>
        <a class="chip is-active" href="<?= e(app_url('/futbol-joven/' . $cat['slug'])) ?>"><?= e($cat['nombre']) ?></a>
      <?php endforeach; ?>
      <a class="chip" href="<?= e(app_url('/goleadores/sub-20')) ?>">Goleadores</a>
      <a class="chip" href="<?= e(app_url('/posiciones/sub-20')) ?>">Posiciones</a>
      <a class="chip" href="<?= e(app_url('/programacion/sub-20')) ?>">Programación</a>
    </div>
    <div class="card-grid featured">
      <?php foreach ($recientes as $noticia): ?>
        <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
