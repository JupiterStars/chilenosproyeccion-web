<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Propiedad intelectual | Chilenos Proyección';
$metaDescription = 'Propiedad intelectual — Chilenos Proyección · Futbolistas Chilenos.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Propiedad intelectual</h1></div>

    <p class="legal-lead">
      La presente política describe la titularidad y el uso permitido de los contenidos del sitio de
      <strong><?= e(media_brand_name()) ?></strong>, medio de la familia
      <strong><?= e(legal_entity_name()) ?></strong>.
    </p>

    <h2>1. Contenidos propios</h2>
    <p>
      Salvo indicación expresa en contrario, los textos, fotografías propias, diseño gráfico, logotipos,
      tipografías licenciadas al medio, estructura de bases de datos editoriales y la selección y
      disposición de contenidos son de <?= e(legal_entity_name()) ?> o se utilizan con autorización de
      sus titulares. Queda prohibida la reproducción total, la explotación comercial o la creación de obras
      derivadas sin autorización escrita previa, sin perjuicio del derecho de cita con atribución y enlace.
    </p>

    <h2>2. Uso permitido para lectores y medios</h2>
    <ul class="legal-list">
      <li>Compartir enlaces a las páginas del sitio.</li>
      <li>Citar extractos breves con mención de la fuente y enlace a la publicación original.</li>
      <li>Uso personal y no comercial de la información de tablas y resultados, sin alteración engañosa.</li>
    </ul>

    <h2>3. Marcas y escudos de clubes y entidades</h2>
    <p>
      Las marcas, escudos, himnos y denominaciones de clubes, ligas y de la ANFP son de sus respectivos
      titulares. Su mención o reproducción en el sitio se realiza con fines de información periodística e
      identificación de equipos, sin que ello implique afiliación, patrocinio o respaldo. Si un titular
      solicita el retiro o ajuste de material, puede utilizar el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>
      con el asunto Legal / Privacidad; la solicitud será evaluada con diligencia.
    </p>

    <h2>4. Recursos de terceros</h2>
    <p>
      Tipografías, librerías de código u otros componentes pueden estar sujetos a licencias de terceros,
      las cuales se respetan. El uso de dichos recursos no transfiere derechos sobre la marca o los
      contenidos editoriales del medio.
    </p>

    <h2>5. Denuncias de infracción</h2>
    <p>
      Si considera que un contenido del sitio vulnera derechos de propiedad intelectual o de imagen, envíe
      su reclamo mediante el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>,
      incluyendo:
    </p>
    <ul class="legal-list">
      <li>Identificación del reclamante y un correo de respuesta.</li>
      <li>Dirección de la página del contenido objetado.</li>
      <li>Descripción del derecho invocado y, en lo posible, prueba de titularidad.</li>
      <li>Declaración de veracidad de la información aportada.</li>
    </ul>
    <p>Se evaluará el caso y, cuando corresponda, se retirará, modificará o contextualizará el material.</p>

    <h2>6. Contacto</h2>
    <p>
      <?= e(legal_entity_name()) ?> · <?= e(media_brand_name()) ?> ·
      <a href="<?= e(app_url('/contacto')) ?>">Formulario de contacto</a>
    </p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
