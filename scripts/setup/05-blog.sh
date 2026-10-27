#!/usr/bin/env bash
# Catégories blog + articles SEO

step "Creating blog categories..."
$WP term create category "Actualités"          --description="Les dernières nouvelles de L'Atelier Capsi"
$WP term create category "Culture & Conseils"  --description="Guides de culture, conseils et techniques"
$WP term create category "Variétés"            --description="Présentation des variétés rares et de collection"
$WP term create category "Plantes aromatiques" --description="Présentation et culture des plantes aromatiques rares"
$WP term delete category 1

# ============================================================
# Article 1 — L'Atelier Capsi
# ============================================================
step "Creating article 1: L'Atelier Capsi..."

read -r -d '' ARTICLE1 <<'EOF' || true
<!-- wp:paragraph {"className":"article-intro"} -->
<p class="article-intro">L'Atelier Capsi est une <strong>pépinière artisanale en Normandie</strong> entièrement dédiée à la culture de <strong>piments rares</strong> et de plantes aromatiques d'exception. Depuis 2018, nous sélectionnons, cultivons et expédions des graines et des plants issus de plus de 200 variétés du genre <em>Capsicum</em> — du plus doux au plus extrême, du plus courant au plus confidentiel. Découvrez notre démarche, nos valeurs et ce qui fait la singularité de notre atelier.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Une pépinière artisanale née d'une passion pour la biodiversité végétale</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>L'Atelier Capsi est né d'un constat simple&nbsp;: les variétés de piments accessibles au grand public ne représentent qu'une infime fraction de la diversité réelle du genre <em>Capsicum</em>. Des milliers de variétés existent à travers le monde — au Mexique, en Bolivie, aux Antilles, en Asie du Sud-Est — et la plupart restent totalement inconnues du jardinier européen.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Notre mission est de changer cela. En Normandie, dans une serre chauffée au cœur de la campagne, nous cultivons chaque année plus de 200 variétés issues des cinq espèces botaniques domestiquées du genre <em>Capsicum</em>&nbsp;: <em>C. annuum</em>, <em>C. chinense</em>, <em>C. frutescens</em>, <em>C. baccatum</em> et <em>C. pubescens</em>. Des variétés de collection, rares, authentiques — que vous ne trouverez nulle part ailleurs.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>De la graine au plant : un processus 100 % artisanal</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Chez nous, rien n'est industrialisé. Chaque lot de graines est produit à partir de nos propres plants parents, sélectionnés sur plusieurs générations pour leur <strong>typicité variétale</strong>, leur vigueur et la qualité de leurs fruits. Les semis démarrent en serre chauffée dès le mois de février&nbsp;; les jeunes plants sont repiqués, tuteurés et élevés pendant plusieurs semaines avant d'être conditionnés pour l'expédition.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Nos <strong>graines de piments</strong> sont récoltées à pleine maturité, séchées naturellement, triées à la main et conditionnées en sachets numérotés par lot. Chaque sachet indique l'espèce, la variété, l'année de récolte et le taux de germination constaté — systématiquement supérieur à 85 %. Une traçabilité complète, du semis à votre jardin.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>200 variétés de piments rares : notre collection en détail</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Notre catalogue couvre un spectre gustatif et botanique très large. Côté piments doux, vous trouverez des <strong>Pimentos</strong> espagnols, des Banana peppers américains ou encore des Bishops Crown en forme de couronne — parfaits pour la cuisine ou la décoration. Côté intermédiaire, nos <strong>Jalapeños</strong> de sélection, Serrano et Cayenne offrent une palette aromatique remarquable.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Pour les amateurs de sensations intenses, notre collection de <em>Capsicum chinense</em> comprend des <strong>Habaneros</strong> fruités des Caraïbes, des <strong>Scotch Bonnets</strong> jamaïcains, des <strong>Trinidad Moruga Scorpion</strong> et bien sûr le légendaire <strong>Carolina Reaper</strong> — le piment le plus fort du monde avec plus de 2 millions de Scoville. Des variétés rares qui attirent collectionneurs et passionnés de gastronomie pimentée du monde entier.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Plantes aromatiques rares : l'autre passion de l'Atelier</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Au fil des saisons, L'Atelier Capsi a élargi sa collection aux <strong>plantes aromatiques rares</strong>&nbsp;: thym citron (<em>Thymus citriodorus</em>), origan de Crète, basilic thaï, mélisse dorée, sarriette de montagne… Des variétés peu courantes que nous cultivons en serre avec le même soin et la même exigence de sélection que nos piments.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Piments et aromatiques partagent des conditions de culture similaires — chaleur, lumière généreuse, sol drainant — ce qui nous a naturellement conduits vers ces végétaux méditerranéens et tropicaux que nous élevons côte à côte dans notre serre normande.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Comment commander vos graines et plants de piments rares</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Notre boutique en ligne propose des <strong>graines de piments rares</strong> en sachets de 20 graines, ainsi que des <strong>plants de piments prêts à planter</strong> livrés en pot biodégradable avec une fiche culturale. Les stocks sont volontairement limités — chaque lot est produit en petite série — et certaines variétés s'épuisent dès les premières semaines de la saison.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Nous livrons en France métropolitaine et dans une partie de l'Union européenne (graines uniquement hors de France, conformément à la réglementation phytosanitaire). Toutes les commandes sont préparées et expédiées depuis notre atelier normand, directement par nos soins.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>« Parce que nous refusons de sacrifier la qualité à la quantité — chaque plant, chaque sachet de graines est le résultat d'une sélection patiente et d'une culture attentive, menée de A à Z dans notre atelier. »</p></blockquote>
<!-- /wp:quote -->
EOF

