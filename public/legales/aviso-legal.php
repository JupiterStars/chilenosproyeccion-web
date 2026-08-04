<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Aviso legal | Chilenos Proyección';
$metaDescription = 'Aviso legal de Chilenos Proyección — familia Futbolistas Chilenos.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Aviso legal</h1></div>

    <p class="legal-lead">
      El presente aviso regula el acceso y uso del sitio web de
      <strong><?= e(media_brand_name()) ?></strong>, medio digital de la familia
      <strong><?= e(legal_entity_name()) ?></strong>, con operación en la República de Chile.
    </p>

    <h2>1. Identificación del responsable</h2>
    <ul class="legal-list">
      <li><strong>Marca del medio:</strong> <?= e(media_brand_name()) ?></li>
      <li><strong>Responsable / familia editorial:</strong> <?= e(legal_entity_name()) ?></li>
      <li><strong>Actividad:</strong> medio de comunicación digital especializado en fútbol juvenil y formativas</li>
      <li><strong>País:</strong> Chile</li>
      <li><strong>Contacto:</strong> <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a></li>
      <li><strong>Canal legal:</strong> <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a></li>
    </ul>

    <h2>2. Objeto del sitio</h2>
    <p>
      El sitio tiene por objeto la difusión de información periodística y de datos deportivos relativos a
      categorías formativas (Sub-20 a Infantil), incluyendo noticias, tablas, programación, goleadores y
      contenidos afines. No constituye un servicio oficial de la ANFP ni de clubes afiliados.
    </p>

    <h2>3. Carácter de la información</h2>
    <p>
      Los contenidos se publican con fines informativos y de opinión periodística. No constituyen asesoría
      legal, médica, financiera ni de ninguna otra índole profesional. Resultados, tablas y estadísticas
      se elaboran con criterio editorial y pueden actualizarse o corregirse cuando corresponda.
    </p>

    <h2>4. Propiedad intelectual y marcas de terceros</h2>
    <p>
      Los textos, diseño, selección editorial y elementos gráficos propios del medio son de
      <?= e(legal_entity_name()) ?> o se utilizan con autorización. Las marcas, escudos y denominaciones de
      clubes, ligas y de la ANFP pertenecen a sus respectivos titulares y se mencionan o reproducen con fines
      de información periodística e identificación, sin implicar afiliación o patrocinio.
    </p>
    <p>Para mayor detalle, consulte la <a href="<?= e(app_url('/legales/propiedad-intelectual')) ?>">Política de propiedad intelectual</a>.</p>

    <h2>5. Enlaces a terceros</h2>
    <p>
      El sitio puede incluir enlaces a páginas externas (clubes, federación, redes sociales u otros). Dichos
      sitios son ajenos al control de <?= e(legal_entity_name()) ?>, que no asume responsabilidad por sus
      contenidos, políticas de privacidad ni disponibilidad.
    </p>

    <h2>6. Publicidad y contenidos patrocinados</h2>
    <p>
      Podrán exhibirse espacios publicitarios o contenidos con mención comercial. Cuando un contenido sea
      patrocinado o publicitario, se procurará su identificación clara para el lector. Los anunciantes son
      responsables de la veracidad y legalidad de sus mensajes.
    </p>

    <h2>7. Protección de datos y cookies</h2>
    <p>
      El tratamiento de datos personales se rige por la
      <a href="<?= e(app_url('/legales/politica-privacidad')) ?>">Política de privacidad</a>
      y por la normativa chilena vigente. El uso de cookies y tecnologías similares se describe en la
      <a href="<?= e(app_url('/legales/politica-cookies')) ?>">Política de cookies</a>,
      incluyendo el aviso de consentimiento al ingresar al sitio.
    </p>

    <h2>8. Limitación de responsabilidad</h2>
    <p>
      <?= e(legal_entity_name()) ?> realiza esfuerzos razonables para mantener la exactitud y disponibilidad
      del servicio. El sitio puede, no obstante, presentar errores, interrupciones o retrasos ajenos a su
      control. En la medida permitida por la ley chilena, no se responde por daños indirectos derivados del
      uso o imposibilidad de uso de la información publicada.
    </p>

    <h2>9. Legislación aplicable</h2>
    <p>
      El presente aviso y el uso del sitio se rigen por las leyes de la República de Chile. Para la resolución
      de controversias se privilegiará el contacto directo y, en su caso, los tribunales competentes de Chile.
    </p>

    <h2>10. Contacto</h2>
    <p>
      Para notificaciones legales, reclamos o requerimientos formales, utilice el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>
      seleccionando el asunto Legal / Privacidad, o revise el
      <a href="<?= e(app_url('/legales/contacto-legal')) ?>">canal de contacto legal</a>.
      Atenderemos su solicitud con la mayor diligencia.
    </p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
