<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Política de privacidad | Chilenos Proyección';
$metaDescription = 'Política de privacidad de Chilenos Proyección — Futbolistas Chilenos — normativa chilena.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Política de privacidad</h1></div>

    <p class="legal-lead">
      La presente política describe cómo <strong><?= e(legal_entity_name()) ?></strong>, a través del medio
      <strong><?= e(media_brand_name()) ?></strong>, trata los datos personales de quienes visitan o se
      comunican con este sitio, de conformidad con la normativa chilena vigente en materia de protección de
      datos y vida privada.
    </p>

    <h2>1. Responsable del tratamiento</h2>
    <ul class="legal-list">
      <li><strong>Responsable:</strong> <?= e(legal_entity_name()) ?></li>
      <li><strong>Medio:</strong> <?= e(media_brand_name()) ?></li>
      <li><strong>País:</strong> Chile</li>
      <li><strong>Contacto:</strong> <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a></li>
    </ul>

    <h2>2. Marco normativo</h2>
    <p>
      El tratamiento se realiza bajo las leyes de la República de Chile aplicables a la protección de datos
      personales y a la vida privada, en particular la Ley N°&nbsp;19.628 y las normas, principios y
      lineamientos que las complementen o sustituyan, incluyendo el marco de protección de datos personales
      en vigor. Asimismo, se consideran las obligaciones de lealtad y transparencia propias de un medio de
      comunicación digital, y, en lo pertinente, la protección de niños, niñas y adolescentes (Ley N°&nbsp;21.430).
    </p>

    <h2>3. Datos que podemos tratar</h2>
    <ul class="legal-list">
      <li><strong>Formulario de contacto:</strong> nombre, correo electrónico de respuesta, asunto, mensaje, fecha de envío e información técnica básica (por ejemplo, dirección IP) para seguridad y prevención de abuso.</li>
      <li><strong>Navegación y preferencias:</strong> datos técnicos del navegador, páginas visitadas y preferencias de interfaz (por ejemplo, tema claro u oscuro), según se detalla en la política de cookies.</li>
      <li><strong>Analítica y publicidad (solo con consentimiento):</strong> identificadores de cookies o similares utilizados por herramientas de terceros para medición de visitas y, cuando corresponda, publicidad, únicamente si usted acepta las cookies opcionales en el aviso de consentimiento.</li>
      <li><strong>Redes sociales:</strong> si interactúa con perfiles externos vinculados al medio, aplicarán además las políticas de cada plataforma.</li>
    </ul>
    <p>No solicitamos datos especialmente sensibles a través del sitio. Le pedimos no enviar contraseñas ni información confidencial innecesaria por el formulario de contacto.</p>

    <h2>4. Finalidades</h2>
    <ul class="legal-list">
      <li>Gestionar y responder consultas, correcciones y solicitudes enviadas por el formulario de contacto.</li>
      <li>Operar y mejorar el sitio web, incluyendo seguridad, prevención de spam y registro técnico mínimo.</li>
      <li>Recordar preferencias de uso esenciales (por ejemplo, consentimiento de cookies y tema visual).</li>
      <li>Con su consentimiento, permitir que herramientas de terceros midan audiencia y, si corresponde, muestren publicidad.</li>
      <li>Cumplir obligaciones legales y atender requerimientos de autoridad competente cuando proceda.</li>
    </ul>

    <h2>5. Bases de licitud y consentimiento</h2>
    <p>
      Tratamos datos sobre la base de: (i) su <strong>consentimiento</strong>, cuando envía el formulario o
      acepta cookies no esenciales; (ii) el <strong>interés legítimo</strong> en la seguridad del servicio y
      la prevención de abusos; y (iii) el <strong>cumplimiento de obligaciones legales</strong> cuando
      correspondan. El aviso de cookies del sitio le permite aceptar todas las cookies o limitarse a las
      estrictamente necesarias. Puede revocar el consentimiento de cookies no esenciales borrando los datos
      del sitio en su navegador o modificando la elección cuando el medio habilite un mecanismo equivalente.
    </p>

    <h2>6. Encargados, proveedores y transferencias</h2>
    <p>
      Para alojar el sitio, gestionar el envío de mensajes del formulario, medir visitas o mostrar publicidad
      podemos recurrir a proveedores de servicios tecnológicos. Algunos pueden operar total o parcialmente
      fuera de Chile. En tales casos se procuran medidas razonables de protección y contratos o salvaguardas
      acordes a la normativa aplicable. La información se utiliza bajo las normas vigentes y no se vende a
      terceros.
    </p>

    <h2>7. Plazos de conservación</h2>
    <ul class="legal-list">
      <li><strong>Mensajes de contacto:</strong> el tiempo necesario para gestionar la solicitud y un periodo adicional razonable por seguridad o defensa de reclamaciones.</li>
      <li><strong>Registros técnicos de seguridad:</strong> plazos cortos, salvo investigación de incidentes.</li>
      <li><strong>Cookies y analítica de terceros:</strong> según la configuración de cada proveedor y la vigencia del consentimiento.</li>
    </ul>

    <h2>8. Derechos de las personas</h2>
    <p>
      Usted puede solicitar acceso a sus datos, rectificación, cancelación o eliminación, oposición al
      tratamiento y revocación del consentimiento cuando este sea la base del tratamiento, en los términos de
      la normativa vigente. También puede solicitar información sobre cesiones o encargos relevantes.
    </p>
    <p>
      Para ejercer estos derechos, utilice el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>
      seleccionando el asunto Legal / Privacidad, e indique en el mensaje el tipo de derecho que desea
      ejercer (por ejemplo: Privacidad - derecho de acceso), una descripción de su solicitud y elementos
      que permitan verificar su identidad de forma proporcional. Responderemos en los plazos que establezca la ley.
    </p>

    <h2>9. Menores de edad y fútbol formativo</h2>
    <p>
      El sitio informa sobre categorías juveniles e infantiles. No se dirigen campañas de captación de datos a
      menores. El tratamiento de imágenes o menciones de niños, niñas y adolescentes se realiza con criterio
      editorial prudente, minimización de datos y respeto a la normativa de protección de la infancia. Los
      adultos responsables pueden contactarnos por el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>
      para solicitar correcciones o retiro de material cuando corresponda.
    </p>

    <h2>10. Seguridad</h2>
    <p>
      Se aplican medidas técnicas y organizativas razonables para proteger la información (control de acceso,
      comunicaciones cifradas cuando el sitio opera por HTTPS, limitación de envíos del formulario, entre
      otras). Ningún sistema es absolutamente invulnerable; ante un incidente con impacto en datos personales
      se evaluarán las medidas y notificaciones que exija la normativa.
    </p>

    <h2>11. Cookies</h2>
    <p>
      El detalle de categorías de cookies, el aviso de consentimiento y las opciones de aceptación se
      describen en la <a href="<?= e(app_url('/legales/politica-cookies')) ?>">Política de cookies</a>.
    </p>

    <h2>12. Modificaciones</h2>
    <p>
      Esta política puede actualizarse para reflejar cambios legales, técnicos o de operación del medio. La
      versión vigente se publica en esta misma dirección.
    </p>

    <h2>13. Contacto</h2>
    <p>
      <?= e(legal_entity_name()) ?> · <?= e(media_brand_name()) ?> ·
      <a href="<?= e(app_url('/contacto')) ?>">Formulario de contacto</a>
    </p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