$WP post create \
  --post_type=post \
  --post_status=publish \
  --post_title="L'Atelier Capsi : pépinière artisanale de piments rares en Normandie" \
  --post_name="latelier-capsi-pepiniere-artisanale-piments-rares-normandie" \
  --post_content="$ARTICLE1" \
  --post_category="$(cat_id "Actualités")"

# ============================================================
# Article 2 — Origine des piments
# ============================================================
step "Creating article 2: Origine des piments..."

read -r -d '' ARTICLE2 <<'EOF' || true
<!-- wp:paragraph {"className":"article-intro"} -->
<p class="article-intro">Le piment est aujourd'hui l'une des épices les plus consommées au monde, présente dans presque toutes les cuisines — mexicaine, thaïe, indienne, nord-africaine, coréenne. Mais <strong>d'où viennent les piments</strong> ? Quelle est leur histoire ? Comment une plante américaine a-t-elle réussi à s'imposer sur tous les continents en moins de deux siècles ? Plongée dans l'histoire fascinante du genre <em>Capsicum</em>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Les piments sont nés en Amérique du Sud : le berceau du Capsicum</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Contrairement à une idée reçue, les piments ne sont pas originaires d'Asie ou d'Afrique — même si ces continents en sont aujourd'hui d'importants producteurs. Le genre <em>Capsicum</em> est originaire d'<strong>Amérique du Sud et centrale</strong>, plus précisément de la région qui correspond aujourd'hui à la Bolivie et au Pérou. C'est là que les espèces sauvages ancestrales du piment ont évolué il y a plusieurs millions d'années.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Les piments sauvages (<em>Capsicum</em> spp.) produisent naturellement de petits fruits rouges riches en capsaïcine — la molécule responsable de la sensation de brûlure. Cette capsaïcine a vraisemblablement évolué comme mécanisme de défense contre les mammifères, tout en attirant les oiseaux (insensibles à la capsaïcine) pour assurer la dispersion des graines.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>6 000 ans de domestication : les peuples précolombiens et le piment</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Les premières traces de <strong>domestication du piment</strong> remontent à plus de 6 000 ans avant notre ère. Des études archéologiques et génétiques menées au Mexique, en Équateur et au Pérou ont retrouvé des résidus de capsaïcine sur des ustensiles de cuisine préhistoriques, prouvant que le piment était déjà cultivé et consommé bien avant l'invention de l'écriture.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Pour les civilisations précolombiennes — Aztèques, Mayas, Incas — le piment n'était pas seulement un condiment&nbsp;: il était aussi une monnaie d'échange, un médicament et un élément rituel. Les Aztèques distinguaient des dizaines de variétés de piments, chacune avec un nom spécifique, des usages culinaires précis et une place définie dans leur pharmacopée.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Christophe Colomb et la diffusion mondiale du piment (1492)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>C'est Christophe Colomb qui introduit le piment en Europe en 1493, à son retour de son premier voyage en Amérique. Cherchant du <em>poivre noir</em> (<em>Piper nigrum</em>), il découvre à la place des plantes aux fruits brûlants que les peuples autochtones appellent <em>ají</em> ou <em>chili</em>. Il les ramène en Espagne sous le nom de «&nbsp;pimiento&nbsp;» — et c'est ainsi que commence l'une des plus grandes diffusions botaniques de l'histoire humaine.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>En moins de 50 ans, le piment se répand depuis l'Espagne jusqu'au Portugal, puis en Afrique, en Inde, en Asie du Sud-Est et en Chine — principalement par les routes commerciales portugaises. Sa culture s'adapte remarquablement bien aux climats chauds de ces régions, et le piment s'intègre presque immédiatement dans les cuisines locales, transformant définitivement des gastronomies entières.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Les 5 espèces Capsicum domestiquées : diversité et caractéristiques</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Le genre <em>Capsicum</em> compte plus de 30 espèces, dont cinq sont principalement cultivées dans le monde&nbsp;:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li><strong><em>Capsicum annuum</em></strong> — l'espèce la plus cultivée au monde. Elle regroupe la quasi-totalité des piments doux (poivrons, Pimentos) mais aussi des variétés piquantes très connues&nbsp;: Jalapeño, Cayenne, Paprika, Serrano, Ancho.</li>
<li><strong><em>Capsicum chinense</em></strong> — paradoxalement originaire d'Amazonie et non de Chine. C'est l'espèce des piments les plus forts du monde&nbsp;: Habanero, Scotch Bonnet, Ghost Pepper (Bhut Jolokia), Trinidad Moruga Scorpion, Carolina Reaper.</li>
<li><strong><em>Capsicum frutescens</em></strong> — espèce du Tabasco, des piments thaïs (<em>Prik kee noo</em>) et des petits piments d'Afrique de l'Ouest.</li>
<li><strong><em>Capsicum baccatum</em></strong> — très populaire en Amérique du Sud, notamment au Pérou. Comprend les piments Ají Amarillo, Ají Limon et les Bishop's Crown.</li>
<li><strong><em>Capsicum pubescens</em></strong> — la plus rare des cinq espèces cultivées. Reconnaissable à ses graines noires et ses fleurs violettes. Représentée par les variétés Rocoto et Manzano, cultivées en haute altitude dans les Andes.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Du Mexique à la Normandie : cultiver des piments rares en Europe</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Cultiver des <strong>piments rares en Europe</strong> représente un défi technique que L'Atelier Capsi a relevé depuis 2018. Les piments sont des plantes tropicales ou subtropicales qui exigent une longue saison chaude — souvent plus longue que ce que le climat normand peut offrir naturellement. La solution&nbsp;: démarrer les semis très tôt (dès janvier-février) en serre chauffée, puis prolonger la saison sous abri jusqu'aux premières gelées.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Les résultats sont là&nbsp;: des plants vigoureux, des fruits typiques, des taux de capsaïcine conformes à ceux des variétés originelles. La culture sous serre normande, loin d'être un compromis, permet même de mieux contrôler les conditions (température, humidité, arrosage) et d'obtenir des plants d'une qualité très supérieure à ceux produits à grande échelle dans les pays du Sud.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>« Comprendre d'où vient chaque variété, connaître son histoire et ses exigences culturales — c'est la base de notre travail à l'Atelier Capsi. Un piment n'est pas seulement une source de piquant&nbsp;: c'est une plante avec une identité, un territoire, une mémoire. »</p></blockquote>
<!-- /wp:quote -->
EOF

