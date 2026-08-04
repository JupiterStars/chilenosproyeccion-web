<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Política de cookies | ChilenosProyección';
$metaDescription = 'Cómo usamos cookies en ChilenosProyección.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Política de cookies</h1></div>
    <p class="legal-updated">Última actualización: 3 de agosto de 2026 · ChilenosProyección · Chile</p>

    <h2>1. Qué son las cookies</h2>
    <p>Son pequeños archivos que el sitio guarda en tu navegador para recordar preferencias y, si lo permitís, para medir el uso del sitio o mostrar publicidad.</p>

    <h2>2. Qué usamos en este sitio</h2>
    <p>Hoy nos limitamos a lo siguiente:</p>
    <ul class="legal-list">
      <li><strong>Cookies necesarias:</strong> para que el sitio funcione bien (por ejemplo, recordar si preferís tema claro u oscuro y si ya respondiste al aviso de cookies).</li>
      <li><strong>Cookies opcionales (solo si aceptás):</strong> para estadísticas de visitas y, cuando esté activa, publicidad en el sitio. Si elegís “Solo necesarias”, no se usan con ese fin.</li>
    </ul>

    <h2>3. Cómo elegís</h2>
    <p>Al entrar aparece un aviso. Podés <strong>aceptar</strong> las cookies opcionales o quedarte solo con las <strong>necesarias</strong>. Si más adelante querés cambiar la elección, borrá los datos de este sitio desde la configuración de tu navegador y volvé a entrar.</p>

    <h2>4. Cómo desactivarlas en el navegador</h2>
    <p>También podés bloquear o borrar cookies desde las opciones de tu navegador (Chrome, Firefox, Safari, Edge, etc.). Si bloqueás todas las cookies, es posible que algunas preferencias del sitio no se guarden.</p>

    <h2>5. Más información</h2>
    <p>Sobre datos personales en general, revisá la <a href="<?= e(app_url('/legales/politica-privacidad')) ?>">política de privacidad</a>. Para consultas: <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a>.</p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
