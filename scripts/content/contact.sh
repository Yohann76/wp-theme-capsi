#!/usr/bin/env bash
# Page Contact — meta pour le template

step "Configuring contact page..."

CONTACT_ID=$(page_id "Contact")

# Données de contact affichées dans le template
$WP post meta update "$CONTACT_ID" _contact_email    "contact@lateliercapsi.fr"
$WP post meta update "$CONTACT_ID" _contact_phone    "+33 1 00 00 00 00"
$WP post meta update "$CONTACT_ID" _contact_address  "1 rue de l'Atelier, 75000 Paris"
$WP post meta update "$CONTACT_ID" _contact_hours    "Lun–Ven : 9h–18h"
