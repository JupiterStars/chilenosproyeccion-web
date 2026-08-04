<?php
/**
 * Patrón móvil de noticias (ciclo):
 *  1 grande → 4 filas → 2 lado a lado → grilla 4 (2×2) → 2 grandes → repite
 *
 * Inserts opcionales (HTML string o callable) entre bloques del primer ciclo:
 *  $newsFeedInserts = [
 *    'after_grid4' => '...',      // tras la grilla 2×2
 *    'after_two_large' => '...',  // tras las 2 noticias grandes
 *  ];
 *
 * Desktop: grilla clásica de cards.
 *
 * @var list<array<string,mixed>> $noticias
 * @var array<string, mixed> $newsFeedInserts
 */
$noticias = array_values($noticias ?? []);
$newsFeedInserts = $newsFeedInserts ?? [];
if (!$noticias) {
    return;
}

$renderInsert = static function (string $key) use ($newsFeedInserts): void {
    if (empty($newsFeedInserts[$key])) {
        return;
    }
    $v = $newsFeedInserts[$key];
    if (is_callable($v)) {
        $v();
        return;
    }
    echo (string) $v;
};
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
        ['type' => 'featured', 'n' => 1, 'slot' => 'featured1'],
        ['type' => 'rows', 'n' => 4, 'slot' => 'rows'],
        ['type' => 'pair', 'n' => 2, 'slot' => 'pair'],
        ['type' => 'grid4', 'n' => 4, 'slot' => 'grid4'],
        ['type' => 'featured', 'n' => 2, 'slot' => 'two_large'],
    ];
    $step = 0;
    $didAfterGrid4 = false;
    $didAfterTwoLarge = false;
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
        $slot = $block['slot'];

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

        // Inserts del home: solo en el primer ciclo
        if ($step <= count($cycle)) {
            if ($slot === 'grid4') {
                $renderInsert('after_grid4');
                $didAfterGrid4 = true;
            }
            if ($slot === 'two_large') {
                $renderInsert('after_two_large');
                $didAfterTwoLarge = true;
            }
        }
    endwhile;

    // Si no hubo suficientes noticias para llegar al slot, igual mostrar inserts
    if (!$didAfterGrid4) {
        $renderInsert('after_grid4');
    }
    if (!$didAfterTwoLarge) {
        $renderInsert('after_two_large');
    }
  ?>
</div>
<?php
// Limpiar inserts para no filtrar a otro require en la misma request
$newsFeedInserts = [];
?>
