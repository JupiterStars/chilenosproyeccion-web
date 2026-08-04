<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$cat = strtolower(trim($_GET['categoria'] ?? 'sub-20')) ?: 'sub-20';
$etiqueta = categoria_etiqueta($cat);
$reglas = reglas_clasificacion($cat);
$secciones = categoria_secciones($cat);

// Pills de navegación con apellido + iniciales en móvil
$pills = [
    'sub-20' => categoria_etiqueta('sub-20'),
    'sub-18' => categoria_etiqueta('sub-18'),
    'sub-16' => categoria_etiqueta('sub-16'),
    'sub-15' => categoria_etiqueta('sub-15'),
    'sub-20-regional' => categoria_etiqueta('sub-20-regional'),
    'sub-18-regional' => categoria_etiqueta('sub-18-regional'),
    'sub-16-regional' => categoria_etiqueta('sub-16-regional'),
    'sub-15-regional' => categoria_etiqueta('sub-15-regional'),
    'sub-14-infantil' => categoria_etiqueta('sub-14-infantil'),
    'sub-13-infantil' => categoria_etiqueta('sub-13-infantil'),
    'sub-12-infantil' => categoria_etiqueta('sub-12-infantil'),
    'sub-11-infantil' => categoria_etiqueta('sub-11-infantil'),
];

// Cargar filas: si hay varias secciones, una tabla por sección (planteles oficiales)
$bloques = [];
if (count($secciones) > 1 || (isset($secciones[0]['key']) && $secciones[0]['key'] !== 'unica')) {
    foreach ($secciones as $sec) {
        $labelTorneo = $etiqueta['titulo'] . ' · ' . $sec['label'];
        $filasDb = PosicionModel::porCategoria($cat);
        $filas = posiciones_demo_desde_equipos($sec['equipos'] ?? [], $labelTorneo);
        if ($filasDb && count($secciones) === 1) {
            $filas = $filasDb;
        }
        $bloques[] = [
            'sec' => $sec,
            'filas' => $filas,
            'torneo' => $labelTorneo,
        ];
    }
} else {
    $filas = PosicionModel::porCategoria($cat);
    $torneo = $filas[0]['torneo'] ?? $etiqueta['titulo'];
    if (!$filas || (($filas[0]['torneo'] ?? '') === 'Demo Sub-20' && ($secciones[0]['equipos'] ?? []))) {
        $filas = posiciones_demo_desde_equipos($secciones[0]['equipos'] ?? [], $etiqueta['titulo']);
        $torneo = $etiqueta['titulo'];
    }
    $bloques[] = [
        'sec' => $secciones[0] ?? ['key' => 'unica', 'label' => 'Tabla general', 'iniciales' => 'GEN', 'corto' => 'General'],
        'filas' => $filas,
        'torneo' => $torneo,
    ];
}

$multi = count($bloques) > 1;

