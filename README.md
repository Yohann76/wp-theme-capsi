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
