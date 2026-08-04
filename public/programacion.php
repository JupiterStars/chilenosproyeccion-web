<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$cat = strtolower(trim($_GET['categoria'] ?? 'sub-20'));
$divisionParam = strtolower(trim($_GET['division'] ?? ''));
$fechaParam = trim($_GET['fecha'] ?? '');

// Si la categoría es una división (nacional/regional/infantil), mapear
$divisiones = nav_divisiones();
$divisionKeys = array_keys($divisiones);
if (in_array($cat, $divisionKeys, true)) {
    $divisionParam = $cat;
    $first = $divisiones[$cat]['items'][0]['slug'] ?? 'sub-20';
    $cat = $first;
}

// Inferir división desde categoría
if ($divisionParam === '' || !isset($divisiones[$divisionParam])) {
    if (str_contains($cat, 'regional')) {
        $divisionParam = 'regional';
    } elseif (str_contains($cat, 'infantil')) {
        $divisionParam = 'infantil';
    } else {
        $divisionParam = 'nacional';
    }
}

$itemsDivision = $divisiones[$divisionParam]['items'] ?? [];
// Validar que cat pertenece a la división actual; si no, usar el primero
$slugsDiv = array_column($itemsDivision, 'slug');
if ($slugsDiv && !in_array($cat, $slugsDiv, true)) {
    $cat = $slugsDiv[0];
}

$fechasConPartidos = ProgramacionModel::fechasDisponibles($cat ?: null);
$fechasConPartidosSet = array_fill_keys($fechasConPartidos, true);
// Incluir fechas “futuras” sin partidos (jornadas confirmadas sin horario)
$todasFechas = ProgramacionModel::enriquecerFechasCalendario($fechasConPartidos, 12);

$fechaSeleccionada = $fechaParam;
if ($fechaSeleccionada === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaSeleccionada)) {
    // Preferir la primera fecha >= hoy; si no hay, la primera del listado
    $hoy = date('Y-m-d');
    $fechaSeleccionada = $todasFechas[0] ?? $hoy;
    foreach ($todasFechas as $f) {
        if ($f >= $hoy) {
            $fechaSeleccionada = $f;
            break;
        }
    }
}

$filas = ProgramacionModel::porCategoriaYFecha($cat ?: null, $fechaSeleccionada);
$etiqueta = categoria_etiqueta($cat);
$nombreCat = $etiqueta['titulo'] !== '' ? $etiqueta['titulo'] : strtoupper(str_replace('-', ' ', $cat));

// Navegación prev/next entre fechas
$idxFecha = array_search($fechaSeleccionada, $todasFechas, true);
if ($idxFecha === false) {
    // Fecha pedida no está en el calendario: insertarla para poder navegar
    $todasFechas[] = $fechaSeleccionada;
    sort($todasFechas);
    $idxFecha = array_search($fechaSeleccionada, $todasFechas, true);
}
$idxFecha = is_int($idxFecha) ? $idxFecha : 0;
$fechaPrev = $idxFecha > 0 ? $todasFechas[$idxFecha - 1] : null;
$fechaNext = ($idxFecha < count($todasFechas) - 1) ? $todasFechas[$idxFecha + 1] : null;

$baseProg = static function (string $slug, ?string $fecha = null) use ($divisionParam): string {
    $url = app_url('/programacion/' . $slug);
    $q = [];
    if ($fecha) {
        $q['fecha'] = $fecha;
    }
    // division solo si hace falta distinguir
    if ($q) {
        $url .= '?' . http_build_query($q);
    }
    return $url;
};

// Fecha legible (formato chileno dd/mm/yyyy)
$fechaLabel = format_fecha_cl($fechaSeleccionada);
try {
    $dt = new DateTimeImmutable($fechaSeleccionada);
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $fechaLabel = $dias[(int) $dt->format('w')] . ' ' . format_fecha_cl($fechaSeleccionada);
} catch (Throwable $e) {
    // keep format_fecha_cl
}