$pageTitle = 'Posiciones ' . $etiqueta['titulo'] . ' | ChilenosProyección';
$metaDescription = 'Tabla de posiciones — ' . $etiqueta['titulo'];
$navActive = 'posiciones';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <h1>Posiciones</h1>
    </div>
    <p class="page-intro">
      <span class="cat-full"><?= e($etiqueta['titulo']) ?></span>
      <span class="cat-ini" title="<?= e($etiqueta['titulo']) ?>"><?= e($etiqueta['iniciales']) ?></span>
      · Orden: puntos, diferencia de goles y goles a favor.
    </p>
    <?php if (($reglas['descripcion'] ?? '') !== ''): ?>
      <p class="reglas-torneo" role="note">
        <strong>Clasificación:</strong> <?= e($reglas['descripcion']) ?>
      </p>
    <?php endif; ?>

    <div class="subnav-sticky" data-subnav-sticky>
      <?= nav_misma_categoria($cat, 'posiciones') ?>

      <div class="cat-pills cat-pills--scroll" role="navigation" aria-label="Categorías">
        <?php foreach ($pills as $slug => $et): ?>
          <a
            class="pill <?= $cat === $slug ? 'is-active' : '' ?>"
            href="<?= e(app_url('/posiciones/' . $slug)) ?>"
            title="<?= e($et['titulo']) ?>"
          >
            <span class="pill-full"><?= e($et['nombre']) ?> <small><?= e($et['apellido']) ?></small></span>
            <span class="pill-ini"><?= e($et['iniciales']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($multi): ?>
        <div class="grupo-jump" role="navigation" aria-label="Ir a grupo">
          <?php foreach ($bloques as $bloque): ?>
            <?php $sec = $bloque['sec']; ?>
            <a class="grupo-jump-btn" href="#sec-<?= e($sec['key'] ?? 'unica') ?>">
              <strong><?= e($sec['iniciales'] ?? '') ?></strong>
              <span><?= e($sec['corto'] ?? $sec['label'] ?? '') ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php foreach ($bloques as $bloque): ?>
      <?php
        $sec = $bloque['sec'];
        $filas = $bloque['filas'];
        $badgeLabel = (string) ($reglas['badge'] ?? 'Clasifica');
      ?>
      <div class="standings-block" id="sec-<?= e($sec['key'] ?? 'unica') ?>">
        <?php if ($multi): ?>
          <div class="standings-block-head">
            <h2>
              <span class="sec-full"><?= e($sec['label']) ?></span>
              <span class="sec-ini badge-ini" title="<?= e($sec['label']) ?>"><?= e($sec['iniciales'] ?? '') ?></span>
            </h2>
            <p class="standings-block-meta">
              <?= e($etiqueta['titulo']) ?> · <?= e($sec['corto'] ?? $sec['label']) ?>
              <?php if (($reglas['tipo'] ?? '') === 'regional'): ?>
                · Top 2 a playoff
              <?php elseif (($reglas['tipo'] ?? '') === 'infantil_11_12'): ?>
                · 1° clasifica
              <?php endif; ?>
            </p>
          </div>
        <?php endif; ?>

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
                <th title="Promedio de puntos (Pts ÷ PJ)">Prom.</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($filas as $i => $f): ?>
                <?php
                  $pos = $i + 1;
                  $slugClub = $f['club_slug'] ?? null;
                  $esc = $f['escudo_url'] ?? ($slugClub ? club_escudo_url($slugClub) : null);
                  $dg = (int) ($f['dg'] ?? ((int) ($f['gf'] ?? 0) - (int) ($f['gc'] ?? 0)));
                  $pj = (int) ($f['pj'] ?? 0);
                  $pts = (int) ($f['pts'] ?? 0);
                  $prom = $pj > 0 ? round($pts / $pj, 2) : 0.0;
                  $clasifica = fila_clasifica($cat, $pos);
                  // Solo verde para clasificados — sin naranja en el resto (evita confusión)
                  $rowClass = $clasifica ? 'table-row--clasifica' : '';
                ?>
                <tr class="<?= e($rowClass) ?>">
                  <td class="col-pos"><span class="pos-badge"><?= $pos ?></span></td>
                  <td>
                    <div class="club-cell club-cell--table">
                      <?php if ($esc): ?>
                        <img class="club-mini" src="<?= e(app_url($esc)) ?>" alt="<?= e($f['club'] ?? '') ?>" width="28" height="28" loading="lazy" onerror="this.style.display='none'" />
                      <?php endif; ?>
                      <?php if ($slugClub): ?>
                        <a class="club-cell-name" href="<?= e(app_url('/club/' . $slugClub)) ?>"><?= e($f['club'] ?? '') ?></a>
                      <?php else: ?>
                        <span class="club-cell-name"><?= e($f['club'] ?? '') ?></span>
                      <?php endif; ?>
                      <?php if ($clasifica): ?>
                        <span class="badge-clasifica"><?= e($badgeLabel) ?></span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td><?= $pj ?></td>
                  <td><?= (int) ($f['pg'] ?? 0) ?></td>
                  <td><?= (int) ($f['pe'] ?? 0) ?></td>
                  <td><?= (int) ($f['pp'] ?? 0) ?></td>
                  <td><?= (int) ($f['gf'] ?? 0) ?></td>
                  <td><?= (int) ($f['gc'] ?? 0) ?></td>
                  <td class="<?= $dg > 0 ? 'pos-dg' : ($dg < 0 ? 'neg-dg' : '') ?>"><?= $dg > 0 ? '+' : '' ?><?= $dg ?></td>
                  <td class="col-pts"><strong><?= $pts ?></strong></td>
                  <td class="col-prom"><?= number_format($prom, 2, '.', '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
