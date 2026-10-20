#!/usr/bin/env bash
# Page À propos — contenu structuré

step "Populating À propos page..."

ABOUT_ID=$(page_id "À propos")

$WP post update "$ABOUT_ID" --post_content="
<section class=\"about-section\">
  <h2>Notre mission</h2>
  <p>L'Atelier Capsi est né d'une passion : préserver et partager les variétés de piments les plus rares et les plus remarquables du monde. Dans un contexte d'appauvrissement de la biodiversité cultivée, nous agissons comme un conservatoire vivant du genre <em>Capsicum</em>.</p>
  <p>Nous sélectionnons, cultivons et propageons des variétés d'exception — certaines menacées de disparition — pour les mettre entre les mains des jardiniers curieux, des cuisiniers passionnés et des collectionneurs.</p>
</section>

<section class=\"about-section\">
  <h2>Notre histoire</h2>
  <p>Tout a commencé en 2018 avec une dizaine de variétés cultivées en serre. Au fil des saisons, la collection s'est étendue à plus de 200 variétés issues des cinq espèces principales de <em>Capsicum</em>. Aujourd'hui, l'Atelier propose graines, plants et produits artisanaux élaborés à partir de notre propre production.</p>
</section>

<section class=\"about-section\">
  <h2>Nos valeurs</h2>
  <ul class=\"about-values\">
    <li><strong>Biodiversité</strong> — Chaque variété que nous cultivons représente un patrimoine génétique unique. Nous refusons de ne proposer que les variétés commerciales.</li>
    <li><strong>Traçabilité</strong> — Toutes nos graines sont issues de notre propre production ou de semenciers partenaires identifiés. Zéro intermédiaire anonyme.</li>
    <li><strong>Transmission</strong> — Partager le savoir fait partie de notre ADN : guides de culture, conseils personnalisés, blog botanique.</li>
    <li><strong>Artisanat</strong> — Nos sauces et condiments sont élaborés en petites séries, à partir de piments cultivés sur place, sans conservateurs.</li>
  </ul>
</section>

<section class=\"about-section\">
  <h2>Notre collection</h2>
  <p>Plus de <strong>200 variétés</strong> répertoriées, issues des cinq espèces du genre <em>Capsicum</em> :</p>
  <ul class=\"about-species\">
    <li><em>Capsicum annuum</em> — Jalapeño, Cayenne, Paprika, Piment d'Espelette…</li>
    <li><em>Capsicum chinense</em> — Habanero, Carolina Reaper, Ghost Pepper, Scotch Bonnet…</li>
    <li><em>Capsicum frutescens</em> — Tabasco, Thai, Malagueta…</li>
    <li><em>Capsicum baccatum</em> — Aji Amarillo, Lemon Drop, Bishop's Crown…</li>
    <li><em>Capsicum pubescens</em> — Rocoto, Manzano…</li>
  </ul>
</section>
"
