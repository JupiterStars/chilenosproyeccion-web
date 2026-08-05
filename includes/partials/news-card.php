<?php
/** @var array $n|$noticia */
$n = $n ?? $noticia ?? [];
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$imgRaw = trim((string) ($n['imagen_destacada_url'] ?? ''));
$sinImg = ($imgRaw === '' || $imgRaw === 'none' || $imgRaw === '-');
$img = $sinImg ? '' : noticia_img_url($n);
$cat = $n['categoria_nombre'] ?? 'Noticia';
$dorada = noticia_tiene_etiqueta_dorada($n);
?>
<article class="news-card<?= $dorada ? ' news-card--gold' : '' ?><?= $sinImg ? ' news-card--no-img' : '' ?>">
  <a href="<?= e($url) ?>">
    <div class="news-card-media<?= $sinImg ? ' news-card-media--empty' : '' ?>">
      <?php if (!$sinImg): ?>
        <img src="<?= e($img) ?>" alt="<?= e($n['imagen_alt'] ?? $n['titulo'] ?? '') ?>" loading="lazy" />
      <?php endif; ?>
    </div>
    <div class="news-card-body">
      <?php if ($dorada): ?>
        <span class="badge badge--gold">Goleada</span>
      <?php else: ?>
        <span class="badge"><?= e($cat) ?></span>
      <?php endif; ?>
      <h3><?= e($n['titulo'] ?? '') ?></h3>
      <?php if (!empty($n['extracto'])): ?>
        <p><?= e($n['extracto']) ?></p>
      <?php endif; ?>
      <div class="meta"><?= e(format_fecha($n['fecha_publicacion'] ?? null)) ?></div>
      <span class="feed-cta feed-cta--inline news-card-cta">Leer noticia →</span>
    </div>
  </a>
</article>
