<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$cat = trim($_GET['categoria'] ?? 'sub-20') ?: 'sub-20';
$filas = PosicionModel::porCategoria($cat);
$torneo = $filas[0]['torneo'] ?? strtoupper($cat);

$pageTitle = 'Posiciones ' . strtoupper($cat) . ' | ChilenosProyección';
$metaDescription = 'Tabla de posiciones del fútbol joven chileno — ' . $torneo;
$navActive = 'posiciones';

$cats = array_merge(
    array_map(static fn ($c) => $c['slug'], categorias_futbol_joven()),
    ['sub-20-regional', 'sub-18-regional']
);

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <h1>Posiciones</h1>
    </div>
    <p class="page-intro"><?= e($torneo) ?>. Orden: puntos, diferencia de goles y goles a favor.</p>

    <div class="cat-pills" role="navigation" aria-label="Categorías">
      <?php foreach (['sub-20' => 'Sub-20', 'sub-18' => 'Sub-18', 'sub-16' => 'Sub-16', 'sub-15' => 'Sub-15', 'sub-20-regional' => 'Sub-20 Reg.'] as $slug => $label): ?>
        <a class="pill <?= $cat === $slug ? 'is-active' : '' ?>" href="<?= e(app_url('/posiciones/' . $slug)) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="table-wrap table-standings">
      <table class="data-table">
        <thead>
          <tr>
            <th class="col-pos">#</th>
            <th>Club</th>
            <th title="Partidos jugados">PJ</th>
            <th title="Ganados">PG</th>
            <th title="Empatados">PE</th>
            <th title="Perdidos">PP</th>
            <th title="Goles a favor">GF</th>
            <th title="Goles en contra">GC</th>
            <th title="Diferencia de goles">DG</th>
            <th title="Puntos">Pts</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filas as $i => $f): ?>
            <?php
              $pos = $i + 1;
              $slugClub = $f['club_slug'] ?? null;
              $esc = $f['escudo_url'] ?? ($slugClub ? '/assets/escudos/' . $slugClub . '.png' : null);
              $dg = (int) ($f['dg'] ?? ((int) ($f['gf'] ?? 0) - (int) ($f['gc'] ?? 0)));
            ?>
            <tr class="<?= $pos <= 3 ? 'row-top' : '' ?>">
              <td class="col-pos"><span class="pos-badge"><?= $pos ?></span></td>
              <td>
                <div class="club-cell">
                  <?php if ($esc): ?>
                    <img class="club-mini" src="<?= e(app_url($esc)) ?>" alt="" width="28" height="28" loading="lazy" onerror="this.style.display='none'" />
                  <?php endif; ?>
                  <?php if ($slugClub): ?>
                    <a href="<?= e(app_url('/club/' . $slugClub)) ?>"><?= e($f['club'] ?? '') ?></a>
                  <?php else: ?>
                    <?= e($f['club'] ?? '') ?>
                  <?php endif; ?>
                </div>
              </td>
              <td><?= (int) ($f['pj'] ?? 0) ?></td>
              <td><?= (int) ($f['pg'] ?? 0) ?></td>
              <td><?= (int) ($f['pe'] ?? 0) ?></td>
              <td><?= (int) ($f['pp'] ?? 0) ?></td>
              <td><?= (int) ($f['gf'] ?? 0) ?></td>
              <td><?= (int) ($f['gc'] ?? 0) ?></td>
              <td class="<?= $dg > 0 ? 'pos-dg' : ($dg < 0 ? 'neg-dg' : '') ?>"><?= $dg > 0 ? '+' : '' ?><?= $dg ?></td>
              <td class="col-pts"><strong><?= (int) ($f['pts'] ?? 0) ?></strong></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
