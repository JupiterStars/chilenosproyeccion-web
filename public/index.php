<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$destacadas = NoticiaModel::destacadas(5);
$recientes = NoticiaModel::recientes(16);
$goleadores = GoleadorModel::porCategoria('sub-20', 10);
$goleadoresMeta = GoleadorModel::metaCategoria('sub-20');
$categorias = categorias_futbol_joven();

$feedLead = $recientes[0] ?? ($destacadas[0] ?? null);

// Noticias para el patrón móvil (1 grande + filas + pair + grid + 2 grandes…)
$noticiasHome = $recientes;
if (count($noticiasHome) < 8) {
    $noticiasHome = array_values(array_merge($recientes, $destacadas));
    $seen = [];
    $dedup = [];
    foreach ($noticiasHome as $nn) {
        $k = (string) ($nn['slug'] ?? spl_object_id((object) $nn));
        if (isset($seen[$k])) {
            continue;
        }
        $seen[$k] = true;
        $dedup[] = $nn;
    }
    $noticiasHome = $dedup;
}

// Páginas de 4 partidos: se muestran 4 y cada 4s cambian (solo si hay >4)
$proximosAll = array_slice(ticker_proximos_partidos(), 0, 12);
$resultadosAll = array_slice(ticker_resultados_partidos(), 0, 12);
$proximosPages = array_chunk($proximosAll, 4);
$resultadosPages = array_chunk($resultadosAll, 4);

// Goleadores: tablas por categoría (rotan cada 8s en móvil)
$goleadoresSlides = [];
foreach (['sub-20', 'sub-18', 'sub-16', 'sub-15'] as $gCat) {
    $filasG = GoleadorModel::porCategoria($gCat, 12);
    if (!$filasG) {
        continue;
    }
    $etG = categoria_etiqueta($gCat);
    $metaG = GoleadorModel::metaCategoria($gCat);
    $goleadoresSlides[] = [
        'cat' => $gCat,
        'titulo' => $etG['titulo'],
        'torneo' => $metaG['torneo'] ?? ($etG['titulo'] ?? ''),
        'filas' => $filasG,
    ];
}
if (!$goleadoresSlides && $goleadores) {
    $goleadoresSlides[] = [
        'cat' => 'sub-20',
        'titulo' => 'Sub-20 Nacional',
        'torneo' => $goleadoresMeta['torneo'] ?? 'Apertura 2026',
        'filas' => $goleadores,
    ];
}

$pageTitle = 'ChilenosProyección — Fútbol joven chileno';
$metaDescription = 'Resultados, goleadores, debuts y proyecciones Sub-20 a Sub-15. El medio del fútbol joven chileno.';
$navActive = 'inicio';
$ogImage = app_url($feedLead['imagen_destacada_url'] ?? '/assets/brand/goleadores-sub20.jpg');
$bodyClass = 'page-home';

require INCLUDES_PATH . '/header.php';
?>

