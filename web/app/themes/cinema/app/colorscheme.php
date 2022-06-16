<?php 

	// Editor color palette.
	// let op dat dezelfde kleuren ook in de variables van de SCSS bestanden staan: resources/assets/styles/common/variables
	// namen kleuren aanpassen

$dark_gray = "#7d7c7c";
$neutral_gray = "#eaeae8";

$festival_grijs = "#f3f3f1";
$special_gray = "#eee8e2";
$button_gray =  "#bcaba2";
	
//basis kleuren
$black1  = '#000';
$wit  = '#fff';

// ToDo groottes fonts hier invullen

add_theme_support( 'editor-color-palette', array(
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
	array(
		'name'	=> __( 'donker grijs', 'indrukwekkend' ),
		'slug'	=> 'donker-grijs',
		'color'	=> $dark_gray,
	),
	array(
		'name'	=> __( 'neutraal grijs', 'indrukwekkend' ),
		'slug'	=> 'neutraal-grijs',
		'color'	=> $neutral_gray,
	),
	array(
		'name'	=> __( 'Oud roze', 'indrukwekkend' ),
		'slug'	=> 'button-grijs',
		'color'	=> $button_gray,
	),
	array(
		'name'	=> __( 'special grijs', 'indrukwekkend' ),
		'slug'	=> 'special-grijs',
		'color'	=> $special_gray,
	),
	array(
		'name'	=> __( 'festival grijs', 'indrukwekkend' ),
		'slug'	=> 'festival-grijs',
		'color'	=> $festival_grijs,
	),
));

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
