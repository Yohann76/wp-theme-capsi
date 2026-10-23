#!/usr/bin/env bash
# Page Contact — coordonnées stockées dans le Customizer (theme mods)

step "Configuring contact info (theme mods)..."

$WP theme mod set capsi_email   "contact@lateliercapsi.fr"
$WP theme mod set capsi_phone   "+33 1 00 00 00 00"
$WP theme mod set capsi_address "1 rue de l'Atelier\n75000 Paris"
$WP theme mod set capsi_hours   "Lun–Ven : 9h–18h"
