<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$cat = strtolower(trim($_GET['categoria'] ?? 'sub-20'));
$filas = ProgramacionModel::porCategoria($cat ?: null);
$nombreCat = strtoupper(str_replace('-', ' ', $cat));

$pageTitle = "Programación {$nombreCat} | ChilenosProyección";
$metaDescription = "Próximos partidos {$nombreCat}.";

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <h1>Programación <?= e($nombreCat) ?></h1>
      <div class="chip-row">
        <?php foreach (categorias_futbol_joven() as $c): ?>
          <a class="chip <?= $c['slug'] === $cat ? 'is-active' : '' ?>" href="<?= e(app_url('/programacion/' . $c['slug'])) ?>"><?= e($c['nombre']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr><th>Fecha</th><th>Hora</th><th>Local</th><th>Visita</th><th>Recinto</th></tr>
        </thead>
        <tbody>
          <?php foreach ($filas as $r): ?>
            <tr>
              <td><?= e($r['fecha'] ?? '') ?></td>
              <td><?= e(substr((string) ($r['hora'] ?? ''), 0, 5)) ?></td>
              <td><?= e($r['local']) ?></td>
              <td><?= e($r['visita']) ?></td>
              <td><?= e($r['recinto'] ?? '—') ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$filas): ?>
            <tr><td colspan="5">Sin partidos programados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
