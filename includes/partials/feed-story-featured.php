<?php
/** Destacada grande estilo app móvil @var array $n */
$n = $n ?? $noticia ?? [];
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$img = noticia_img_url($n);
$tiempo = format_tiempo_relativo($n['fecha_publicacion'] ?? null);
$catSlug = (string) ($n['categoria_slug'] ?? 'sub-20');
$dorada = noticia_tiene_etiqueta_dorada($n);
$sinImg = ($img === '');
?>
<article class="feed-featured<?= $sinImg ? ' feed-featured--no-img' : '' ?><?= $dorada ? ' feed-featured--gold' : '' ?>">
  <a class="feed-featured-link" href="<?= e($url) ?>">
    <?php if (!$sinImg): ?>
      <div class="feed-featured-media">
        <img src="<?= e($img) ?>" alt="<?= e($n['imagen_alt'] ?? $n['titulo'] ?? '') ?>" loading="lazy" />
        <span class="feed-featured-bar" aria-hidden="true"></span>
      </div>
    <?php else: ?>
      <div class="feed-featured-media feed-featured-media--empty" aria-hidden="true">
        <span class="feed-featured-bar" aria-hidden="true"></span>
      </div>
    <?php endif; ?>
    <div class="feed-featured-body">
      <?php if ($dorada): ?>
        <span class="badge badge--gold">Goleada</span>
      <?php elseif (!empty($n['categoria_nombre'])): ?>
        <span class="badge"><?= e((string) $n['categoria_nombre']) ?></span>
      <?php endif; ?>
      <h3 class="feed-featured-title"><?= e($n['titulo'] ?? '') ?></h3>
      <?php if ($tiempo !== ''): ?>
        <p class="feed-time"><?= e($tiempo) ?></p>
      <?php endif; ?>
    </div>
  </a>
  <div class="feed-card-ctas">
    <a class="feed-cta feed-cta--primary" href="<?= e($url) ?>">Leer</a>
    <a class="feed-cta feed-cta--ghost" href="<?= e(app_url('/futbol-joven/' . $catSlug)) ?>">Más de la categoría</a>
  </div>
</article>
