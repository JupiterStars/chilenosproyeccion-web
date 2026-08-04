<?php
/**
 * Patrón móvil de noticias: 1 grande → 4 en grilla 2×2 → 1 grande → 4…
 * Desktop: grilla clásica de cards.
 *
 * @var list<array<string,mixed>> $noticias
 */
$noticias = array_values($noticias ?? []);
if (!$noticias) {
    return;
}
?>
<?php /* Desktop / tablet: grilla habitual */ ?>
<div class="news-layout-desktop card-grid featured">
  <?php foreach ($noticias as $noticia): ?>
    <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
  <?php endforeach; ?>
</div>

<?php /* Móvil: 1 destacada + 4 grid, repetido */ ?>
<div class="news-layout-mobile">
  <?php
    $i = 0;
    $total = count($noticias);
    while ($i < $total):
      // 1 grande
      $n = $noticias[$i];
      $i++;
      require INCLUDES_PATH . '/partials/feed-story-featured.php';

      // hasta 4 en grilla 2×2
      $batch = array_slice($noticias, $i, 4);
      if ($batch):
        $i += count($batch);
  ?>
    <div class="feed-news-grid">
      <?php foreach ($batch as $n): ?>
        <?php require INCLUDES_PATH . '/partials/feed-story-grid-card.php'; ?>
      <?php endforeach; ?>
    </div>
  <?php
      endif;
    endwhile;
  ?>
</div>
