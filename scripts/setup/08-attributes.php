<?php
/**
 * Création des attributs produit WooCommerce et assignation aux produits démo.
 * Exécuté via: wp eval-file /tmp/capsi-attributes.php
 */

if ( ! function_exists( 'wc_create_attribute' ) ) {
    WP_CLI::error( 'WooCommerce non disponible.' );
}

// ── Helpers ───────────────────────────────────────────────────

function capsi_ensure_attribute( string $name, string $slug ): int {
    foreach ( wc_get_attribute_taxonomies() as $attr ) {
        if ( $attr->attribute_name === $slug ) {
            WP_CLI::log( "  attr existe : {$name}" );
            return (int) $attr->attribute_id;
        }
    }
    $id = wc_create_attribute( [
        'name'         => $name,
        'slug'         => $slug,
        'type'         => 'select',
        'order_by'     => 'name',
        'has_archives' => false,
    ] );
    if ( is_wp_error( $id ) ) {
        WP_CLI::warning( "  erreur attr '{$name}' : " . $id->get_error_message() );
        return 0;
    }
    // Enregistrer la taxonomie immédiatement
    $taxonomy = wc_attribute_taxonomy_name( $slug );
    if ( ! taxonomy_exists( $taxonomy ) ) {
        register_taxonomy( $taxonomy, 'product' );
    }
    WP_CLI::log( "  créé : {$name} (ID {$id})" );
    return $id;
}

function capsi_ensure_term( string $taxonomy, string $value ): int {
    if ( ! taxonomy_exists( $taxonomy ) ) {
        register_taxonomy( $taxonomy, 'product' );
    }
    $existing = get_term_by( 'name', $value, $taxonomy );
    if ( $existing ) {
        return $existing->term_id;
    }
    $result = wp_insert_term( $value, $taxonomy );
    if ( is_wp_error( $result ) ) {
        WP_CLI::warning( "  erreur terme '{$value}' dans '{$taxonomy}' : " . $result->get_error_message() );
        return 0;
    }
    return (int) $result['term_id'];
}

/**
 * @param int    $product_id
 * @param array  $attrs  [ [ 'slug' => 'pa_xxx'|'', 'name' => '...', 'values' => [...], 'position' => n ], ... ]
 */
function capsi_set_product_attributes( int $product_id, array $attrs ): void {
    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        WP_CLI::warning( "  produit {$product_id} introuvable" );
        return;
    }

    $objects = [];
    foreach ( $attrs as $data ) {
        $attr = new WC_Product_Attribute();
        $attr->set_position( $data['position'] );
        $attr->set_visible( true );
        $attr->set_variation( false );

        if ( ! empty( $data['slug'] ) ) {
            // Attribut taxonomie globale
            $taxonomy = wc_attribute_taxonomy_name( $data['slug'] );
            $attr_id  = wc_attribute_taxonomy_id_by_name( $data['slug'] );
            $attr->set_id( $attr_id );
            $attr->set_name( $taxonomy );

            $term_ids = [];
            foreach ( $data['values'] as $value ) {
                $tid = capsi_ensure_term( $taxonomy, $value );
                if ( $tid ) {
                    $term_ids[] = $tid;
                }
            }
            wp_set_object_terms( $product_id, $term_ids, $taxonomy );
            $attr->set_options( $term_ids );

        } else {
            // Attribut local (texte libre, non filtrable)
            $attr->set_id( 0 );
            $attr->set_name( $data['name'] );
            $attr->set_options( $data['values'] );
        }

        $objects[ sanitize_title( $data['name'] ) ] = $attr;
    }

    $product->set_attributes( $objects );
    $product->save();
    WP_CLI::success( "  attributs mis à jour : " . $product->get_name() );
}

// ── 1. Création des attributs globaux ─────────────────────────
WP_CLI::log( "\n=== Attributs globaux ===" );
capsi_ensure_attribute( 'Famille',           'famille' );
capsi_ensure_attribute( 'Espèce Capsicum',   'espece-capsicum' );
capsi_ensure_attribute( 'Origine',           'origine' );
capsi_ensure_attribute( 'Niveau de force',   'niveau-force' );
capsi_ensure_attribute( 'Couleur à maturité','couleur-maturite' );
capsi_ensure_attribute( 'Arôme principal',   'arome-principal' );

// Vider le cache taxonomies WC
delete_transient( 'wc_attribute_taxonomies' );

// ── 2. Assignation aux produits démo ──────────────────────────
WP_CLI::log( "\n=== Assignation produits ===" );

