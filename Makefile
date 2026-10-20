.PHONY: dev-build dev-install dev-run dev-kill dev-configure dev-reload

dev-build:
	docker compose pull
	$(MAKE) dev-install

dev-install:
	docker run --rm -v $(PWD)/theme:/app composer:2 install --working-dir=/app --no-interaction --optimize-autoloader

dev-run:
	docker compose up -d

dev-kill:
	docker compose down

dev-configure:
	bash scripts/configure.sh

dev-reload:
	$(MAKE) dev-kill
	docker volume rm wp-theme-capsi_wp_data wp-theme-capsi_db_data 2>/dev/null || true
	$(MAKE) dev-build
	$(MAKE) dev-run
	$(MAKE) dev-configure
