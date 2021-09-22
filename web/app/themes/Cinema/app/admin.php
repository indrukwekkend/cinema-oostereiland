<?php

namespace App;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    // Add postMessage support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial('blogname', [
        'selector' => '.brand',
        'render_callback' => function () {
            bloginfo('name');
        }
    ]);
});

/**
 * Customizer JS
 * 
 * customize preview geeft admin toegang tot de customizer
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});

// Block editor geeft toegang tot de blok editor pagina's dus wordt dan alleen daar onderstaande JS ingeladen
// TODO: eigen js maken voor dit blok ipv de customizer te "misbruiken" (in webpack)
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/block-editor.js'), ['customize-preview'], null, true);
});
