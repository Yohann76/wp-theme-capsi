# wp-theme-capsi

Custom WordPress theme for **L'Atelier Capsi**, built with Timber/Twig.

## Commands

```bash
make dev-build      # pull Docker images + composer install
make dev-install    # composer install only
make dev-run        # start WordPress + MySQL
make dev-kill       # stop containers
make dev-configure  # configure WordPress via WP-CLI
make dev-reload     # full reset (kill + volumes + build + run + configure)
```

## Getting started

```bash
make dev-reload
```

Access : [http://51.178.80.14:8080](http://51.178.80.14:8080)
Admin : [http://51.178.80.14:8080/wp-admin](http://51.178.80.14:8080/wp-admin) — `admin / capsi2024`

## Brand Guidelines

### Identity
High-end botanical brand — passionate and authentic. A blend of ancient herbalism and clean contemporary design.
> "The conservatory and artisan house of rare and collectible peppers."

### Colors

| Role | Name | Hex | Usage |
|------|------|-----|-------|
| Primary | Rouge Capsi | `#C82A2B` | CTA, accents, logo pepper |
| Secondary | Vert Botaniq | `#2D4A3E` | Text, headings, line art |
| Accent 1 | Terre de Semis | `#B85C38` | Warm accents, terracotta |
| Accent 2 | Safran Brûlé | `#E39B35` | Highlights, badges |
| Background | Papier Botanique | `#F4EFE6` | Page background, cream paper feel |

### Typography

| Role | Font | Weight | Notes |
|------|------|--------|-------|
| Headings & identity | Cormorant Garamond | 400 / 600 | Elegant fine serif, botanical illustration feel |
| Body & labels | Inter | 300 / 400 / 500 | Clean sans-serif, high readability |

### Illustration style
- **Line art**: Fine engraved botanical drawings — precise, natural, no fill or minimal fill
- **Scoville badges**: Minimal circular seal numbered 1–10+, no kitsch flame icons
- **Materials feel**: Kraft paper, amber glass, terracotta pots, textured labels

### CSS tokens
All values are available as CSS custom properties in `theme/assets/css/main.css`.

Two themes available — **dark is default**:

| Token | Dark | Light |
|-------|------|-------|
| `--color-bg` | `#0F0F0F` | `#F4EFE6` |
| `--color-text` | `#EDE8DF` | `#1C2E26` |
| `--color-primary` | `#E03535` | `#C82A2B` |
| `--color-secondary` | `#4A8C6F` | `#2D4A3E` |
| `--color-surface` | `#1E1E1E` | `#FFFFFF` |

Switch theme by setting `data-theme="light"` on `<html>` in `views/base.twig`.

---

## Stack

- Docker + Docker Compose
- WordPress
- Timber / Twig (clean separation of logic and templates)
- WooCommerce

## Structure

```
wp-theme-capsi/
├── docker-compose.yml
├── Makefile
└── theme/
    ├── composer.json
    ├── vendor/               ← gitignored
    ├── style.css
    ├── functions.php         ← PHP logic only
    ├── index.php             ← post list controller
    ├── single.php            ← single post controller
    ├── page.php              ← static page controller
    ├── woocommerce.php       ← shop controller
    └── views/                ← Twig templates (HTML only)
        ├── base.twig
        ├── index.twig
        ├── single.twig
        ├── page.twig
        └── woocommerce.twig
```
