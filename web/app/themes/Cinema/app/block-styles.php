<?php
/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 * 
 * LET OP:
 * Er is een tweede manier om styles te registreren. In WP 5.6 gaat het met onderstaande methode nog niet
 * goed bij de buttons. Ik heb geen probelemen met onderstaande blokken.
 * de alternatieve methode is in JS. In app/admin bestand voor block editor toegevoegd: block-editor.js
 *
 */

if ( function_exists( 'register_block_style' ) ) {
	/**
	 * Register block styles.
	 *
	 * @since Twenty Twenty-One 1.0
	 *
	 * @return void
	 */
	function twenty_twenty_one_register_block_styles() {


		// Columns: zonder marge.
		register_block_style(
			'core/columns',
			array(
				'name'  => 'indrukwekkend-columns-zonder',
				'label' => esc_html__( 'Zonder ruimte', 'indrukwekkend' ),
			)
		);

		// Cover: Borders.
		register_block_style(
			'core/cover',
			array(
				'name'  => 'indrukwekkend-border',
				'label' => esc_html__( 'Borders', 'indrukwekkend' ),
			)
		);

		// Group: Borders.
		register_block_style(
			'core/group',
			array(
				'name'  => 'indrukwekkend-regisseur',
				'label' => esc_html__( 'Regisseur blok', 'indrukwekkend' ),
			)
		);

		
		// Image: Borders.
		register_block_style(
			'core/image',
			array(
				'name'  => 'indrukwekkend-border',
				'label' => esc_html__( 'Borders', 'indrukwekkend' ),
			)
		);

		// Image: Frame. Wat doet deze? omschrijf
		register_block_style(
			'core/image',
			array(
				'name'  => 'indrukwekkend-image-frame',
				'label' => esc_html__( 'Frame', 'indrukwekkend' ),
			)
		);

		// Latest Posts: Dividers.
		register_block_style(
			'core/latest-posts',
			array(
				'name'  => 'indrukwekkend-latest-posts-dividers',
				'label' => esc_html__( 'Dividers', 'indrukwekkend' ),
			)
		);

		// Latest Posts: Borders.
		register_block_style(
			'core/latest-posts',
			array(
				'name'  => 'indrukwekkend-latest-posts-borders',
				'label' => esc_html__( 'Borders', 'indrukwekkend' ),
			)
		);

		// Media & Text: Regisseur.
		register_block_style(
			'core/media-text',
			array(
				'name'  => 'indrukwekkend-regisseur',
				'label' => esc_html__( 'Regisseur', 'indrukwekkend' ),
			)
		);

		// Media & Text: Grote afbeelding.
		register_block_style(
			'core/media-text',
			array(
				'name'  => 'indrukwekkend-afbeelding',
				'label' => esc_html__( 'Grote afbeelding', 'indrukwekkend' ),
			)
		);

		// Separator: Thick.
		register_block_style(
			'core/separator',
			array(
				'name'  => 'indrukwekkend-separator-thick',
				'label' => esc_html__( 'Thick', 'indrukwekkend' ),
			)
		);

		// Social icons: Dark gray color.
		register_block_style(
			'core/social-links',
			array(
				'name'  => 'indrukwekkend-social-icons-color',
				'label' => esc_html__( 'Dark gray', 'indrukwekkend' ),
			)
		);

		// INDRUKWEKKEND BLOKKEN
		register_block_style(
			'indrukwekkend/header-achtergrond',
			array(
				'name'  => 'indrukwekkend-klant',
				'label' => esc_html__( 'Klant Naam', 'indrukwekkend' ),
			)
		);
	}
	add_action( 'init', 'twenty_twenty_one_register_block_styles' );
}
