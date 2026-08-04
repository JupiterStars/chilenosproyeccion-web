<?php
/** @var array $n|$noticia */
$n = $n ?? $noticia ?? [];
$url = app_url('/noticia/' . ($n['slug'] ?? ''));
$img = $n['imagen_destacada_url'] ?? '/assets/brand/goleadores-sub20.jpg';
if ($img && !str_starts_with($img, 'http') && !str_starts_with($img, '/')) {
    $img = '/' . $img;
}
$cat = $n['categoria_nombre'] ?? 'Noticia';
?>
<article class="news-card">
  <a href="<?= e($url) ?>">
    <div class="news-card-media">
      <img src="<?= e(str_starts_with((string)$img, 'http') ? $img : app_url($img)) ?>" alt="<?= e($n['imagen_alt'] ?? $n['titulo'] ?? '') ?>" loading="lazy" />
    </div>
    <div class="news-card-body">
      <span class="badge"><?= e($cat) ?></span>
      <h3><?= e($n['titulo'] ?? '') ?></h3>
      <?php if (!empty($n['extracto'])): ?>
        <p><?= e($n['extracto']) ?></p>
      <?php endif; ?>
      <div class="meta"><?= e(format_fecha($n['fecha_publicacion'] ?? null)) ?></div>
      <span class="feed-cta feed-cta--inline news-card-cta">Leer noticia →</span>
    </div>
  </a>
</article>
