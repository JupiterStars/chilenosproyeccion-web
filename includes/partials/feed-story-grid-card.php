<?php
/** Card compacta para grilla 2×2 en móvil @var array $n */
$n = $n ?? $noticia ?? [];
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$img = noticia_img_url($n);
$tiempo = format_tiempo_relativo($n['fecha_publicacion'] ?? null);
$cat = $n['categoria_nombre'] ?? '';
?>
<article class="feed-grid-card">
  <a class="feed-grid-card-link" href="<?= e($url) ?>">
    <div class="feed-grid-card-media">
      <img src="<?= e($img) ?>" alt="<?= e($n['imagen_alt'] ?? $n['titulo'] ?? '') ?>" loading="lazy" />
    </div>
    <div class="feed-grid-card-body">
      <?php if ($cat !== ''): ?>
        <span class="feed-grid-card-cat"><?= e($cat) ?></span>
      <?php endif; ?>
      <h3 class="feed-grid-card-title"><?= e($n['titulo'] ?? '') ?></h3>
      <?php if ($tiempo !== ''): ?>
        <p class="feed-time"><?= e($tiempo) ?></p>
      <?php endif; ?>
    </div>
  </a>
</article>
