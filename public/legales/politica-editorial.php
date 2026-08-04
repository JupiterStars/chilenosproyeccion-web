<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Política editorial | Chilenos Proyección';
$metaDescription = 'Criterios editoriales de Chilenos Proyección — familia Futbolistas Chilenos.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Política editorial</h1></div>

    <p class="legal-lead">
      <strong><?= e(media_brand_name()) ?></strong> es un medio digital de la familia
      <strong><?= e(legal_entity_name()) ?></strong> dedicado al fútbol juvenil y formativas en Chile.
      Esta política resume los principios que orientan su trabajo informativo.
    </p>

    <h2>1. Misión</h2>
    <p>
      Informar con claridad, rigor y respeto sobre torneos formativos, trayectorias de jóvenes jugadores,
      resultados, clasificaciones y contextos del fútbol de cantera, contribuyendo a una conversación
      pública informada sobre el desarrollo del fútbol chileno.
    </p>

    <h2>2. Criterio informativo</h2>
    <p>
      Publicamos la información deportiva con un enfoque objetivo y profesional. Las tablas, goleadores,
      programación y crónicas se elaboran con criterio editorial propio, orientado a la exactitud y a la
      utilidad del lector. Ante imprecisiones relevantes, priorizamos la corrección oportuna.
    </p>

    <h2>3. Independencia y transparencia</h2>
    <p>
      Las decisiones editoriales buscan el interés del lector. Los contenidos publicitarios o patrocinados
      se identificarán de forma clara. Las relaciones comerciales no deben distorsionar la veracidad de la
      información deportiva publicada.
    </p>

    <h2>4. Respeto y cobertura de menores</h2>
    <p>
      La cobertura de categorías infantiles y juveniles se realiza con especial cuidado: se evita el
      sensacionalismo, se minimizan datos innecesarios y se atiende con seriedad las solicitudes de
      corrección o retiro formuladas por adultos responsables, en línea con la normativa de protección de
      la infancia y buenas prácticas periodísticas.
    </p>

    <h2>5. Correcciones</h2>
    <p>
      Los errores relevantes se corrigen de forma visible o mediante actualización del contenido, según
      corresponda. Las solicitudes de rectificación se reciben únicamente a través del
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>,
      seleccionando el asunto Corrección de contenido.
    </p>

    <h2>6. Opinión y análisis</h2>
    <p>
      Las piezas de opinión o análisis se distinguen, en la medida de lo posible, de la información de
      hechos. Las proyecciones sobre jugadores son juicios periodísticos basados en el desempeño observado
      y no constituyen promesas ni valoraciones oficiales de clubes o selecciones.
    </p>

    <h2>7. Contacto editorial</h2>
    <p>
      <?= e(legal_entity_name()) ?> · <?= e(media_brand_name()) ?> ·
      <a href="<?= e(app_url('/contacto')) ?>">Formulario de contacto</a>
    </p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