$WP post create \
  --post_type=post \
  --post_status=publish \
  --post_title="D'où viennent les piments ? Origine, histoire et espèces du Capsicum" \
  --post_name="origine-histoire-piments-especes-capsicum" \
  --post_content="$ARTICLE2" \
  --post_category="$(cat_id "Variétés")"

# ============================================================
# Featured images — placeholders (remplacer par les vraies images)
# ============================================================
step "Importing placeholder featured images..."

CONTENT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../content" && pwd)"
WPDIR="/var/www/html/wp-content/uploads/capsi-placeholders"
WP_CONTAINER="$(docker compose ps -q wordpress)"

docker exec "$WP_CONTAINER" mkdir -p "$WPDIR"
docker cp "${CONTENT_DIR}/placeholder-article-1.png" "${WP_CONTAINER}:${WPDIR}/placeholder-article-1.png"
docker cp "${CONTENT_DIR}/placeholder-article-2.png" "${WP_CONTAINER}:${WPDIR}/placeholder-article-2.png"

ART1_ID=$(post_id "latelier-capsi-pepiniere-artisanale-piments-rares-normandie")
ART2_ID=$(post_id "origine-histoire-piments-especes-capsicum")

$WP media import "${WPDIR}/placeholder-article-1.png" \
    --post_id="$ART1_ID" --featured_image \
    --title="[Placeholder] L'Atelier Capsi en Normandie — 1200×630"
$WP media import "${WPDIR}/placeholder-article-2.png" \
    --post_id="$ART2_ID" --featured_image \
    --title="[Placeholder] Origine des piments — 1200×630"
