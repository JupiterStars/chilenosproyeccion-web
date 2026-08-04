<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$pageTitle = 'Quiénes somos | ChilenosProyección';
$metaDescription = 'Medio digital del fútbol joven chileno.';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Quiénes somos</h1></div>
    <p>
      <strong>Chilenos Proyección</strong> es un medio periodístico digital chileno especializado en
      fútbol juvenil y formativas: categorías Sub-20 a Infantil, resultados, goleadores y proyecciones.
    </p>
    <p>
      Trabajamos cobertura de cancha, datos oficiales y redacción con revisión humana,
      para informar con claridad y respeto por el fútbol formativo.
    </p>
    <p>Sitio: <a href="https://chilenosproyeccion.cl">chilenosproyeccion.cl</a></p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
