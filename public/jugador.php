<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$slug = trim($_GET['slug'] ?? '');
$jugador = JugadorModel::porSlug($slug);
if (!$jugador) {
    abort_404('Jugador no encontrado');
}

$edad = JugadorModel::edad($jugador['fecha_nacimiento'] ?? null);
$goles = (int) ($jugador['goles'] ?? 0);
$pj = (int) ($jugador['partidos'] ?? 0);
$ast = (int) ($jugador['asistencias'] ?? 0);
$prom = $pj > 0 ? round($goles / $pj, 2) : 0;
$noticias = !empty($jugador['id']) ? JugadorModel::noticias((int) $jugador['id']) : [];
$foto = $jugador['foto_url'] ?? null;
$clubSlug = $jugador['club_slug'] ?? null;

$pageTitle = ($jugador['nombre'] ?? 'Jugador') . ' | ChilenosProyección';
$metaDescription = 'Perfil de ' . ($jugador['nombre'] ?? 'jugador') . ' — ' . ($jugador['club_nombre'] ?? '') . '. Stats y noticias de formativas.';

require INCLUDES_PATH . '/header.php';
?>
<section class="section entity-hero">
  <div class="container entity-hero-inner">
    <div class="entity-crest entity-photo">
      <?php if ($foto): ?>
        <img src="<?= e(app_url($foto)) ?>" alt="<?= e($jugador['nombre'] ?? '') ?>" width="120" height="120" />
      <?php else: ?>
        <div class="photo-fallback" aria-hidden="true"><?= e(mb_substr($jugador['nombre'] ?? '?', 0, 1)) ?></div>
      <?php endif; ?>
    </div>
    <div class="entity-meta">
      <p class="entity-kicker"><?= e($jugador['posicion'] ?? 'Jugador') ?><?php if (!empty($jugador['categoria_nombre'])): ?> · <?= e($jugador['categoria_nombre']) ?><?php endif; ?></p>
      <h1><?= e($jugador['nombre'] ?? '') ?></h1>
      <p class="page-intro entity-club-line" style="margin-bottom:0">
        Club:
        <?php
          $clubNom = (string) ($jugador['club_nombre'] ?? '—');
          $escJ = (string) ($jugador['escudo_url'] ?? '');
          if ($escJ === '' && $clubSlug) {
              $escJ = club_escudo_url($clubSlug);
          }
          echo render_entity_with_crest($clubNom, $clubSlug, $escJ !== '' ? $escJ : null, [
              'size' => 28,
              'class' => 'club-cell--inline',
          ]);
        ?>
        <?php if ($edad !== null): ?> · <?= $edad ?> años<?php endif; ?>
      </p>
    </div>
  </div>
</section>

<section class="section section-tight">
  <div class="container">
    <div class="stat-grid">
      <div class="stat-card"><span class="stat-value"><?= $goles ?></span><span class="stat-label">Goles</span></div>
      <div class="stat-card"><span class="stat-value"><?= $pj ?></span><span class="stat-label">Partidos</span></div>
      <div class="stat-card"><span class="stat-value"><?= $ast ?></span><span class="stat-label">Asistencias</span></div>
      <div class="stat-card"><span class="stat-value"><?= $prom ?></span><span class="stat-label">Prom. gol</span></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head"><h2>Noticias relacionadas</h2></div>
    <?php if (!$noticias): ?>
      <div class="empty-state">Sin noticias asociadas todavía.</div>
    <?php else: ?>
      <div class="card-grid card-grid-3">
        <?php foreach ($noticias as $n): ?>
          <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
