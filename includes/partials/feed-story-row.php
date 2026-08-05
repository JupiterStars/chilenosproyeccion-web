<?php
/** Fila compacta: thumb + título + tiempo + CTA @var array $n */
$n = (isset($noticia) && is_array($noticia)) ? $noticia : ($n ?? []);
unset($noticia);
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$img = noticia_img_url($n);
$tiempo = format_tiempo_relativo($n['fecha_publicacion'] ?? null);
$dorada = noticia_tiene_etiqueta_dorada($n);
$sinImg = ($img === '');
?>
<article class="feed-row<?= $dorada ? ' feed-row--gold' : '' ?>">
  <a class="feed-row-link" href="<?= e($url) ?>">
    <div class="feed-row-thumb<?= $sinImg ? ' feed-row-thumb--empty' : '' ?>">
      <?php if (!$sinImg): ?>
        <img src="<?= e($img) ?>" alt="" loading="lazy" />
      <?php endif; ?>
    </div>
    <div class="feed-row-body">
      <?php if ($dorada): ?>
        <span class="badge badge--gold badge--sm">Goleada</span>
      <?php endif; ?>
      <h3 class="feed-row-title"><?= e($n['titulo'] ?? '') ?></h3>
      <?php if ($tiempo !== ''): ?>
        <p class="feed-time"><?= e($tiempo) ?></p>
      <?php endif; ?>
      <span class="feed-cta feed-cta--inline">Leer →</span>
    </div>
  </a>
</article>
