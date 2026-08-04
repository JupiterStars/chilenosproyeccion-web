<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Términos y condiciones | Chilenos Proyección';
$metaDescription = 'Términos y condiciones de uso — Chilenos Proyección · Futbolistas Chilenos.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Términos y condiciones</h1></div>

    <p class="legal-lead">
      Los presentes términos regulan el acceso y uso del sitio web de
      <strong><?= e(media_brand_name()) ?></strong>, medio de la familia
      <strong><?= e(legal_entity_name()) ?></strong>. Al utilizar el sitio, usted declara haber leído y
      aceptado estas condiciones. Si no está de acuerdo, debe abstenerse de usarlo.
    </p>

    <h2>1. Identificación</h2>
    <p>
      Responsable: <?= e(legal_entity_name()) ?>. Medio: <?= e(media_brand_name()) ?>.
      Contacto: <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>.
      Aviso legal: <a href="<?= e(app_url('/legales/aviso-legal')) ?>">ver documento</a>.
    </p>

    <h2>2. Objeto del servicio</h2>
    <p>
      El sitio ofrece información periodística y de datos sobre fútbol juvenil y formativas en Chile
      (noticias, posiciones, goleadores, programación y contenidos relacionados). No es un servicio
      oficial de la ANFP ni de clubes. El contenido es informativo y puede actualizarse o corregirse.
    </p>

    <h2>3. Uso permitido y prohibiciones</h2>
    <p>Usted se compromete a utilizar el sitio de forma lícita y de buena fe. En particular, está prohibido:</p>
    <ul class="legal-list">
      <li>Intentar vulnerar la seguridad, disponibilidad o integridad del sitio o de terceros.</li>
      <li>Realizar extracción masiva o automatizada abusiva de contenidos sin autorización.</li>
      <li>Enviar spam, mensajes engañosos o contenidos ilícitos a través del formulario de contacto.</li>
      <li>Suplantar identidad o facilitar datos falsos en las comunicaciones con el medio.</li>
      <li>Utilizar marcas, textos o materiales del medio con fines comerciales no autorizados.</li>
    </ul>

    <h2>4. Comunicaciones y formulario de contacto</h2>
    <p>
      El único canal público habilitado para comunicarse con el medio es el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>.
      Al enviar un mensaje, usted se compromete a proporcionar datos veraces y actualizados, y a no utilizar
      el canal para fines ilícitos, difamatorios o de acoso. El envío implica la aceptación del
      aviso de consentimiento relativo al tratamiento de sus datos para gestionar la solicitud, conforme a la
      <a href="<?= e(app_url('/legales/politica-privacidad')) ?>">Política de privacidad</a>
      y a la normativa chilena vigente. <?= e(legal_entity_name()) ?> podrá no tramitar o bloquear
      comunicaciones abusivas, reiteradas o que vulneren estos términos.
    </p>
    <p>
      La información que usted facilite se utilizará exclusivamente para las finalidades informadas y bajo
      las normas vigentes en materia de protección de datos y comunicaciones.
    </p>

    <h2>5. Contenidos, exactitud y correcciones</h2>
    <p>
      El medio publica información deportiva con criterio periodístico objetivo y profesional. Si detecta
      una imprecisión relevante, puede solicitar revisión mediante el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>,
      seleccionando el asunto Corrección de contenido.
    </p>

    <h2>6. Propiedad intelectual</h2>
    <p>
      Salvo indicación en contrario, los contenidos editoriales, el diseño y la marca del medio son de
      <?= e(legal_entity_name()) ?> o de sus licenciantes. Se permite la cita breve con atribución y enlace
      a la fuente. Queda prohibida la reproducción total o el uso comercial sin autorización. Los escudos y
      marcas de clubes son de sus titulares. Consulte la
      <a href="<?= e(app_url('/legales/propiedad-intelectual')) ?>">Política de propiedad intelectual</a>.
    </p>

    <h2>7. Contenidos de terceros y enlaces</h2>
    <p>
      El sitio puede enlazar a recursos externos. <?= e(legal_entity_name()) ?> no controla ni responde por
      dichos sitios, sus políticas ni su disponibilidad.
    </p>

    <h2>8. Publicidad</h2>
    <p>
      Podrán mostrarse anuncios propios o de terceros. Los anunciantes son responsables de sus mensajes.
      Cuando un contenido sea publicitario o patrocinado, se procurará su identificación clara.
    </p>

    <h2>9. Cookies y privacidad</h2>
    <p>
      El uso de cookies y el aviso de consentimiento se regulan en la
      <a href="<?= e(app_url('/legales/politica-cookies')) ?>">Política de cookies</a>
      y en la <a href="<?= e(app_url('/legales/politica-privacidad')) ?>">Política de privacidad</a>.
    </p>

    <h2>10. Limitación de responsabilidad</h2>
    <p>
      En la medida permitida por la ley chilena, incluidas las normas de protección al consumidor cuando
      resulten aplicables, <?= e(legal_entity_name()) ?> no responde por daños indirectos, lucro cesante o
      perjuicios derivados del uso o imposibilidad de uso de la información publicada, sin perjuicio de las
      responsabilidades que no puedan limitarse legalmente. El servicio puede verse afectado por
      interrupciones técnicas o por factores ajenos al control del medio.
    </p>

    <h2>11. Menores y cobertura formativa</h2>
    <p>
      La cobertura de categorías infantiles y juveniles se realiza con respeto a la dignidad de las
      personas y a la normativa de protección de la infancia. Se invita a adultos responsables a utilizar el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>
      ante inquietudes sobre imágenes o menciones de menores.
    </p>

    <h2>12. Legislación y jurisdicción</h2>
    <p>
      Estos términos se rigen por las leyes de la República de Chile. Para controversias se privilegiará el
      diálogo directo y, en su caso, los tribunales competentes de Chile.
    </p>

    <h2>13. Modificaciones</h2>
    <p>
      <?= e(legal_entity_name()) ?> podrá actualizar estos términos. La versión vigente se publica en esta
      dirección. El uso continuado del sitio tras la publicación implica la aceptación de la versión actualizada.
    </p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
