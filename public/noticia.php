<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
    abort_404();
}

$noticia = NoticiaModel::porSlug($slug);
if (!$noticia) {
    abort_404('Noticia no encontrada');
}

$relacionadas = NoticiaModel::relacionadas(
    (int) ($noticia['id'] ?? 0),
    isset($noticia['categoria_id']) ? (int) $noticia['categoria_id'] : null,
    4
);
// Rellenar hasta 4 para grilla móvil 2×2
if (count($relacionadas) < 4) {
    $seen = [(int) ($noticia['id'] ?? 0)];
    foreach ($relacionadas as $r) {
        $seen[] = (int) ($r['id'] ?? 0);
    }
    foreach (NoticiaModel::recientes(16) as $r) {
        $rid = (int) ($r['id'] ?? 0);
        if (in_array($rid, $seen, true)) {
            continue;
        }
        $relacionadas[] = $r;
        $seen[] = $rid;
        if (count($relacionadas) >= 4) {
            break;
        }
    }
}
$relacionadas = array_slice($relacionadas, 0, 4);

$pageTitle = ($noticia['meta_titulo'] ?? $noticia['titulo']) . ' | ChilenosProyección';
$metaDescription = $noticia['meta_descripcion'] ?? ($noticia['extracto'] ?? '');
$ogImage = app_url($noticia['imagen_destacada_url'] ?? '/assets/brand/goleadores-sub20.jpg');
$canonical = app_url('/noticia/' . $slug);

require INCLUDES_PATH . '/header.php';
?>
<article class="container article">
  <div class="article-hero">
    <img
      src="<?= e(app_url($noticia['imagen_destacada_url'] ?? '/assets/brand/goleadores-sub20.jpg')) ?>"
      alt="<?= e($noticia['imagen_alt'] ?? $noticia['titulo']) ?>"
      loading="eager"
      fetchpriority="high"
    />
  </div>
  <?php if (!empty($noticia['categoria_slug'])): ?>
    <a class="badge" href="<?= e(app_url('/futbol-joven/' . $noticia['categoria_slug'])) ?>">
      <?= e($noticia['categoria_nombre'] ?? '') ?>
    </a>
  <?php endif; ?>
  <h1><?= e($noticia['titulo']) ?></h1>
  <div class="article-meta">
    <?= e($noticia['autor_nombre'] ?? 'Redacción ChilenosProyección') ?>
    · <?= e(format_fecha($noticia['fecha_publicacion'] ?? null)) ?>
  </div>

  <?php if (!empty($noticia['extracto'])): ?>
    <p class="article-deck"><?= e($noticia['extracto']) ?></p>
  <?php endif; ?>


  <div class="article-body">
    <?= $noticia['contenido'] ?? '<p>' . e($noticia['extracto'] ?? '') . '</p>' ?>
  </div>

  <?php if (!empty($noticia['imagen_credito'])): ?>
    <p class="meta">Crédito imagen: <?= e($noticia['imagen_credito']) ?></p>
  <?php endif; ?>

  <div class="article-end-cta">
    <a class="btn btn-ghost" href="<?= e(app_url('/futbol-joven/' . ($noticia['categoria_slug'] ?? 'sub-20'))) ?>">Más de la categoría</a>
    <a class="btn btn-primary" href="<?= e(app_url('/goleadores/sub-20')) ?>">Ver goleadores</a>
  </div>
</article>

<?php if ($relacionadas): ?>
<section class="section related-section">
  <div class="container">
    <div class="section-head"><h2>Seguí leyendo</h2></div>
    <?php /* Desktop: cards normales */ ?>
    <div class="related-desktop card-grid featured">
      <?php foreach ($relacionadas as $noticia): ?>
        <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
      <?php endforeach; ?>
    </div>
    <?php /* Móvil: grilla 2×2 */ ?>
    <div class="related-mobile feed-news-grid">
      <?php foreach ($relacionadas as $n): ?>
        <?php require INCLUDES_PATH . '/partials/feed-story-grid-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require INCLUDES_PATH . '/footer.php'; ?>
