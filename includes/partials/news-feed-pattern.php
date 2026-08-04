<?php
/**
 * Patrón móvil de noticias (ciclo):
 *  1 grande → 4 filas (thumb+título) → 2 lado a lado → grilla 4 (2×2) → 2 grandes → repite
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

<?php /* Móvil: patrón de bloques */ ?>
<div class="news-layout-mobile">
  <?php
    $i = 0;
    $total = count($noticias);
    // Ciclo: featured1, rows4, pair2, grid4, featured2
    $cycle = [
        ['type' => 'featured', 'n' => 1],
        ['type' => 'rows', 'n' => 4],
        ['type' => 'pair', 'n' => 2],
        ['type' => 'grid4', 'n' => 4],
        ['type' => 'featured', 'n' => 2],
    ];
    $step = 0;
    while ($i < $total):
        $block = $cycle[$step % count($cycle)];
        $step++;
        $take = min($block['n'], $total - $i);
        if ($take < 1) {
            break;
        }
        $batch = array_slice($noticias, $i, $take);
        $i += $take;
        $type = $block['type'];

        if ($type === 'featured'):
            foreach ($batch as $n) {
                require INCLUDES_PATH . '/partials/feed-story-featured.php';
            }
        elseif ($type === 'rows'):
  ?>
    <div class="feed-stack">
      <?php foreach ($batch as $n): ?>
        <?php require INCLUDES_PATH . '/partials/feed-story-row.php'; ?>
      <?php endforeach; ?>
    </div>
  <?php
        elseif ($type === 'pair'):
  ?>
    <div class="feed-news-pair">
      <?php foreach ($batch as $n): ?>
        <?php require INCLUDES_PATH . '/partials/feed-story-grid-card.php'; ?>
      <?php endforeach; ?>
    </div>
  <?php
        else: // grid4
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
