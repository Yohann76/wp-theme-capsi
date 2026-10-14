.PHONY: dev-build dev-run dev-kill dev-configure

WP_URL    = http://51.178.80.14:8080
WP_TITLE  = L'Atelier Capsi
WP_ADMIN  = admin
WP_PASS   = capsi2024
WP_EMAIL  = yohanndurand76@gmail.com

dev-build:
	docker compose pull

dev-run:
	docker compose up -d

dev-kill:
	docker compose down

dev-configure:
	@echo "Attente que la base de données soit prête..."
	@until docker compose exec db mysql -ucapsi_user -pcapsi_pass capsi_db -e "SELECT 1" >/dev/null 2>&1; do sleep 3; done
	@echo "Attente que WordPress soit prêt..."
	@until docker compose exec wordpress curl -s http://localhost >/dev/null 2>&1; do sleep 3; done
	docker compose run --rm wpcli core install \
		--url="$(WP_URL)" \
		--title="$(WP_TITLE)" \
		--admin_user="$(WP_ADMIN)" \
		--admin_password="$(WP_PASS)" \
		--admin_email="$(WP_EMAIL)" \
		--skip-email
	docker compose run --rm wpcli theme activate capsi-theme
	docker compose run --rm wpcli option update blogdescription ""
	@echo ""
	@echo "WordPress configuré !"
	@echo "URL     : $(WP_URL)"
	@echo "Admin   : $(WP_URL)/wp-admin"
	@echo "Login   : $(WP_ADMIN) / $(WP_PASS)"