// Bhut Jolokia / Ghost Pepper (piment)
$bhut = get_page_by_path( 'plant-bhut-jolokia-ghost-pepper', OBJECT, 'product' );
if ( $bhut ) {
    capsi_set_product_attributes( $bhut->ID, [
        [ 'slug' => '',                 'name' => 'Nom latin',          'values' => ['Capsicum chinense'],           'position' => 0 ],
        [ 'slug' => 'famille',          'name' => 'Famille',            'values' => ['Solanaceae'],                  'position' => 1 ],
        [ 'slug' => 'espece-capsicum',  'name' => 'Espèce Capsicum',    'values' => ['Capsicum chinense'],           'position' => 2 ],
        [ 'slug' => 'origine',          'name' => 'Origine',            'values' => ['Assam, Inde'],                 'position' => 3 ],
        [ 'slug' => '',                 'name' => 'Contenu',            'values' => ['20 graines'],                  'position' => 4 ],
        [ 'slug' => '',                 'name' => 'Saison de semis',    'values' => ['Janvier – Mars'],              'position' => 5 ],
        [ 'slug' => '',                 'name' => 'Force (SHU)',        'values' => ['800 000 – 1 041 427 SHU'],     'position' => 6 ],
        [ 'slug' => 'niveau-force',     'name' => 'Niveau de force',    'values' => ['Extrême'],                     'position' => 7 ],
        [ 'slug' => 'couleur-maturite', 'name' => 'Couleur à maturité', 'values' => ['Rouge orangé'],               'position' => 8 ],
    ] );
}

// Carolina Reaper (piment)
$reaper = get_page_by_path( 'graines-carolina-reaper-20-graines', OBJECT, 'product' );
if ( $reaper ) {
    capsi_set_product_attributes( $reaper->ID, [
        [ 'slug' => '',                 'name' => 'Nom latin',          'values' => ['Capsicum chinense'],            'position' => 0 ],
        [ 'slug' => 'famille',          'name' => 'Famille',            'values' => ['Solanaceae'],                   'position' => 1 ],
        [ 'slug' => 'espece-capsicum',  'name' => 'Espèce Capsicum',    'values' => ['Capsicum chinense'],            'position' => 2 ],
        [ 'slug' => 'origine',          'name' => 'Origine',            'values' => ['Caroline du Sud, USA'],         'position' => 3 ],
        [ 'slug' => '',                 'name' => 'Contenu',            'values' => ['20 graines'],                   'position' => 4 ],
        [ 'slug' => '',                 'name' => 'Saison de semis',    'values' => ['Janvier – Février'],            'position' => 5 ],
        [ 'slug' => '',                 'name' => 'Force (SHU)',        'values' => ['1 500 000 – 2 200 000 SHU'],    'position' => 6 ],
        [ 'slug' => 'niveau-force',     'name' => 'Niveau de force',    'values' => ['Extrême'],                      'position' => 7 ],
        [ 'slug' => 'couleur-maturite', 'name' => 'Couleur à maturité', 'values' => ['Rouge'],                        'position' => 8 ],
    ] );
}

// Habanero Orange (piment)
$habanero = get_page_by_path( 'graines-habanero-orange-20-graines', OBJECT, 'product' );
if ( $habanero ) {
    capsi_set_product_attributes( $habanero->ID, [
        [ 'slug' => '',                 'name' => 'Nom latin',          'values' => ['Capsicum chinense'],           'position' => 0 ],
        [ 'slug' => 'famille',          'name' => 'Famille',            'values' => ['Solanaceae'],                  'position' => 1 ],
        [ 'slug' => 'espece-capsicum',  'name' => 'Espèce Capsicum',    'values' => ['Capsicum chinense'],           'position' => 2 ],
        [ 'slug' => 'origine',          'name' => 'Origine',            'values' => ['Yucatán, Mexique'],            'position' => 3 ],
        [ 'slug' => '',                 'name' => 'Contenu',            'values' => ['20 graines'],                  'position' => 4 ],
        [ 'slug' => '',                 'name' => 'Saison de semis',    'values' => ['Janvier – Mars'],              'position' => 5 ],
        [ 'slug' => '',                 'name' => 'Force (SHU)',        'values' => ['100 000 – 350 000 SHU'],       'position' => 6 ],
        [ 'slug' => 'niveau-force',     'name' => 'Niveau de force',    'values' => ['Très fort'],                   'position' => 7 ],
        [ 'slug' => 'couleur-maturite', 'name' => 'Couleur à maturité', 'values' => ['Orange'],                      'position' => 8 ],
    ] );
}

