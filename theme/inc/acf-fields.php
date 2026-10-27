<?php
/**
 * Groupe de champs ACF — Fiche botanique produit WooCommerce.
 * Enregistrement programmatique — pas d'import JSON requis.
 */
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( [
    'key'                   => 'group_capsi_product',
    'title'                 => 'Fiche botanique',
    'fields'                => [

        // ═══════════════════════════════════════════════════════
        // Onglet : Identité
        // ═══════════════════════════════════════════════════════
        [
            'key'       => 'field_capsi_tab_identite',
            'label'     => 'Identité',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'        => 'field_capsi_type',
            'label'      => 'Type',
            'name'       => 'capsi_type',
            'type'       => 'select',
            'choices'    => [
                'Piment'           => 'Piment',
                'Aromatique'       => 'Aromatique',
                'Plante vivace'    => 'Plante vivace',
                'Annuelle'         => 'Annuelle',
                'Arbuste'          => 'Arbuste',
                'Bulbe'            => 'Bulbe',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'         => 'field_capsi_nom',
            'label'       => 'Nom',
            'name'        => 'capsi_nom',
            'type'        => 'text',
            'placeholder' => 'Ex. : Piment Fantôme',
        ],
        [
            'key'         => 'field_capsi_nom_secondaire',
            'label'       => 'Nom secondaire',
            'name'        => 'capsi_nom_secondaire',
            'type'        => 'text',
            'placeholder' => 'Ex. : Ghost Pepper, Naga Jolokia',
        ],
        [
            'key'          => 'field_capsi_nom_latin',
            'label'        => 'Nom latin',
            'name'         => 'capsi_nom_latin',
            'type'         => 'text',
            'instructions' => 'Genre + espèce, affiché en italique sur la fiche.',
            'placeholder'  => 'Ex. : Capsicum chinense',
        ],
        [
            'key'          => 'field_capsi_famille',
            'label'        => 'Famille',
            'name'         => 'capsi_famille',
            'type'         => 'select',
            'instructions' => 'Famille botanique — utilisée comme filtre en boutique.',
            'choices'      => [
                'Solanaceae'   => 'Solanaceae',
                'Lamiaceae'    => 'Lamiaceae',
                'Apiaceae'     => 'Apiaceae',
                'Myrtaceae'    => 'Myrtaceae',
                'Rosaceae'     => 'Rosaceae',
                'Asteraceae'   => 'Asteraceae',
                'Liliaceae'    => 'Liliaceae',
                'Alliaceae'    => 'Alliaceae',
            ],
            'allow_null'   => 1,
            'ui'           => 1,
        ],
        [
            'key'         => 'field_capsi_genre',
            'label'       => 'Genre',
            'name'        => 'capsi_genre',
            'type'        => 'text',
            'placeholder' => 'Ex. : Capsicum, Thymus, Ocimum',
        ],
        [
            'key'          => 'field_capsi_espece',
            'label'        => 'Espèce',
            'name'         => 'capsi_espece',
            'type'         => 'select',
            'instructions' => 'Espèce botanique complète.',
            'choices'      => [
                'Capsicum chinense'   => 'Capsicum chinense',
                'Capsicum annuum'     => 'Capsicum annuum',
                'Capsicum frutescens' => 'Capsicum frutescens',
                'Capsicum baccatum'   => 'Capsicum baccatum',
                'Capsicum pubescens'  => 'Capsicum pubescens',
                'Thymus citriodorus'  => 'Thymus citriodorus',
                'Thymus vulgaris'     => 'Thymus vulgaris',
                'Ocimum basilicum'    => 'Ocimum basilicum',
            ],
            'allow_null'   => 1,
            'ui'           => 1,
        ],
        [
            'key'         => 'field_capsi_variete',
            'label'       => 'Variété / Cultivar',
            'name'        => 'capsi_variete',
            'type'        => 'text',
            'placeholder' => 'Ex. : Ghost Pepper Red, Lemon',
        ],
        [
            'key'         => 'field_capsi_origine',
            'label'       => 'Origine géographique',
            'name'        => 'capsi_origine',
            'type'        => 'text',
            'placeholder' => 'Ex. : Assam, Inde',
        ],
        [
            'key'          => 'field_capsi_force_shu',
            'label'        => 'Force (SHU)',
            'name'         => 'capsi_force_shu',
            'type'         => 'text',
            'instructions' => 'Fourchette en unités Scoville. Laisser vide pour les non-piments.',
            'placeholder'  => 'Ex. : 800 000 – 1 041 427 SHU',
        ],
        [
            'key'        => 'field_capsi_niveau_force',
            'label'      => 'Niveau de force',
            'name'       => 'capsi_niveau_force',
            'type'       => 'select',
            'choices'    => [
                'Doux'      => 'Doux',
                'Moyen'     => 'Moyen',
                'Fort'      => 'Fort',
                'Très fort' => 'Très fort',
                'Extrême'   => 'Extrême',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],

        // ═══════════════════════════════════════════════════════
        // Onglet : Apparence
        // ═══════════════════════════════════════════════════════
        [
            'key'       => 'field_capsi_tab_apparence',
            'label'     => 'Apparence',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'        => 'field_capsi_couleur',
            'label'      => 'Couleur à maturité',
            'name'       => 'capsi_couleur',
            'type'       => 'select',
            'choices'    => [
                'Rouge'        => 'Rouge',
                'Rouge orangé' => 'Rouge orangé',
                'Orange'       => 'Orange',
                'Jaune'        => 'Jaune',
                'Vert'         => 'Vert',
                'Marron'       => 'Marron',
                'Pourpre'      => 'Pourpre',
                'Blanc crème'  => 'Blanc crème',
                'Noir'         => 'Noir',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'         => 'field_capsi_couleur_floraison',
            'label'       => 'Couleur de la floraison',
            'name'        => 'capsi_couleur_floraison',
            'type'        => 'text',
            'placeholder' => 'Ex. : Blanc, Violet, Jaune',
        ],
        [
            'key'         => 'field_capsi_periode_floraison',
            'label'       => 'Période de floraison',
            'name'        => 'capsi_periode_floraison',
            'type'        => 'text',
            'placeholder' => 'Ex. : Juin – Septembre',
        ],
        [
            'key'        => 'field_capsi_persistance_feuillage',
            'label'      => 'Persistance du feuillage',
            'name'       => 'capsi_persistance_feuillage',
            'type'       => 'select',
            'choices'    => [
                'Persistant'       => 'Persistant',
                'Semi-persistant'  => 'Semi-persistant',
                'Caduc'            => 'Caduc',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'         => 'field_capsi_couleur_feuillage',
            'label'       => 'Couleur du feuillage',
            'name'        => 'capsi_couleur_feuillage',
            'type'        => 'text',
            'placeholder' => 'Ex. : Vert foncé brillant',
        ],
        [
            'key'         => 'field_capsi_parfum',
            'label'       => 'Parfum',
            'name'        => 'capsi_parfum',
            'type'        => 'text',
            'placeholder' => 'Ex. : Citronné et légèrement mentholé',
        ],
        [
            'key'         => 'field_capsi_hauteur',
            'label'       => 'Hauteur à maturité',
            'name'        => 'capsi_hauteur',
            'type'        => 'text',
            'placeholder' => 'Ex. : 60 – 100 cm',
        ],
        [
            'key'         => 'field_capsi_forme_fleurs',
            'label'       => 'Forme des fleurs',
            'name'        => 'capsi_forme_fleurs',
            'type'        => 'text',
            'placeholder' => 'Ex. : Étoilée à 5 pétales',
        ],
        [
            'key'         => 'field_capsi_largeur',
            'label'       => 'Largeur / Diamètre',
            'name'        => 'capsi_largeur',
            'type'        => 'text',
            'placeholder' => 'Ex. : 40 – 60 cm',
        ],
        [
            'key'        => 'field_capsi_arome',
            'label'      => 'Arôme principal (fruit)',
            'name'       => 'capsi_arome',
            'type'       => 'select',
            'instructions' => 'Arôme gustatif du fruit — piments principalement.',
            'choices'    => [
                'Citron'  => 'Citron',
                'Fruité'  => 'Fruité',
                'Floral'  => 'Floral',
                'Terreux' => 'Terreux',
                'Fumé'    => 'Fumé',
                'Herbacé' => 'Herbacé',
                'Sucré'   => 'Sucré',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],

        // ═══════════════════════════════════════════════════════
        // Onglet : Besoins
        // ═══════════════════════════════════════════════════════
        [
            'key'       => 'field_capsi_tab_besoins',
            'label'     => 'Besoins',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'        => 'field_capsi_resistance_froid',
            'label'      => 'Résistance au froid',
            'name'       => 'capsi_resistance_froid',
            'type'       => 'select',
            'choices'    => [
                'Fragile (> 10°C)'       => 'Fragile (> 10°C)',
                'Sensible (0 – 10°C)'    => 'Sensible (0 – 10°C)',
                'Rustique (-5 – 0°C)'    => 'Rustique (-5 – 0°C)',
                'Très rustique (< -5°C)' => 'Très rustique (< -5°C)',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'        => 'field_capsi_ensoleillement',
            'label'      => 'Ensoleillement',
            'name'       => 'capsi_ensoleillement',
            'type'       => 'select',
            'choices'    => [
                'Plein soleil' => 'Plein soleil',
                'Mi-ombre'     => 'Mi-ombre',
                'Ombre'        => 'Ombre',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'        => 'field_capsi_niveau_soin',
            'label'      => 'Niveau de soin',
            'name'       => 'capsi_niveau_soin',
            'type'       => 'select',
            'choices'    => [
                'Facile'  => 'Facile',
                'Moyen'   => 'Moyen',
                'Expert'  => 'Expert',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'         => 'field_capsi_type_sol',
            'label'       => 'Type de sol',
            'name'        => 'capsi_type_sol',
            'type'        => 'text',
            'placeholder' => 'Ex. : Léger et bien drainé',
        ],
        [
            'key'        => 'field_capsi_nature_sol',
            'label'      => 'Nature du sol',
            'name'       => 'capsi_nature_sol',
            'type'       => 'select',
            'choices'    => [
                'Acide'        => 'Acide',
                'Neutre'       => 'Neutre',
                'Alcalin'      => 'Alcalin',
                'Indifférent'  => 'Indifférent',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'         => 'field_capsi_parfait_pour',
            'label'       => 'Parfait pour',
            'name'        => 'capsi_parfait_pour',
            'type'        => 'text',
            'placeholder' => 'Ex. : Serre, pot, balcon ensoleillé',
        ],
        [
            'key'        => 'field_capsi_tolerance_secheresse',
            'label'      => 'Tolérance à la sécheresse',
            'name'       => 'capsi_tolerance_secheresse',
            'type'       => 'select',
            'choices'    => [
                'Faible'      => 'Faible',
                'Moyenne'     => 'Moyenne',
                'Bonne'       => 'Bonne',
                'Excellente'  => 'Excellente',
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],

        // ═══════════════════════════════════════════════════════
        // Onglet : Conditionnement
        // ═══════════════════════════════════════════════════════
        [
            'key'       => 'field_capsi_tab_conditionnement',
            'label'     => 'Conditionnement',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'          => 'field_capsi_contenu',
            'label'        => 'Contenu',
            'name'         => 'capsi_contenu',
            'type'         => 'text',
            'instructions' => 'Ce que contient le sachet ou le pot.',
            'placeholder'  => 'Ex. : 20 graines  /  Plant de 8-12 cm',
        ],
        [
            'key'         => 'field_capsi_saison_semis',
            'label'       => 'Saison de semis / plantation',
            'name'        => 'capsi_saison_semis',
            'type'        => 'text',
            'placeholder' => 'Ex. : Janvier – Mars',
        ],

        // ═══════════════════════════════════════════════════════
        // Onglet : Textes
        // ═══════════════════════════════════════════════════════
        [
            'key'       => 'field_capsi_tab_textes',
            'label'     => 'Textes',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'          => 'field_capsi_utilisation',
            'label'        => 'Utilisation',
            'name'         => 'capsi_utilisation',
            'type'         => 'wysiwyg',
            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 0,
            'instructions' => 'Affiché dans l\'onglet "Utilisation" de la fiche produit.',
        ],
        [
            'key'          => 'field_capsi_conseils',
            'label'        => 'Conseils de culture',
            'name'         => 'capsi_conseils',
            'type'         => 'wysiwyg',
            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 0,
            'instructions' => 'Affiché dans l\'onglet "Conseils" de la fiche produit.',
        ],
    ],
    'location'              => [
        [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ] ],
    ],
    'position'              => 'normal',
    'style'                 => 'default',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
] );
