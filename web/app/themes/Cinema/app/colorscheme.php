<?php 

	// Editor color palette.
	// let op dat dezelfde kleuren ook in de variables van de SCSS bestanden staan: resources/assets/styles/common/variables
	// namen kleuren aanpassen
	

// $blue= "#34b6b5";
// $licht_blauw= "#d4f8f8";

// $paars= "#662483";
// $licht_paars= "#e1d2e8";

// $oranje= "#f39200";
// $licht_oranje= "#f3e2c9";

$light_green = '#f4f5f0';
$bright_green = "#a8c816";
$dark_green = "#626844";
$neutral_gray = "#c7c7c7";
	
//basis kleuren
$black1  = '#000';
$wit  = '#fff';

// ToDo groottes fonts hier invullen

add_theme_support( 'editor-color-palette', array(

	array(
		'name'	=> __( 'groen', 'indrukwekkend' ),
		'slug'	=> 'groen',
		'color'	=> $bright_green,
	),
	array(
		'name'	=> __( 'licht-groen', 'indrukwekkend' ),
		'slug'	=> 'licht-groen',
		'color'	=> $light_green,
	),
	array(
		'name'	=> __( 'donker-groen', 'indrukwekkend' ),
		'slug'	=> 'donker-groen',
		'color'	=> $dark_green,
	),
	array(
		'name'	=> __( 'Wit', 'indrukwekkend' ),
		'slug'	=> 'wit',
		'color'	=> $wit,
	),
	array(
		'name'	=> __( 'Zwart', 'indrukwekkend' ),
		'slug'	=> 'zwart',
		'color'	=> $black1,
	),
) );

// -- Disable Custom Colors
add_theme_support( 'disable-custom-colors' );


// Adds support for editor font sizes.
add_theme_support(
	'editor-font-sizes',
	array(
		array(
			'name'      => esc_html__( 'Extra small', 'indrukwekkend' ),
			'shortName' => esc_html_x( 'XS', 'Font size', 'indrukwekkend' ),
			'size'      => 16,
			'slug'      => 'extra-small',
		),
		array(
			'name'      => esc_html__( 'Small', 'indrukwekkend' ),
			'shortName' => esc_html_x( 'S', 'Font size', 'indrukwekkend' ),
			'size'      => 18,
			'slug'      => 'small',
		),
		array(
			'name'      => esc_html__( 'Normal', 'indrukwekkend' ),
			'shortName' => esc_html_x( 'M', 'Font size', 'indrukwekkend' ),
			'size'      => 20,
			'slug'      => 'normal',
		),
		array(
			'name'      => esc_html__( 'Large', 'indrukwekkend' ),
			'shortName' => esc_html_x( 'L', 'Font size', 'indrukwekkend' ),
			'size'      => 24,
			'slug'      => 'large',
		),
		array(
			'name'      => esc_html__( 'Extra large', 'indrukwekkend' ),
			'shortName' => esc_html_x( 'XL', 'Font size', 'indrukwekkend' ),
			'size'      => 36,
			'slug'      => 'extra-large',
		),
		array(
			'name'      => esc_html__( 'Huge', 'indrukwekkend' ),
			'shortName' => esc_html_x( 'XXL', 'Font size', 'indrukwekkend' ),
			'size'      => 62,
			'slug'      => 'huge',
		),
		// array(
		// 	'name'      => esc_html__( 'Gigantic', 'indrukwekkend' ),
		// 	'shortName' => esc_html_x( 'XXXL', 'Font size', 'indrukwekkend' ),
		// 	'size'      => 144,
		// 	'slug'      => 'gigantic',
		// ),
	)
);


// --Disable Yoast Love letter -->
add_action( 'template_redirect', function () {
 
	if ( ! class_exists( 'WPSEO_Frontend' ) ) {
			return;
	}

	$instance = WPSEO_Frontend::get_instance();

	// make sure, future version of the plugin does not break our site.
	if ( ! method_exists( $instance, 'debug_mark') ) {
			return ;
	}

	// ok, let us remove the love letter.
	 remove_action( 'wpseo_head', array( $instance, 'debug_mark' ), 2 );
}, 9999 );

?>
