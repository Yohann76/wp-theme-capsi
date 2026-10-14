# wp-theme-capsi

WordPress theme for **L'Atelier Capsi**

## Start project

```bash
make dev-build   # downloads Docker images
make dev-run     # starts WordPress + MySQL
make dev-kill    # stops the containers
make dev-configure # auto configure wp with wp-cli

make dev-kill && make dev-run && make dev-configure

WordPress configure !
URL     : http://51.178.80.14:8080
Admin   : http://51.178.80.14:8080/wp-admin
Login   : admin / capsi2024
```

Local access : [http://51.178.80.14:8080](http://51.178.80.14:8080)

## Technologie

- Docker
- Make
- Wordpress
- Twig (!important to use twig)

## Important

- Based on best SEO practice
- Use WooCommerce plugin 
- Respect chart grapics (TODO: create...)

## Structure

```
wp-theme-capsi/
├── docker-compose.yml
├── Makefile
└── theme/
    ├── style.css
    ├── index.php
    ├── header.php
    ├── footer.php
    └── functions.php
```