// Thym Citron (aromatique)
$thym = get_page_by_path( 'plant-thym-citron-variete-rare', OBJECT, 'product' );
if ( $thym ) {
    capsi_set_product_attributes( $thym->ID, [
        [ 'slug' => '',                'name' => 'Nom latin',       'values' => ['Thymus citriodorus'],   'position' => 0 ],
        [ 'slug' => 'famille',         'name' => 'Famille',         'values' => ['Lamiaceae'],            'position' => 1 ],
        [ 'slug' => 'origine',         'name' => 'Origine',         'values' => ['Méditerranée'],         'position' => 2 ],
        [ 'slug' => '',                'name' => 'Contenu',         'values' => ['Plant de 8-12 cm'],     'position' => 3 ],
        [ 'slug' => '',                'name' => 'Saison de semis', 'values' => ['Février – Avril'],      'position' => 4 ],
        [ 'slug' => 'arome-principal', 'name' => 'Arôme principal', 'values' => ['Citron'],               'position' => 5 ],
    ] );
}

// ── 3. Conseils de culture ────────────────────────────────────
WP_CLI::log( "\n=== Conseils de culture ===" );

$conseils = [
    'plant-bhut-jolokia-ghost-pepper' => "Semez les graines en serre chauffée entre janvier et mars, à une température de sol de 25-28°C. La levée prend 2 à 4 semaines — la patience est de mise avec cette espèce.\n\n<h3>Semis et repiquage</h3>\nRempotez les plantules dès l'apparition des deux premières vraies feuilles, dans un mélange terreau + sable (70/30). Maintenez une température nocturne d'au moins 18°C.\n\n<h3>Culture et fructification</h3>\nCultivez impérativement en serre ou sous tunnel. Arrosez régulièrement à la base, sans mouiller le feuillage. La fructification débute en juillet-août ; les fruits mûrissent de septembre à octobre. Tuteurez les tiges dès 30 cm de hauteur.\n\n<h3>Attention</h3>\nPortez des gants lors de la manipulation des fruits. La capsaïcine du Bhut Jolokia est extrêmement concentrée — évitez tout contact avec les yeux ou les muqueuses.",

    'graines-carolina-reaper-20-graines' => "Le Carolina Reaper est l'un des piments les plus exigeants à cultiver — mais aussi l'un des plus gratifiants. Prévoyez une saison longue et une chaleur constante.\n\n<h3>Semis</h3>\nSemez dès janvier-février en serre chauffée à 28-30°C. La germination peut prendre 3 à 5 semaines. Utilisez une mini-serre avec tapis chauffant pour de meilleurs résultats.\n\n<h3>Croissance</h3>\nMaintenez une température diurne de 25-30°C et nocturne d'au moins 20°C. Rempotez progressivement jusqu'à un pot de 10-15 litres. Fertilisez toutes les deux semaines avec un engrais riche en potassium à partir de la floraison.\n\n<h3>Récolte</h3>\nLes fruits sont prêts à récolter 150 à 200 jours après le semis, lorsqu'ils passent du vert au rouge vif. Portez impérativement des gants épais et évitez de vous toucher le visage.",

    'graines-habanero-orange-20-graines' => "L'Habanero Orange est plus accessible que les piments ultra-forts, mais demande tout de même chaleur et soleil pour exprimer tout son potentiel aromatique.\n\n<h3>Semis et plantation</h3>\nSemez en serre chauffée entre janvier et mars. Repiquez en pleine terre ou en pot de 8-10 litres après les dernières gelées, ou maintenez en serre toute la saison.\n\n<h3>Entretien</h3>\nArrosez régulièrement sans excès. Un paillage au pied du plant conserve l'humidité et limite les adventices. Pincez les premières fleurs pour favoriser une ramification dense.\n\n<h3>Récolte</h3>\nLes fruits passent du vert à l'orange vif à maturité complète, en août-septembre. Cueillez-les à ce stade pour un maximum d'arôme fruité et de piquant.",

    'plant-thym-citron-variete-rare' => "Le Thym Citron est une plante robuste et peu exigeante, parfaite en pot sur un balcon ensoleillé comme en pleine terre.\n\n<h3>Plantation</h3>\nInstallez le plant dans un substrat très drainant (terreau + sable ou gravillon). Le Thym Citron redoute les excès d'eau bien plus que la sécheresse. Choisissez un emplacement en plein soleil.\n\n<h3>Arrosage et entretien</h3>\nArrosez modérément, uniquement lorsque le substrat est sec en surface. En pot, veillez à ce que l'eau ne stagne jamais dans la soucoupe. Taillez légèrement après chaque floraison pour maintenir une forme compacte.\n\n<h3>Récolte</h3>\nCueillez les tiges fraîches au fur et à mesure des besoins, de préférence le matin. Le parfum est maximum juste avant la floraison. Séchez les tiges à l'ombre pour une conservation longue durée.",
];

