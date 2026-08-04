<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$slug = trim($_GET['slug'] ?? '');
$tag = TagModel::porSlug($slug);
if (!$tag) {
    abort_404('Tema no encontrado');
}
$noticias = TagModel::noticiasPorTag($slug);

$pageTitle = ($tag['nombre'] ?? $slug) . ' | ChilenosProyección';
$metaDescription = 'Noticias sobre ' . ($tag['nombre'] ?? $slug);

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Tema: <?= e($tag['nombre'] ?? $slug) ?></h1></div>
    <?php if ($noticias): ?>
      <div class="card-grid featured">
        <?php foreach ($noticias as $noticia): ?>
          <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">Sin noticias con este tema todavía.</div>
    <?php endif; ?>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
