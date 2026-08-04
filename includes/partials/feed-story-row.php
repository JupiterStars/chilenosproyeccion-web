<?php
/** Fila compacta: thumb + título + tiempo + CTA @var array $n */
$n = $n ?? $noticia ?? [];
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$img = noticia_img_url($n);
$tiempo = format_tiempo_relativo($n['fecha_publicacion'] ?? null);
?>
<article class="feed-row">
  <a class="feed-row-link" href="<?= e($url) ?>">
    <div class="feed-row-thumb">
      <img src="<?= e($img) ?>" alt="" loading="lazy" />
    </div>
    <div class="feed-row-body">
      <h3 class="feed-row-title"><?= e($n['titulo'] ?? '') ?></h3>
      <?php if ($tiempo !== ''): ?>
        <p class="feed-time"><?= e($tiempo) ?></p>
      <?php endif; ?>
      <span class="feed-cta feed-cta--inline">Leer →</span>
    </div>
  </a>
</article>
