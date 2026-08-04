<?php
/**
 * Card de partido estilo app
 * @var array $match keys: local, visita, escudo_local, escudo_visita, hora|score, liga, extra
 */
$m = $match ?? [];
$local = (string) ($m['local'] ?? '');
$visita = (string) ($m['visita'] ?? '');
$escL = (string) ($m['escudo_local'] ?? '');
$escV = (string) ($m['escudo_visita'] ?? '');
$lSlug = (string) ($m['club_local_slug'] ?? '');
$vSlug = (string) ($m['club_visita_slug'] ?? '');
if ($escL === '' && ($lSlug !== '' || $local !== '')) {
    $escL = club_escudo_url($lSlug !== '' ? $lSlug : slugify($local));
}
if ($escV === '' && ($vSlug !== '' || $visita !== '')) {
    $escV = club_escudo_url($vSlug !== '' ? $vSlug : slugify($visita));
}
$centro = $m['score'] ?? $m['hora'] ?? 'vs';
$liga = $m['liga'] ?? 'Formativas ANFP';
$href = $m['href'] ?? app_url('/programacion/sub-20');
?>
<article class="feed-match">
  <a class="feed-match-link" href="<?= e($href) ?>">
    <div class="feed-match-liga"><?= e($liga) ?></div>
    <div class="feed-match-row">
      <div class="feed-match-team">
        <?php if ($escL): ?>
          <img src="<?= e(app_url($escL)) ?>" alt="<?= e($local) ?>" width="36" height="36" loading="lazy" onerror="this.style.opacity=.3" />
        <?php endif; ?>
        <span><?= e($local) ?></span>
      </div>
      <div class="feed-match-center">
        <span class="feed-match-score"><?= e($centro) ?></span>
      </div>
      <div class="feed-match-team feed-match-team--right">
        <span><?= e($visita) ?></span>
        <?php if ($escV): ?>
          <img src="<?= e(app_url($escV)) ?>" alt="<?= e($visita) ?>" width="36" height="36" loading="lazy" onerror="this.style.opacity=.3" />
        <?php endif; ?>
      </div>
    </div>
  </a>
</article>
