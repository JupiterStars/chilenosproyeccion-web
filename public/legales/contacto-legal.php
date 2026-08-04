<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Contacto legal | Chilenos Proyección';
$metaDescription = 'Contacto legal y ejercicio de derechos — Futbolistas Chilenos · Chilenos Proyección.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Contacto legal</h1></div>

    <p class="legal-lead">
      Canal oficial de <strong><?= e(legal_entity_name()) ?></strong> para asuntos legales, privacidad y
      propiedad intelectual relacionados con el medio <strong><?= e(media_brand_name()) ?></strong>.
      Toda comunicación se realiza a través del
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>.
    </p>

    <h2>1. Cómo contactarnos</h2>
    <ul class="legal-list">
      <li><strong>Responsable:</strong> <?= e(legal_entity_name()) ?></li>
      <li><strong>Medio:</strong> <?= e(media_brand_name()) ?></li>
      <li><strong>País:</strong> Chile</li>
      <li><strong>Canal:</strong> <a href="<?= e(app_url('/contacto')) ?>">Formulario de contacto</a></li>
      <li><strong>Asunto sugerido:</strong> Legal / Privacidad</li>
    </ul>
    <p>
      Al completar el formulario, indique su nombre, un correo válido para responderle y el detalle de su
      solicitud. No publicamos direcciones de correo propias; el formulario es el único canal habilitado
      para estos fines.
    </p>

    <h2>2. Materias que puede plantear por este canal</h2>
    <ul class="legal-list">
      <li>Ejercicio de derechos sobre datos personales (acceso, rectificación, cancelación, oposición y afines).</li>
      <li>Reclamos o consultas sobre privacidad y cookies.</li>
      <li>Denuncias de infracción de propiedad intelectual o de imagen.</li>
      <li>Solicitudes de rectificación periodística de carácter formal.</li>
      <li>Requerimientos de autoridades o notificaciones legales.</li>
    </ul>

    <h2>3. Qué incluir en su mensaje</h2>
    <p>Para una respuesta eficaz, se recomienda indicar:</p>
    <ul class="legal-list">
      <li>Nombre completo y un correo de respuesta.</li>
      <li>Asunto preciso (por ejemplo: Privacidad - derecho de acceso).</li>
      <li>Descripción clara del requerimiento y, si aplica, la dirección de la página afectada.</li>
      <li>En solicitudes sobre datos personales, elementos que permitan verificar su identidad de forma proporcional.</li>
    </ul>

    <h2>4. Plazos</h2>
    <p>
      Las solicitudes serán revisadas y respondidas dentro de los plazos que establece la normativa chilena
      aplicable a cada tipo de requerimiento, y en todo caso con la mayor prontitud posible.
    </p>

    <h2>5. Seguridad de la información</h2>
    <p>
      Si detecta una vulnerabilidad técnica en el sitio, le agradecemos un reporte responsable a través del
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>,
      con el asunto Seguridad, incluyendo la información necesaria para su reproducción, sin difundir
      públicamente datos que permitan su explotación.
    </p>

    <h2>6. Documentos relacionados</h2>
    <ul class="legal-list">
      <li><a href="<?= e(app_url('/legales/aviso-legal')) ?>">Aviso legal</a></li>
      <li><a href="<?= e(app_url('/legales/politica-privacidad')) ?>">Política de privacidad</a></li>
      <li><a href="<?= e(app_url('/legales/politica-cookies')) ?>">Política de cookies</a></li>
      <li><a href="<?= e(app_url('/legales/terminos-y-condiciones')) ?>">Términos y condiciones</a></li>
      <li><a href="<?= e(app_url('/legales/propiedad-intelectual')) ?>">Propiedad intelectual</a></li>
      <li><a href="<?= e(app_url('/legales/politica-editorial')) ?>">Política editorial</a></li>
    </ul>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
