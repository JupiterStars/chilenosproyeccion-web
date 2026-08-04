<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$cat = trim($_GET['categoria'] ?? 'sub-20') ?: 'sub-20';
$filas = GoleadorModel::porCategoria($cat, 10);
$meta = GoleadorModel::metaCategoria($cat);
$torneo = $meta['torneo'] !== '' ? $meta['torneo'] : ($filas[0]['torneo'] ?? ('Goleadores ' . strtoupper($cat)));
$fuente = $meta['fuente'] ?? '';
$showPj = false;
foreach ($filas as $f) {
    if ((int) ($f['partidos'] ?? 0) > 0) {
        $showPj = true;
        break;
    }
}

$pageTitle = 'Goleadores ' . strtoupper($cat) . ' | ChilenosProyección';
$metaDescription = 'Tabla de goleadores — ' . $torneo;
$navActive = 'goleadores';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Goleadores</h1></div>
    <p class="page-intro">
      <?= e($torneo) ?>.
      <?php if ($fuente): ?>
        <span class="meta">Fuente: <?= e($fuente) ?>.</span>
      <?php endif; ?>
    </p>

    <div class="cat-pills" role="navigation" aria-label="Categorías">
      <?php foreach (['sub-20' => 'Sub-20', 'sub-18' => 'Sub-18', 'sub-16' => 'Sub-16', 'sub-15' => 'Sub-15'] as $slug => $label): ?>
        <a class="pill <?= $cat === $slug ? 'is-active' : '' ?>" href="<?= e(app_url('/goleadores/' . $slug)) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th class="col-pos">#</th>
            <th>Jugador</th>
            <th class="col-club-ico" title="Club">Club</th>
            <th>Goles</th>
            <?php if ($showPj): ?><th>PJ</th><th>Prom.</th><?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filas as $i => $f): ?>
            <?php
              $pos = $i + 1;
              $jSlug = $f['jugador_slug'] ?? null;
              $cSlug = $f['club_slug'] ?? null;
              $clubNom = $f['club'] ?? '';
              $esc = $f['escudo_url'] ?? ($cSlug ? '/assets/escudos/' . $cSlug . '.png' : '');
              $pj = (int) ($f['partidos'] ?? 0);
              $g = (int) ($f['goles'] ?? 0);
              $prom = $f['promedio'] ?? ($pj > 0 ? round($g / $pj, 2) : 0);
            ?>
            <tr class="<?= $pos <= 3 ? 'row-top' : '' ?>">
              <td class="col-pos"><span class="pos-badge"><?= $pos ?></span></td>
              <td>
                <?php if ($jSlug): ?>
                  <a href="<?= e(app_url('/jugador/' . $jSlug)) ?>"><strong><?= e($f['jugador'] ?? '') ?></strong></a>
                <?php else: ?>
                  <strong><?= e($f['jugador'] ?? '') ?></strong>
                <?php endif; ?>
              </td>
              <td class="cell-club-ico">
                <?php if ($esc): ?>
                  <a
                    class="club-crest-only"
                    href="<?= e($cSlug ? app_url('/club/' . $cSlug) : '#') ?>"
                    title="<?= e($clubNom) ?>"
                    aria-label="<?= e($clubNom) ?>"
                  >
                    <img
                      src="<?= e(app_url($esc)) ?>"
                      alt="<?= e($clubNom) ?>"
                      width="32"
                      height="32"
                      loading="lazy"
                      onerror="this.closest('a').classList.add('is-missing')"
                    />
                  </a>
                <?php else: ?>
                  <span class="club-crest-fallback" title="<?= e($clubNom) ?>"><?= e(mb_substr($clubNom, 0, 1)) ?></span>
                <?php endif; ?>
              </td>
              <td class="col-pts"><strong><?= $g ?></strong></td>
              <?php if ($showPj): ?>
                <td><?= $pj ?></td>
                <td><?= e((string) $prom) ?></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if ($fuente): ?>
      <p class="page-intro" style="margin-top:1rem;font-size:.85rem">Top 10 · orden por goles (ANFP).</p>
    <?php endif; ?>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
