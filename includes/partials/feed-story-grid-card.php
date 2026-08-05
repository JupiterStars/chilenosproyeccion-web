<?php
/** Card compacta para grilla 2×2 en móvil + CTA @var array $n */
$n = (isset($noticia) && is_array($noticia)) ? $noticia : ($n ?? []);
unset($noticia);
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$img = noticia_img_url($n);
$tiempo = format_tiempo_relativo($n['fecha_publicacion'] ?? null);
$cat = $n['categoria_nombre'] ?? '';
$dorada = noticia_tiene_etiqueta_dorada($n);
$sinImg = ($img === '');
?>
<article class="feed-grid-card<?= $dorada ? ' feed-grid-card--gold' : '' ?>">
  <a class="feed-grid-card-link" href="<?= e($url) ?>">
    <div class="feed-grid-card-media<?= $sinImg ? ' feed-grid-card-media--empty' : '' ?>">
      <?php if (!$sinImg): ?>
        <img src="<?= e($img) ?>" alt="<?= e($n['imagen_alt'] ?? $n['titulo'] ?? '') ?>" loading="lazy" />
      <?php endif; ?>
    </div>
    <div class="feed-grid-card-body">
      <?php if ($dorada): ?>
        <span class="badge badge--gold badge--sm">Goleada</span>
      <?php elseif ($cat !== ''): ?>
        <span class="feed-grid-card-cat"><?= e($cat) ?></span>
      <?php endif; ?>
      <h3 class="feed-grid-card-title"><?= e($n['titulo'] ?? '') ?></h3>
      <?php if ($tiempo !== ''): ?>
        <p class="feed-time"><?= e($tiempo) ?></p>
      <?php endif; ?>
      <span class="feed-cta feed-cta--inline">Leer →</span>
    </div>
  </a>
</article>
