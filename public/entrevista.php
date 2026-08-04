<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$slug = trim($_GET['slug'] ?? '');
$e = EntrevistaModel::porSlug($slug);
if (!$e) {
    abort_404('Entrevista no encontrada');
}

$pageTitle = ($e['titulo'] ?? 'Entrevista') . ' | ChilenosProyección';
$metaDescription = $e['extracto'] ?? 'Entrevista ChilenosProyección';
$ogImage = app_url($e['imagen_url'] ?? '/assets/brand/portada-preview.jpg');
$navActive = 'entrevistas';

require INCLUDES_PATH . '/header.php';
?>
<article class="section article">
  <div class="container article-narrow">
    <p class="entity-kicker">Entrevista</p>
    <h1 class="article-title"><?= e($e['titulo'] ?? '') ?></h1>
    <p class="article-meta">
      <?= e(format_fecha($e['fecha_publicacion'] ?? null)) ?>
      <?php if (!empty($e['jugador_nombre'])): ?>
        · <a href="<?= e(app_url('/jugador/' . ($e['jugador_slug'] ?? ''))) ?>"><?= e($e['jugador_nombre']) ?></a>
      <?php endif; ?>
      <?php if (!empty($e['club_nombre'])): ?>
        · <?= e($e['club_nombre']) ?>
      <?php endif; ?>
    </p>
    <?php if (!empty($e['imagen_url'])): ?>
      <figure class="article-figure">
        <img src="<?= e(app_url($e['imagen_url'])) ?>" alt="<?= e($e['titulo'] ?? '') ?>" />
      </figure>
    <?php endif; ?>
    <div class="article-body">
      <?= sanitize_html($e['cuerpo'] ?? '') ?>
    </div>
    <?php if (!empty($e['video_url'])): ?>
      <p><a class="btn btn-primary" href="<?= e($e['video_url']) ?>" rel="noopener noreferrer" target="_blank">Ver video</a></p>
    <?php endif; ?>
    <p style="margin-top:2rem"><a class="btn btn-ghost" href="<?= e(app_url('/entrevistas')) ?>">← Todas las entrevistas</a></p>
  </div>
</article>
<?php require INCLUDES_PATH . '/footer.php'; ?>
