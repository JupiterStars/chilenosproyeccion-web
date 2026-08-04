<?php
/**
 * Sección de partidos (páginas de 4) para home móvil.
 *
 * @var string $matchSectionKind  'proximos'|'resultados'
 * @var list<list<array>> $matchPages  chunks de partidos
 * @var string $matchTitle
 * @var string $matchSubtitle
 * @var string $matchBadge
 * @var string $matchBadgeClass  ''|'feed-section-badge--rojo'
 * @var string $matchVerTodo
 * @var string $matchLigaSuffix  ' · Próximo'|' · Final'
 * @var bool $matchIsResultado
 */
$matchPages = $matchPages ?? [];
if (!$matchPages) {
    return;
}
$matchTitle = $matchTitle ?? 'Partidos';
$matchSubtitle = $matchSubtitle ?? '';
$matchBadge = $matchBadge ?? 'P';
$matchBadgeClass = $matchBadgeClass ?? '';
$matchVerTodo = $matchVerTodo ?? app_url('/programacion/sub-20');
$matchLigaSuffix = $matchLigaSuffix ?? '';
$matchIsResultado = !empty($matchIsResultado);
$rotate = count($matchPages) > 1;
?>
<header class="feed-section-head">
  <div class="feed-section-brand">
    <div class="feed-section-badge<?= $matchBadgeClass !== '' ? ' ' . e($matchBadgeClass) : '' ?>"><?= e($matchBadge) ?></div>
    <div>
      <strong><?= e($matchTitle) ?></strong>
      <?php if ($matchSubtitle !== ''): ?>
        <span><?= e($matchSubtitle) ?></span>
      <?php endif; ?>
    </div>
  </div>
  <a class="feed-ver-todo" href="<?= e($matchVerTodo) ?>">Ver todo</a>
</header>
<div
  class="feed-matches<?= $rotate ? ' feed-matches--rotate' : '' ?>"
  <?= $rotate ? 'data-match-rotate data-interval="4000"' : '' ?>
>
  <?php foreach ($matchPages as $pi => $page): ?>
    <div class="feed-match-slide<?= $pi === 0 ? ' is-active' : '' ?>" data-match-slide <?= $pi === 0 ? '' : 'hidden' ?>>
      <div class="feed-matches-page">
        <?php foreach ($page as $row): ?>
          <?php
            if ($matchIsResultado) {
                $match = [
                    'local' => $row['club_local'] ?? '',
                    'visita' => $row['club_visita'] ?? '',
                    'escudo_local' => $row['escudo_local'] ?? '',
                    'escudo_visita' => $row['escudo_visita'] ?? '',
                    'score' => $row['score'] ?? '',
                    'liga' => ($row['categoria'] ?? '') . $matchLigaSuffix,
                    'href' => app_url('/posiciones/sub-20'),
                ];
            } else {
                $match = [
                    'local' => $row['club_local'] ?? '',
                    'visita' => $row['club_visita'] ?? '',
                    'escudo_local' => $row['escudo_local'] ?? '',
                    'escudo_visita' => $row['escudo_visita'] ?? '',
                    'hora' => $row['cuando'] ?? 'vs',
                    'liga' => ($row['categoria'] ?? 'Sub-20') . $matchLigaSuffix,
                    'href' => app_url('/programacion/sub-20'),
                ];
            }
            require INCLUDES_PATH . '/partials/feed-match-card.php';
          ?>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
