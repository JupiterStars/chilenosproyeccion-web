# Graph Report - .  (2026-08-03)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 89 nodes · 86 edges · 37 communities (31 shown, 6 thin omitted)
- Extraction: 70% EXTRACTED · 30% INFERRED · 0% AMBIGUOUS · INFERRED: 26 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Community 0
- Community 1
- Community 2
- Community 3
- Community 4
- Community 5
- Community 6
- Community 7
- Community 8

## God Nodes (most connected - your core abstractions)
1. `Database` - 20 edges
2. `demo_noticias_destacadas()` - 8 edges
3. `NoticiaModel` - 7 edges
4. `currentTheme()` - 4 edges
5. `applyTheme()` - 4 edges
6. `app_url()` - 3 edges
7. `e()` - 3 edges
8. `csrf_field()` - 3 edges
9. `categorias_futbol_joven()` - 3 edges
10. `CategoriaModel` - 3 edges

## Surprising Connections (you probably didn't know these)
- None detected - all connections are within the same source files.

## Import Cycles
- None detected.

## Communities (37 total, 6 thin omitted)

### Community 0 - "Community 0"
Cohesion: 0.15
Nodes (5): Database, ClubModel, PosicionModel, TagModel, PDO

### Community 1 - "Community 1"
Cohesion: 0.25
Nodes (6): app_url(), csrf_field(), csrf_token(), e(), env(), redirect()

### Community 3 - "Community 3"
Cohesion: 0.39
Nodes (6): applyTheme(), currentTheme(), systemTheme(), toggleTheme(), updateThemeMeta(), updateToggleLabel()

## Knowledge Gaps
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Database` connect `Community 0` to `Community 2`, `Community 4`, `Community 5`, `Community 6`, `Community 7`, `Community 8`?**
  _High betweenness centrality (0.249) - this node is a cross-community bridge._
- **Why does `demo_noticias_destacadas()` connect `Community 2` to `Community 0`, `Community 1`?**
  _High betweenness centrality (0.071) - this node is a cross-community bridge._
- **Why does `categorias_futbol_joven()` connect `Community 4` to `Community 1`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `Database` (e.g. with `.porSlug()` and `.todas()`) actually correct?**
  _`Database` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 7 inferred relationships involving `demo_noticias_destacadas()` (e.g. with `.buscar()` and `.contarPublicadas()`) actually correct?**
  _`demo_noticias_destacadas()` has 7 INFERRED edges - model-reasoned connections that need verification._