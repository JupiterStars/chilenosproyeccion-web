<?php
/**
 * Selectores de categoría (y zona) solo en móvil.
 *
 * @var string $basePath   goleadores|posiciones|programacion
 * @var string $cat        slug activo
 * @var array  $pills      slug => etiqueta (array de categoria_etiqueta)
 * @var array  $gruposNav  secciones opcionales (zona/grupo)
 * @var string $grupoParam grupo activo
 * @var string|null $fechaParam  (solo programación)
 */
$basePath = $basePath ?? 'goleadores';
$cat = $cat ?? 'sub-20';
$pills = $pills ?? [];
$gruposNav = $gruposNav ?? [];
$grupoParam = $grupoParam ?? '';
$fechaParam = $fechaParam ?? null;

$buildUrl = static function (string $slug, string $grupo = '') use ($basePath, $fechaParam): string {
    $url = app_url('/' . $basePath . '/' . $slug);
    $q = [];
    if ($grupo !== '' && ($basePath === 'goleadores' || $basePath === 'posiciones')) {
        $q['grupo'] = $grupo;
    }
    if ($fechaParam && $basePath === 'programacion') {
        $q['fecha'] = $fechaParam;
    }
    if ($q) {
        $url .= '?' . http_build_query($q);
    }
    // Ancla al inicio del bloque de zona (JS corrige el offset sticky)
    if ($grupo !== '' && ($basePath === 'goleadores' || $basePath === 'posiciones')) {
        $url .= '#sec-' . rawurlencode($grupo);
    }
    return $url;
};
?>
<div class="mobile-cat-selects" data-mobile-cat-selects>
  <label class="mobile-cat-select">
    <span class="mobile-cat-select-label">Categoría</span>
    <select class="mobile-cat-select-input" data-nav-select aria-label="Elegir categoría">
      <?php foreach ($pills as $slug => $et): ?>
        <?php
          $label = is_array($et)
              ? trim(($et['nombre'] ?? '') . ' ' . ($et['apellido'] ?? ''))
              : (string) $et;
          if (is_array($et) && !empty($et['iniciales'])) {
              $label = $et['iniciales'] . ' · ' . $label;
          }
        ?>
        <option value="<?= e($buildUrl((string) $slug)) ?>" <?= $cat === $slug ? 'selected' : '' ?>>
          <?= e($label) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>

  <?php if ($gruposNav): ?>
    <label class="mobile-cat-select">
      <span class="mobile-cat-select-label">Zona / grupo</span>
      <select class="mobile-cat-select-input" data-nav-select aria-label="Elegir zona o grupo">
        <option value="<?= e($buildUrl($cat)) ?>" <?= $grupoParam === '' ? 'selected' : '' ?>>
          Todas las zonas
        </option>
        <?php foreach ($gruposNav as $sec): ?>
          <?php $key = (string) ($sec['key'] ?? ''); ?>
          <option
            value="<?= e($buildUrl($cat, $key)) ?>"
            <?= $grupoParam === $key ? 'selected' : '' ?>
          >
            <?= e((string) ($sec['corto'] ?? $sec['label'] ?? $key)) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
  <?php endif; ?>
</div>