<?php /* ========== DESKTOP / TABLET: layout web clásico ========== */ ?>
<div class="home-desktop">
  <section class="hero-swiper swiper" aria-label="Destacadas">
    <div class="swiper-wrapper">
      <?php foreach ($destacadas as $i => $slide): ?>
        <div class="swiper-slide">
          <div class="hero-slide-bg">
            <img
              src="<?= e($slide['imagen_destacada_url'] ?? '/assets/brand/goleadores-sub20.jpg') ?>"
              alt="<?= e($slide['imagen_alt'] ?? $slide['titulo']) ?>"
              <?= $i === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"' ?>
            />
          </div>
          <div class="container hero-content">
            <p class="hero-kicker"><?= e($slide['categoria_nombre'] ?? 'Destacada') ?></p>
            <h2 class="hero-title">
              <a href="<?= e(app_url('/noticia/' . $slide['slug'])) ?>">
                <?= e($slide['titulo']) ?>
              </a>
            </h2>
            <p class="hero-lead"><?= e($slide['extracto'] ?? '') ?></p>
            <div class="hero-actions">
              <a class="btn btn-primary" href="<?= e(app_url('/noticia/' . $slide['slug'])) ?>">Leer</a>
              <a class="btn btn-ghost" href="<?= e(app_url('/goleadores/sub-20')) ?>">Goleadores</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
  </section>


  <div class="brand-strip">
    <div class="container brand-strip-inner">
      <span><strong>#</strong>ChilenosProyección</span>
      <span>Sub-20 · Sub-18 · Sub-16 · Sub-15</span>
      <span>Datos · Cancha · Proyección</span>
    </div>
  </div>

  <section class="section" id="noticias">
    <div class="container">
      <div class="section-head">
        <h2>Últimas noticias</h2>
        <div class="chip-row">
          <?php foreach ($categorias as $cat): ?>
            <a class="chip" href="<?= e(app_url('/futbol-joven/' . $cat['slug'])) ?>"><?= e($cat['nombre']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="layout-main">
        <div>
          <div class="card-grid featured">
            <?php foreach (array_slice($recientes, 0, 9) as $noticia): ?>
              <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <aside class="sidebar">
          <div class="panel panel-goleadores">
            <h3>Goleadores <span>Sub-20</span></h3>
            <p class="panel-note"><?= e($goleadoresMeta['torneo'] ?: 'Nacional Apertura 2026') ?> · por goles</p>
            <div class="table-wrap">
              <table class="data-table data-table-compact">
                <thead>
                  <tr>
                    <th class="col-pos">#</th>
                    <th>Jugador</th>
                    <th class="col-club-ico" title="Club">Club</th>
                    <th title="Goles">G</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($goleadores as $i => $g): ?>
                    <?php
                      $jSlug = $g['jugador_slug'] ?? null;
                      $cSlug = $g['club_slug'] ?? null;
                      $clubNom = $g['club'] ?? '';
                      $esc = $g['escudo_url'] ?? ($cSlug ? '/assets/escudos/' . $cSlug . '.png' : '');
                    ?>
                    <tr>
                      <td class="col-pos"><span class="pos-badge"><?= $i + 1 ?></span></td>
                      <td>
                        <?php // Solo nombre: el escudo del club va en la columna Club ?>
                        <?php if ($jSlug): ?>
                          <a class="player-name-link" href="<?= e(app_url('/jugador/' . $jSlug)) ?>"><strong><?= e($g['jugador'] ?? '') ?></strong></a>
                        <?php else: ?>
                          <strong class="player-name"><?= e($g['jugador'] ?? '') ?></strong>
                        <?php endif; ?>
                      </td>
                      <td class="cell-club-ico">
                        <?php if ($esc): ?>
                          <a
                            class="club-crest-only"
                            href="<?= e($cSlug ? app_url('/club/' . $cSlug) : '#') ?>"
                            title="<?= e($clubNom) ?>"
                            aria-label="<?= e($clubNom) ?>"
                          >
                            <img src="<?= e(app_url($esc)) ?>" alt="<?= e($clubNom) ?>" width="28" height="28" loading="lazy" onerror="this.closest('a').classList.add('is-missing')" />
                          </a>
                        <?php else: ?>
                          <span class="club-crest-fallback" title="<?= e($clubNom) ?>"><?= e(mb_substr($clubNom, 0, 1)) ?></span>
                        <?php endif; ?>
                      </td>
                      <td class="num"><?= (int) ($g['goles'] ?? 0) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <p class="panel-more">
              <a class="chip" href="<?= e(app_url('/goleadores/sub-20')) ?>">Sub-20</a>
              <a class="chip" href="<?= e(app_url('/goleadores/sub-18')) ?>">Sub-18</a>
              <a class="chip" href="<?= e(app_url('/goleadores/sub-16')) ?>">Sub-16</a>
              <a class="chip" href="<?= e(app_url('/goleadores/sub-15')) ?>">Sub-15</a>
            </p>
          </div>
        </aside>
      </div>
    </div>
  </section>
</div>

<?php /* ========== MÓVIL: feed estilo app (solo teléfono) ========== */ ?>
<div class="home-mobile feed">
  <div class="container feed-inner">

    <?php if ($noticiasHome): ?>
      <header class="feed-section-head">
        <div class="feed-section-brand">
          <div class="feed-section-badge">N</div>
          <div>
            <strong>Noticias</strong>
            <span>Fútbol joven</span>
          </div>
        </div>
        <a class="feed-ver-todo" href="<?= e(app_url('/futbol-joven')) ?>">Ver todo</a>
      </header>
      <?php
        $noticias = $noticiasHome;
        require INCLUDES_PATH . '/partials/news-feed-pattern.php';
      ?>
    <?php endif; ?>

    <?php if ($proximosPages): ?>
      <header class="feed-section-head">
        <div class="feed-section-brand">
          <div class="feed-section-badge">P</div>
          <div>
            <strong>Próximos</strong>
            <span>Formativas ANFP</span>
          </div>
        </div>
        <a class="feed-ver-todo" href="<?= e(app_url('/programacion/sub-20')) ?>">Ver todo</a>
      </header>
      <div
        class="feed-matches<?= count($proximosPages) > 1 ? ' feed-matches--rotate' : '' ?>"
        <?= count($proximosPages) > 1 ? 'data-match-rotate data-interval="4000"' : '' ?>
      >
        <?php foreach ($proximosPages as $pi => $page): ?>
          <div class="feed-match-slide<?= $pi === 0 ? ' is-active' : '' ?>" data-match-slide <?= $pi === 0 ? '' : 'hidden' ?>>
            <div class="feed-matches-page">
              <?php foreach ($page as $p): ?>
                <?php
                  $match = [
                      'local' => $p['club_local'] ?? '',
                      'visita' => $p['club_visita'] ?? '',
                      'escudo_local' => $p['escudo_local'] ?? '',
                      'escudo_visita' => $p['escudo_visita'] ?? '',
                      'hora' => $p['cuando'] ?? 'vs',
                      'liga' => ($p['categoria'] ?? 'Sub-20') . ' · Próximo',
                      'href' => app_url('/programacion/sub-20'),
                  ];
                  require INCLUDES_PATH . '/partials/feed-match-card.php';
                ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($resultadosPages): ?>
      <header class="feed-section-head">
        <div class="feed-section-brand">
          <div class="feed-section-badge feed-section-badge--rojo">R</div>
          <div>
            <strong>Resultados</strong>
            <span>Últimos partidos</span>
          </div>
        </div>
        <a class="feed-ver-todo" href="<?= e(app_url('/posiciones/sub-20')) ?>">Ver todo</a>
      </header>
      <div
        class="feed-matches<?= count($resultadosPages) > 1 ? ' feed-matches--rotate' : '' ?>"
        <?= count($resultadosPages) > 1 ? 'data-match-rotate data-interval="4000"' : '' ?>
      >
        <?php foreach ($resultadosPages as $ri => $page): ?>
          <div class="feed-match-slide<?= $ri === 0 ? ' is-active' : '' ?>" data-match-slide <?= $ri === 0 ? '' : 'hidden' ?>>
            <div class="feed-matches-page">
              <?php foreach ($page as $r): ?>
                <?php
                  $match = [
                      'local' => $r['club_local'] ?? '',
                      'visita' => $r['club_visita'] ?? '',
                      'escudo_local' => $r['escudo_local'] ?? '',
                      'escudo_visita' => $r['escudo_visita'] ?? '',
                      'score' => $r['score'] ?? '',
                      'liga' => ($r['categoria'] ?? '') . ' · Final',
                      'href' => app_url('/posiciones/sub-20'),
                  ];
                  require INCLUDES_PATH . '/partials/feed-match-card.php';
                ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($goleadoresSlides): ?>
      <header class="feed-section-head">
        <div class="feed-section-brand">
          <div class="feed-section-badge feed-section-badge--naranjo">G</div>
          <div>
            <strong data-scorers-title>Goleadores</strong>
            <span data-scorers-sub><?= e($goleadoresSlides[0]['titulo'] ?? 'Sub-20') ?></span>
          </div>
        </div>
        <a class="feed-ver-todo" href="<?= e(app_url('/goleadores/sub-20')) ?>">Ver todo</a>
      </header>
      <div
        class="feed-scorers-rotate<?= count($goleadoresSlides) > 1 ? ' is-rotating' : '' ?>"
        <?= count($goleadoresSlides) > 1 ? 'data-scorers-rotate data-interval="8000"' : '' ?>
      >
        <?php foreach ($goleadoresSlides as $si => $slide): ?>
          <div
            class="feed-scorers-slide<?= $si === 0 ? ' is-active' : '' ?>"
            data-scorers-slide
            data-scorers-label="<?= e($slide['titulo']) ?>"
            <?= $si === 0 ? '' : 'hidden' ?>
          >
            <div class="feed-card feed-scorers">
              <p class="feed-scorers-cat"><?= e($slide['titulo']) ?><?php if ($slide['torneo'] !== ''): ?> · <?= e((string) $slide['torneo']) ?><?php endif; ?></p>
              <ol class="feed-scorers-list">
                <?php foreach ($slide['filas'] as $i => $g): ?>
                  <?php
                    $club = (string) ($g['club'] ?? '');
                    $cSlug = (string) ($g['club_slug'] ?? '');
                    $esc = (string) ($g['escudo_url'] ?? '');
                    if ($esc === '' && $cSlug !== '') {
                        $esc = club_escudo_url($cSlug);
                    }
                    $jSlug = $g['jugador_slug'] ?? null;
                  ?>
                  <li>
                    <span class="feed-scorers-pos"><?= $i + 1 ?></span>
                    <?php if ($esc): ?>
                      <img src="<?= e(app_url($esc)) ?>" alt="<?= e($club) ?>" title="<?= e($club) ?>" width="28" height="28" loading="lazy" onerror="this.style.display='none'" />
                    <?php endif; ?>
                    <span class="feed-scorers-name">
                      <?php if ($jSlug): ?>
                        <a href="<?= e(app_url('/jugador/' . $jSlug)) ?>"><?= e($g['jugador'] ?? '') ?></a>
                      <?php else: ?>
                        <?= e($g['jugador'] ?? '') ?>
                      <?php endif; ?>
                    </span>
                    <span class="feed-scorers-goles"><?= (int) ($g['goles'] ?? 0) ?></span>
                  </li>
                <?php endforeach; ?>
              </ol>
              <a class="feed-pill-btn" href="<?= e(app_url('/goleadores/' . $slide['cat'])) ?>">Ver tabla completa</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="feed-chips">
      <?php foreach ($categorias as $cat): ?>
        <a class="feed-chip" href="<?= e(app_url('/futbol-joven/' . $cat['slug'])) ?>"><?= e($cat['nombre']) ?></a>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<?php require INCLUDES_PATH . '/footer.php'; ?>
