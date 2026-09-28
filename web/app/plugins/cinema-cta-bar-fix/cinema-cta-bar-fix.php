<?php
/**
 * Plugin Name: Cinema CTA Bar Fix
 * Description: Voorkomt dat filmpagina-tekst onder de sticky titelbalk verdwijnt bij scrollen.
 * Version: 2.0.0
 * Author: Indrukwekkend
 * Author URI: https://indrukwekkend.nl
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cinema_cta_bar_fix_enqueue_assets() {
	if ( ! is_singular( 'films' ) ) {
		return;
	}

	$plugin_dir = plugin_dir_path( __FILE__ );
	$plugin_url = plugin_dir_url( __FILE__ );

	wp_enqueue_style(
		'cinema-cta-bar-fix',
		$plugin_url . 'assets/cta-bar-fix.css',
		array(),
		filemtime( $plugin_dir . 'assets/cta-bar-fix.css' )
	);

	wp_enqueue_script(
		'cinema-cta-bar-fix',
		$plugin_url . 'assets/cta-bar-fix.js',
		array(),
		filemtime( $plugin_dir . 'assets/cta-bar-fix.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'cinema_cta_bar_fix_enqueue_assets', 999 );
