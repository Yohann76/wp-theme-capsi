#!/usr/bin/env bash
# Attente que les services soient prêts

step "Waiting for database..."
until docker compose exec db mysql -ucapsi_user -pcapsi_pass capsi_db \
  -e "SELECT 1" >/dev/null 2>&1; do
  sleep 3
done

step "Waiting for WordPress..."
until docker compose exec wordpress curl -s http://localhost >/dev/null 2>&1; do
  sleep 3
done
