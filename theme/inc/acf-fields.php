<?php
/**
 * Groupe de champs ACF — Fiche botanique produit WooCommerce.
 * Enregistrement programmatique, pas d'import/export JSON requis.
 */
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( [
    'key'                   => 'group_capsi_product',
    'title'                 => 'Fiche botanique',
    'fields'                => [

        // ── Onglet : Identité ─────────────────────────────────────
        [
            'key'       => 'field_capsi_tab_identite',
            'label'     => 'Identité',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'          => 'field_capsi_nom_latin',
            'label'        => 'Nom latin',
            'name'         => 'capsi_nom_latin',
            'type'         => 'text',
            'instructions' => 'Genre et espèce en italique sur la fiche.',
            'placeholder'  => 'Capsicum annuum',
        ],
        [
            'key'          => 'field_capsi_famille',
            'label'        => 'Famille botanique',
            'name'         => 'capsi_famille',
            'type'         => 'select',
            'instructions' => 'Utilisé comme filtre en boutique.',
            'choices'      => [
                'Solanaceae' => 'Solanaceae',
                'Lamiaceae'  => 'Lamiaceae',
                'Apiaceae'   => 'Apiaceae',
                'Myrtaceae'  => 'Myrtaceae',
                'Rosaceae'   => 'Rosaceae',
            ],
            'allow_null'   => 1,
            'ui'           => 1,
        ],
        [
            'key'          => 'field_capsi_espece',
            'label'        => 'Espèce Capsicum',
            'name'         => 'capsi_espece',
            'type'         => 'select',
            'instructions' => 'À renseigner uniquement pour les piments.',
            'choices'      => [
                'Capsicum chinense'   => 'Capsicum chinense',
                'Capsicum annuum'     => 'Capsicum annuum',
                'Capsicum frutescens' => 'Capsicum frutescens',
                'Capsicum baccatum'   => 'Capsicum baccatum',
                'Capsicum pubescens'  => 'Capsicum pubescens',
            ],
            'allow_null'   => 1,
            'ui'           => 1,
        ],
        [
            'key'         => 'field_capsi_origine',
            'label'       => 'Origine géographique',
            'name'        => 'capsi_origine',
            'type'        => 'text',
            'placeholder' => 'Ex. : Assam, Inde',
        ],

        // ── Onglet : Conditionnement ──────────────────────────────
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
            'label'       => 'Saison de semis',
            'name'        => 'capsi_saison_semis',
            'type'        => 'text',
            'placeholder' => 'Ex. : Janvier – Mars',
        ],

        // ── Onglet : Force & Apparence ────────────────────────────
        [
            'key'       => 'field_capsi_tab_force',
            'label'     => 'Force & Apparence',
            'type'      => 'tab',
            'placement' => 'top',
        ],
        [
            'key'          => 'field_capsi_force_shu',
            'label'        => 'Force (SHU)',
            'name'         => 'capsi_force_shu',
            'type'         => 'text',
            'instructions' => 'Fourchette en unités Scoville. Laisser vide pour les aromatiques.',
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
            ],
            'allow_null' => 1,
            'ui'         => 1,
        ],
        [
            'key'        => 'field_capsi_arome',
            'label'      => 'Arôme principal',
            'name'       => 'capsi_arome',
            'type'       => 'select',
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

        // ── Onglet : Textes ───────────────────────────────────────
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
