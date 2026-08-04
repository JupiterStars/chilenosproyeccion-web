<?php
declare(strict_types=1);
// incluido vía legales.php o router directo
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Contacto legal | ChilenosProyección';
$metaDescription = 'Contacto legal de ChilenosProyección — legislación chilena.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Contacto legal</h1></div>
    <p class="legal-updated">Última actualización: 3 de agosto de 2026 · Responsable del sitio: ChilenosProyección · Chile · <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a></p>

    <h2>Canal oficial</h2>
    <p>Para ejercer derechos ARCO, reportar infracciones de propiedad intelectual, solicitar rectificaciones o plantear asuntos legales relacionados con el sitio:</p>
    <ul class="legal-list">
      <li><strong>Medio:</strong> ChilenosProyección</li>
      <li><strong>País:</strong> Chile</li>
      <li><strong>Formulario general:</strong> <a href="<?= e(app_url("/contacto")) ?>">/contacto</a> (indicar asunto “Legal / Privacidad”)</li>
      <li><strong>Correo (actualizar en producción):</strong> legal@chilenosproyeccion.cl</li>
    </ul>
    <h2>Qué incluir en tu mensaje</h2>
    <p>Nombre, medio de contacto, descripción clara del requerimiento, URL afectada y, si pedís derechos sobre datos, elementos que permitan verificar identidad de forma proporcional.</p>
    <h2>Plazos</h2>
    <p>Responderemos en un plazo razonable y, en todo caso, conforme a la normativa chilena aplicable al tipo de solicitud.</p>
    <h2>Emergencias de seguridad</h2>
    <p>Si detectás una vulnerabilidad técnica, escribinos con detalles no explotables públicamente. Agradecemos el reporte responsable.</p>

  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
