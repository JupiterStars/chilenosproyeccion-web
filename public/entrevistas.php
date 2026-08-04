<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$items = EntrevistaModel::listar(20);
$pageTitle = 'Entrevistas | ChilenosProyección';
$metaDescription = 'Entrevistas a jugadores del fútbol joven y formativas chilenas.';
$navActive = 'entrevistas';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Entrevistas</h1></div>
    <p class="page-intro">Voces de cantera: conversaciones con juveniles, técnicos y protagonistas del fútbol formativo.</p>

    <div class="card-grid card-grid-3">
      <?php foreach ($items as $e): ?>
        <article class="news-card">
          <a class="news-card-media" href="<?= e(app_url('/entrevista/' . $e['slug'])) ?>">
            <img
              src="<?= e(app_url($e['imagen_url'] ?? '/assets/brand/portada-preview.jpg')) ?>"
              alt="<?= e($e['titulo'] ?? '') ?>"
              loading="lazy"
            />
          </a>
          <div class="news-card-body">
            <p class="news-card-cat">Entrevista<?php if (!empty($e['jugador_nombre'])): ?> · <?= e($e['jugador_nombre']) ?><?php endif; ?></p>
            <h3 class="news-card-title">
              <a href="<?= e(app_url('/entrevista/' . $e['slug'])) ?>"><?= e($e['titulo'] ?? '') ?></a>
            </h3>
            <p class="news-card-excerpt"><?= e($e['extracto'] ?? '') ?></p>
            <p class="news-card-meta"><?= e(format_fecha($e['fecha_publicacion'] ?? null)) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
