<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$destacadas = NoticiaModel::destacadas(5);
$recientes = NoticiaModel::recientes(12);
$goleadores = GoleadorModel::porCategoria('sub-20', 10);
$goleadoresMeta = GoleadorModel::metaCategoria('sub-20');
$categorias = categorias_futbol_joven();

$feedLead = $recientes[0] ?? ($destacadas[0] ?? null);
$feedRows = array_slice($recientes, 1, 4);

// 4 noticias en grilla 2×2 entre Próximos y Resultados (móvil)
$feedMidGrid = array_slice($recientes, 5, 4);
if (count($feedMidGrid) < 4) {
    $feedMidGrid = array_slice(array_merge($recientes, $destacadas), 0, 4);
}

$secSub20 = array_values(array_filter($recientes, static fn ($n) => ($n['categoria_slug'] ?? '') === 'sub-20'));
if (count($secSub20) < 2) {
    $secSub20 = array_slice($recientes, 0, 3);
}

$proximos = array_slice(ticker_proximos_partidos(), 0, 3);
$resultados = array_slice(ticker_resultados_partidos(), 0, 3);

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
                        <?php if ($jSlug): ?>
                          <a href="<?= e(app_url('/jugador/' . $jSlug)) ?>"><strong><?= e($g['jugador'] ?? '') ?></strong></a>
                        <?php else: ?>
                          <strong><?= e($g['jugador'] ?? '') ?></strong>
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

    <?php if ($feedLead): ?>
      <?php $n = $feedLead; require INCLUDES_PATH . '/partials/feed-story-featured.php'; ?>
    <?php endif; ?>

    <div class="feed-stack">
      <?php foreach ($feedRows as $n): ?>
        <?php require INCLUDES_PATH . '/partials/feed-story-row.php'; ?>
      <?php endforeach; ?>
    </div>

    <?php if ($proximos): ?>
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
      <div class="feed-matches">
        <?php foreach ($proximos as $p): ?>
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
    <?php endif; ?>

    <?php /* Grilla 2×2 de noticias (solo móvil, entre Próximos y Resultados) */ ?>
    <?php if ($feedMidGrid): ?>
      <header class="feed-section-head">
        <div class="feed-section-brand">
          <div class="feed-section-badge">N</div>
          <div>
            <strong>Más noticias</strong>
            <span>Formativas</span>
          </div>
        </div>
        <a class="feed-ver-todo" href="<?= e(app_url('/futbol-joven')) ?>">Ver todo</a>
      </header>
      <div class="feed-news-grid">
        <?php foreach (array_slice($feedMidGrid, 0, 4) as $n): ?>
          <?php require INCLUDES_PATH . '/partials/feed-story-grid-card.php'; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($resultados): ?>
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
      <div class="feed-matches">
        <?php foreach ($resultados as $r): ?>
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
    <?php endif; ?>

    <?php if ($secSub20): ?>
      <header class="feed-section-head">
        <div class="feed-section-brand">
          <div class="feed-section-badge">S20</div>
          <div>
            <strong>Sub-20 Nacional</strong>
            <span>Campeonato Nacional</span>
          </div>
        </div>
        <a class="feed-ver-todo" href="<?= e(app_url('/futbol-joven/sub-20')) ?>">Ver todo</a>
      </header>
      <?php
        $n = $secSub20[0];
        require INCLUDES_PATH . '/partials/feed-story-featured.php';
      ?>
      <div class="feed-stack">
        <?php foreach (array_slice($secSub20, 1, 3) as $n): ?>
          <?php require INCLUDES_PATH . '/partials/feed-story-row.php'; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <header class="feed-section-head">
      <div class="feed-section-brand">
        <div class="feed-section-badge feed-section-badge--naranjo">G</div>
        <div>
          <strong>Goleadores Sub-20</strong>
          <span><?= e($goleadoresMeta['torneo'] ?? 'Apertura 2026') ?></span>
        </div>
      </div>
      <a class="feed-ver-todo" href="<?= e(app_url('/goleadores/sub-20')) ?>">Ver todo</a>
    </header>
    <div class="feed-card feed-scorers">
      <ol class="feed-scorers-list">
        <?php foreach (array_slice($goleadores, 0, 5) as $i => $g): ?>
          <?php
            $esc = $g['escudo_url'] ?? '';
            $club = $g['club'] ?? '';
          ?>
          <li>
            <span class="feed-scorers-pos"><?= $i + 1 ?></span>
            <?php if ($esc): ?>
              <img src="<?= e(app_url($esc)) ?>" alt="<?= e($club) ?>" width="28" height="28" loading="lazy" />
            <?php endif; ?>
            <span class="feed-scorers-name"><?= e($g['jugador'] ?? '') ?></span>
            <span class="feed-scorers-goles"><?= (int) ($g['goles'] ?? 0) ?></span>
          </li>
        <?php endforeach; ?>
      </ol>
      <a class="feed-pill-btn" href="<?= e(app_url('/goleadores/sub-20')) ?>">Ver tabla completa</a>
    </div>

    <div class="feed-chips">
      <?php foreach ($categorias as $cat): ?>
        <a class="feed-chip" href="<?= e(app_url('/futbol-joven/' . $cat['slug'])) ?>"><?= e($cat['nombre']) ?></a>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<?php require INCLUDES_PATH . '/footer.php'; ?>
