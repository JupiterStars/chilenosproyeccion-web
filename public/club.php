<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$slug = trim($_GET['slug'] ?? '');
$club = ClubModel::porSlug($slug);
if (!$club) {
    abort_404('Club no encontrado');
}

$escudo = ClubModel::escudoUrl($club);
$clubId = (int) ($club['id'] ?? 0);
$noticias = $clubId ? ClubModel::noticias($clubId) : [];
$plantel = $clubId ? ClubModel::plantel($clubId) : [];
$proximo = $clubId ? ClubModel::proximoPartido($clubId) : null;

$pageTitle = ($club['nombre'] ?? 'Club') . ' | ChilenosProyección';
$metaDescription = 'Ficha de ' . ($club['nombre'] ?? 'club') . ' — región ' . ($club['region'] ?? 'Chile') . '. Noticias, plantel y programación formativas.';
$ogImage = app_url($escudo);

require INCLUDES_PATH . '/header.php';
?>
<section class="section entity-hero">
  <div class="container entity-hero-inner">
    <div class="entity-crest">
      <img src="<?= e(app_url($escudo)) ?>" alt="Escudo <?= e($club['nombre']) ?>" width="120" height="120" loading="eager" />
    </div>
    <div class="entity-meta">
      <p class="entity-kicker"><?= e(ucfirst((string) ($club['division'] ?? 'Club'))) ?> · ANFP formativas</p>
      <h1><?= e($club['nombre']) ?></h1>
      <p class="page-intro" style="margin-bottom:0">Región: <strong><?= e($club['region'] ?? '—') ?></strong></p>
    </div>
  </div>
</section>

<?php if ($proximo): ?>
<section class="section section-tight">
  <div class="container">
    <div class="match-card">
      <div class="match-card-label">Próximo partido</div>
      <div class="match-card-teams">
        <span><?= e($proximo['local'] ?? '') ?></span>
        <span class="match-vs">vs</span>
        <span><?= e($proximo['visita'] ?? '') ?></span>
      </div>
      <p class="match-card-meta">
        <?= e(format_fecha(($proximo['fecha'] ?? '') . ' ' . ($proximo['hora'] ?? '00:00:00'))) ?>
        <?php if (!empty($proximo['recinto'])): ?> · <?= e($proximo['recinto']) ?><?php endif; ?>
      </p>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container layout-split">
    <div>
      <div class="section-head"><h2>Noticias del club</h2></div>
      <?php if (!$noticias): ?>
        <div class="empty-state">Pronto más noticias de este club.</div>
      <?php else: ?>
        <div class="card-grid card-grid-2">
          <?php foreach ($noticias as $n): ?>
            <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <aside>
      <div class="section-head"><h2>Plantel</h2></div>
      <?php if (!$plantel): ?>
        <div class="empty-state">Plantel en actualización.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="data-table plantel-table">
            <thead>
              <tr><th>Jugador</th><th>Pos.</th><th>G</th><th>PJ</th></tr>
            </thead>
            <tbody>
              <?php foreach ($plantel as $j): ?>
                <tr>
                  <td>
                    <a href="<?= e(app_url('/jugador/' . ($j['slug'] ?? ''))) ?>"><?= e($j['nombre'] ?? '') ?></a>
                  </td>
                  <td><?= e($j['posicion'] ?? '—') ?></td>
                  <td><?= (int) ($j['goles'] ?? 0) ?></td>
                  <td><?= (int) ($j['partidos'] ?? 0) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </aside>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
