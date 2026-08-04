<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$pageTitle = 'Quiénes somos | Chilenos Proyección';
$metaDescription = 'Chilenos Proyección, medio de fútbol juvenil de la familia Futbolistas Chilenos.';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Quiénes somos</h1></div>

    <p class="legal-lead">
      <strong><?= e(media_brand_name()) ?></strong> es un medio periodístico digital chileno especializado en
      fútbol juvenil y formativas: categorías Sub-20 a Infantil, resultados, goleadores, programación y proyecciones.
      Forma parte de la <strong>familia de <?= e(legal_entity_name()) ?></strong>.
    </p>

    <p>
      <?= e(legal_entity_name()) ?> es un ecosistema dedicado al fútbol chileno, con especial atención a las
      canteras y al desarrollo de jóvenes talentos.
    </p>

    <h2>Qué hacemos</h2>
    <p>
      Ofrecemos cobertura informativa de torneos formativos, tablas de posiciones, tablas de goleadores y programación
      de partidos, con un enfoque claro, riguroso y respetuoso del fútbol de base.
    </p>
    <ul class="legal-list">
      <li>Seguimiento de campeonatos Nacional, Regional e Infantil.</li>
      <li>Datos de clasificación, promedios y destacados de jornada.</li>
      <li>Crónicas, entrevistas y análisis orientados a la proyección de jugadores.</li>
    </ul>

    <h2>Cómo trabajamos</h2>
    <p>
      Informamos con rigor y criterio periodístico, privilegiando la claridad del relato y el respeto por clubes,
      cuerpos técnicos, familias y, en especial, por los menores que participan en las categorías formativas.
      Nuestro compromiso es una cobertura sobria, sin sensacionalismo.
    </p>

    <h2>Contacto</h2>
    <p>
      Para consultas, correcciones o propuestas editoriales, utilice el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>.
      Es el canal oficial del medio y de la familia <?= e(legal_entity_name()) ?>.
    </p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
