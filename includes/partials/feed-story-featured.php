<?php
/** Destacada grande estilo app móvil @var array $n */
$n = $n ?? $noticia ?? [];
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$img = noticia_img_url($n);
$tiempo = format_tiempo_relativo($n['fecha_publicacion'] ?? null);
?>
<article class="feed-featured">
  <a class="feed-featured-link" href="<?= e($url) ?>">
    <div class="feed-featured-media">
      <img src="<?= e($img) ?>" alt="<?= e($n['imagen_alt'] ?? $n['titulo'] ?? '') ?>" loading="lazy" />
      <span class="feed-featured-bar" aria-hidden="true"></span>
    </div>
    <div class="feed-featured-body">
      <h3 class="feed-featured-title"><?= e($n['titulo'] ?? '') ?></h3>
      <?php if ($tiempo !== ''): ?>
        <p class="feed-time"><?= e($tiempo) ?></p>
      <?php endif; ?>
    </div>
  </a>
</article>