$pageTitle = "Programación {$nombreCat} | ChilenosProyección";
$metaDescription = "Próximos partidos {$nombreCat} — programación por fecha y categoría.";
$navActive = 'programacion';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <h1>Programación</h1>
      <p class="page-intro" style="margin:0">
        <span class="cat-full"><?= e($nombreCat) ?></span>
        <span class="cat-ini" title="<?= e($nombreCat) ?>"><?= e($etiqueta['iniciales']) ?></span>
      </p>
    </div>

    <div class="subnav-sticky" data-subnav-sticky>
      <?= nav_misma_categoria($cat, 'programacion') ?>

      <?php
        // Select móvil: todas las categorías de fútbol joven
        $basePath = 'programacion';
        $pills = [];
        foreach ($divisiones as $div) {
            foreach ($div['items'] as $cItem) {
                $pills[$cItem['slug']] = categoria_etiqueta($cItem['slug']);
            }
        }
        $gruposNav = [];
        $grupoParam = '';
        $fechaParam = $fechaSeleccionada;
        require INCLUDES_PATH . '/partials/mobile-cat-select.php';
      ?>

      <!-- Divisiones: Nacional / Regional / Infantil -->
      <div class="div-tabs desktop-only-nav" role="navigation" aria-label="Divisiones">
        <?php foreach ($divisiones as $dKey => $div): ?>
          <?php
            $firstSlug = $div['items'][0]['slug'] ?? 'sub-20';
            $href = $baseProg($firstSlug, $fechaSeleccionada);
          ?>
          <a class="div-tab <?= $divisionParam === $dKey ? 'is-active' : '' ?>" href="<?= e($href) ?>">
            <?= e(str_replace('Campeonato ', '', $div['label'] ?? $dKey)) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Categorías de la división -->
      <div class="chip-row chip-row--prog cat-pills--scroll desktop-only-nav" role="navigation" aria-label="Categorías">
        <?php foreach ($itemsDivision as $c): ?>
          <a
            class="chip <?= $c['slug'] === $cat ? 'is-active' : '' ?>"
            href="<?= e($baseProg($c['slug'], $fechaSeleccionada)) ?>"
          ><?= e($c['nombre']) ?></a>
        <?php endforeach; ?>
      </div>

      <!-- Navegación por fechas -->
      <div class="fecha-nav" role="navigation" aria-label="Fechas de partidos">
        <?php if ($fechaPrev): ?>
          <a class="fecha-nav-btn" href="<?= e($baseProg($cat, $fechaPrev)) ?>" aria-label="Fecha anterior">
            <span aria-hidden="true">‹</span> Anterior
          </a>
        <?php else: ?>
          <span class="fecha-nav-btn is-disabled" aria-disabled="true"><span aria-hidden="true">‹</span> Anterior</span>
        <?php endif; ?>

        <div class="fecha-nav-current">
          <strong><?= e($fechaLabel) ?></strong>
          <span class="fecha-nav-meta">
            Fecha <?= (int) $idxFecha + 1 ?> de <?= count($todasFechas) ?: 1 ?>
            <?php if (!$filas): ?> · sin horario aún<?php endif; ?>
          </span>
        </div>

        <?php if ($fechaNext): ?>
          <a class="fecha-nav-btn" href="<?= e($baseProg($cat, $fechaNext)) ?>" aria-label="Fecha siguiente">
            Siguiente <span aria-hidden="true">›</span>
          </a>
        <?php else: ?>
          <span class="fecha-nav-btn is-disabled" aria-disabled="true">Siguiente <span aria-hidden="true">›</span></span>
        <?php endif; ?>
      </div>

      <?php if (count($todasFechas) > 1): ?>
        <div class="fecha-pills cat-pills--scroll" role="list" aria-label="Todas las fechas">
          <?php foreach ($todasFechas as $i => $f): ?>
            <?php
              $hasMatches = isset($fechasConPartidosSet[$f]);
              $label = format_fecha_cl($f);
              $labelCorto = $label;
              try {
                  $d = new DateTimeImmutable($f);
                  $labelCorto = $d->format('d/m');
              } catch (Throwable $e) {
              }
            ?>
            <a
              role="listitem"
              class="fecha-pill <?= $f === $fechaSeleccionada ? 'is-active' : '' ?> <?= $hasMatches ? '' : 'is-empty' ?>"
              href="<?= e($baseProg($cat, $f)) ?>"
              title="<?= e($label) ?><?= $hasMatches ? '' : ' (enfrentamientos pendientes de horario)' ?>"
            >
              <span class="fecha-pill-n">F<?= $i + 1 ?></span>
              <span class="fecha-pill-d"><?= e($labelCorto) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="table-wrap table-wrap--fit">
      <table class="data-table data-table--programacion data-table--fit">
        <thead>
          <tr>
            <th scope="col">Fecha</th>
            <th scope="col" class="col-hora">Hora</th>
            <th scope="col">Local</th>
            <th scope="col">Visita</th>
            <th scope="col">Recinto</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filas as $r): ?>
            <?php
              $lName = (string) ($r['local'] ?? '');
              $vName = (string) ($r['visita'] ?? '');
              $lSlug = (string) ($r['club_local_slug'] ?? '');
              $vSlug = (string) ($r['club_visita_slug'] ?? '');
              $escL = (string) ($r['escudo_local'] ?? '');
              $escV = (string) ($r['escudo_visita'] ?? '');
              $hora = (string) ($r['hora'] ?? '');
              $horaShort = $hora !== '' ? substr($hora, 0, 5) : '—';
              $recinto = (string) ($r['recinto'] ?? '');
              if ($recinto === '' || strtolower($recinto) === 'por definir') {
                  $recinto = 'Por definir';
              }
            ?>
            <tr>
              <td><?= e(format_fecha_cl((string) ($r['fecha'] ?? $fechaSeleccionada))) ?></td>
              <td><?= e($horaShort) ?></td>
              <td>
                <?= render_entity_with_crest($lName, $lSlug !== '' ? $lSlug : null, $escL !== '' ? $escL : null, [
                    'size' => 28,
                    'class' => 'club-cell--table',
                ]) ?>
              </td>
              <td>
                <?= render_entity_with_crest($vName, $vSlug !== '' ? $vSlug : null, $escV !== '' ? $escV : null, [
                    'size' => 28,
                    'class' => 'club-cell--table',
                ]) ?>
              </td>
              <td><?= e($recinto) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$filas): ?>
            <tr>
              <td colspan="5" class="prog-empty">
                Enfrentamientos de esta fecha aún sin horario ni recinto.
                Los partidos se publican cuando la programación de la semana quede confirmada.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