foreach ( $conseils as $slug => $text ) {
    $post = get_page_by_path( $slug, OBJECT, 'product' );
    if ( $post ) {
        update_post_meta( $post->ID, '_capsi_conseils', $text );
        WP_CLI::log( "  conseils mis à jour : {$slug}" );
    }
}

// ── 4. Utilisation ────────────────────────────────────────────
WP_CLI::log( "\n=== Utilisation ===" );

$utilisations = [
    'plant-bhut-jolokia-ghost-pepper' => "Le Bhut Jolokia est avant tout un piment de défi — réservé aux amateurs de sensations fortes et aux cuisines qui savent dompter la capsaïcine.\n\n<h3>En cuisine</h3>\nUtilisez-le avec une extrême parcimonie : une infime quantité suffit à relever un plat entier. Il entre dans la composition de sauces piquantes ultra-hot, de marinades pour viandes grillées, ou de condiments artisanaux. Séché et réduit en poudre, il colore et enflamme les currys, bouillons ou chocolats noirs.\n\n<h3>Conservation</h3>\nSéchez les fruits à basse température (50°C, 24-48 h) pour les conserver plusieurs mois. Congelez-les entiers à défaut de déshydrateur. Manipulez toujours avec des gants — la capsaïcine imprègne les surfaces.",

    'graines-carolina-reaper-20-graines' => "Le Carolina Reaper détient le record Guinness du piment le plus fort du monde. Son utilisation culinaire est confidentielle, mais réelle chez les puristes du piquant.\n\n<h3>En cuisine</h3>\nUne fraction de fruit suffit à parfumer une grande quantité de sauce. On le retrouve dans les sauces \"Death Wish\", les poudres de piment extrêmes, les condiments pour chili ultra-relevé. Son arôme fruité, presque sucré, précède une chaleur explosive — exploitez ce contraste dans les desserts au chocolat ou les chutneys.\n\n<h3>Mise en garde</h3>\nDéconseillé aux personnes souffrant de problèmes gastriques ou cardiaques. Ne jamais manipuler sans gants ni lunettes de protection. Tenez éloigné des enfants.",

    'graines-habanero-orange-20-graines' => "L'Habanero Orange est l'un des piments aromatiques par excellence — son parfum fruité et floral en fait un ingrédient de choix pour les cuisines mexicaines, caribéennes et antillaises.\n\n<h3>En cuisine</h3>\nIdéal pour les sauces hot maison, les marinades de poulet ou de crevettes, les salsas et les chutneys. Son arôme d'abricot et de fleur d'oranger se marie parfaitement avec la mangue, l'ananas ou le citron vert. Retirez les graines et les membranes pour modérer l'intensité tout en conservant l'arôme.\n\n<h3>Conservation</h3>\nConservez les fruits frais au réfrigérateur jusqu'à 2 semaines. Ils se congèlent entiers ou en lamelles. Préparez-en une sauce lacto-fermentée pour un condiment qui se garde des mois.",

    'plant-thym-citron-variete-rare' => "Le Thym Citron est l'herbe aromatique la plus polyvalente du jardin : ses feuilles parfumées s'invitent aussi bien en cuisine qu'en infusion ou en soins naturels.\n\n<h3>En cuisine</h3>\nCiseler les feuilles fraîches sur des poissons, volailles, légumes rôtis ou pâtes. Son arôme citronné apporte fraîcheur et légèreté, contrairement au thym ordinaire. Intégrez-le dans des beurres aromatisés, des huiles infusées ou des vinaigrettes. Associez-le au citron confit, aux olives ou aux fromages frais.\n\n<h3>En infusion</h3>\nUne poignée de tiges fraîches dans de l'eau chaude (non bouillante) donne une tisane digestive et antioxydante. Ajoutez du miel pour adoucir.\n\n<h3>Autres usages</h3>\nLes bouquets séchés parfument le linge et éloignent les insectes. L'huile essentielle de thym citron est utilisée en aromathérapie pour ses propriétés relaxantes.",
];

foreach ( $utilisations as $slug => $text ) {
    $post = get_page_by_path( $slug, OBJECT, 'product' );
    if ( $post ) {
        update_post_meta( $post->ID, '_capsi_utilisation', $text );
        WP_CLI::log( "  utilisation mis à jour : {$slug}" );
    }
}

WP_CLI::log( "\nFait." );
