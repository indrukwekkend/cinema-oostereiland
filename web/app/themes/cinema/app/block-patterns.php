<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Block Patterns
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_pattern/
 * @link https://developer.wordpress.org/reference/functions/register_block_pattern_category/
 *
 * @package WordPress
 * @subpackage StarterTheme
 * @since Indrukwekkend 1.0
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'indrukwekkend',
		array( 'label' => esc_html__( 'Indrukwekkend', 'indrukwekkend' ) )
	);
}

/**
 * Verwijder optioneel de Core Block patterns als we die niet toegankelijk willen maken voor de eindgebruikers:
 */

remove_theme_support( 'core-block-patterns' );

/**
 * Register Block Patterns.
 */
add_action('after_setup_theme', function () {

	// Media & Text Article Title.
	register_block_pattern(
		'indrukwekkend/media-text-article-title',
		array(
			'title'         => esc_html__( 'Regisseur blok', 'indrukwekkend' ),
			'categories'    => array( 'indrukwekkend' ),
			'viewportWidth' => 1024, //align wide voor de inserter?
			'description'   => esc_html_x( 'Een Media en Tekst blok met een grote afbeelding, titel en een verklarende tekst. Hieraan is een slide effect toegevoegd', 'Block pattern description', 'indrukwekkend' ),
			'content'       => '<!-- wp:media-text {"align":"full","mediaPosition":"right","mediaType":"image","mediaWidth":40,"backgroundColor":"licht-groen","className":"is-style-indrukwekkend-regisseur"} -->
			<div class="wp-block-media-text alignfull has-media-on-the-right is-stacked-on-mobile is-style-indrukwekkend-regisseur has-licht-groen-background-color has-background" style="grid-template-columns:auto 40%"><figure class="wp-block-media-text__media"><img src="' . asset_path('images/hitchcock.jpg') . '" alt="' . esc_attr__( 'Alfred Hitchcock -- Pulp Fiction', 'indrukwekkend' ) . '"/></figure><div class="wp-block-media-text__content"><!-- wp:heading -->
			<h2>Over de regisseur</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"placeholder":"Inhoud...","fontSize":"large"} -->
			<p class="has-large-font-size">Test teksten, hier komen de teksten van Mathijs</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph -->
			<p>hier komen de vervolg teksten</p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:media-text -->',
		)
	);

	// Media & Text Article Title.
	register_block_pattern(
		'indrukwekkend/media-text-article-links',
		array(
			'title'         => esc_html__( 'Afbeelding blok', 'indrukwekkend' ),
			'categories'    => array( 'indrukwekkend' ),
			'viewportWidth' => 1024, //align wide voor de inserter?
			'description'   => esc_html_x( 'Een Media en Tekst blok met een grote afbeelding, titel en een verklarende tekst.', 'Block pattern description', 'indrukwekkend' ),
			'content'       => '<!-- wp:media-text {"align":"wide","mediaType":"image","mediaWidth":67,"className":"is-style-indrukwekkend-afbeelding"} -->
			<div class="wp-block-media-text alignwide is-stacked-on-mobile is-style-indrukwekkend-afbeelding" style="grid-template-columns:67% auto"><figure class="wp-block-media-text__media"><img src="' . asset_path('images/de-slag.jpg') . '" alt="' . esc_attr__( 'Susan Radder -- De slag om de Schelde', 'indrukwekkend' ) . '"/></figure><div class="wp-block-media-text__content"><!-- wp:heading -->
			<h2>Korte quote uit de film</h2>
			<!-- /wp:heading -->
			
			<!-- wp:paragraph {"placeholder":"Inhoud...","fontSize":"large"} -->
			<p class="has-large-font-size">Test teksten, hier komen de teksten van Mathijs</p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:media-text -->',
		)
	);

	

	// Groep met 2 kolommen met de afbeelding staand links
	register_block_pattern(
		'indrukwekkend/regisseur-staand',
		array(
			'title'         => esc_html__( 'Regisseur staand blok', 'indrukwekkend' ),
			'categories'    => array( 'indrukwekkend' ),
			'viewportWidth' => 1024, //align wide voor de inserter?
			'description'   => esc_html_x( 'Een Media en Tekst blok met een staande afbeelding, titel en een verklarende tekst.', 'Block pattern description', 'indrukwekkend' ),
			'content'       => '<!-- wp:group {"align":"full","backgroundColor":"licht-groen"} -->
			<div class="wp-block-group alignfull is-style-indrukwekkend-regisseur has-licht-groen-background-color has-background"><!-- wp:columns {"verticalAlignment":null,"align":"wide"} -->
			<div class="wp-block-columns alignwide"><!-- wp:column {"width":"33.33%"} -->
			<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="' . asset_path('images/kubrick.jpg') . '" alt="' . esc_attr__( 'Stanley Kubrick', 'indrukwekkend' ) . '"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:column -->
			
			<!-- wp:column {"verticalAlignment":"center","width":"66.66%"} -->
			<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66.66%"><!-- wp:heading -->
			<h2>Over de regisseur:</h2>
			<!-- /wp:heading -->
			
			<!-- wp:paragraph {"fontSize":"large"} -->
			<p class="has-large-font-size">Test teksten, hier komen de teksten van Mathijs</p>
			<!-- /wp:paragraph -->
			
			<!-- wp:paragraph -->
			<p>Hier komen de vervolg teksten</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns --></div>
			<!-- /wp:group -->',
		)
	);

	// Aangesloten afbeeldingen.
	register_block_pattern(
		'indrukwekkend/aangesloten-afbeeldingen',
		array(
			'title'         => esc_html__( 'Aangesloten afbeeldingen', 'indrukwekkend' ),
			'categories'    => array( 'indrukwekkend' ),
			'viewportWidth' => 1024,
			'description'   => esc_html_x( 'Twee aaneengesloten afbeeldingen, naast elkaar.', 'Block pattern description', 'indrukwekkend' ),
			'content'       => '<!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"is-style-indrukwekkend-columns-zonder"} --><div class="wp-block-columns alignwide are-vertically-aligned-center is-style-indrukwekkend-columns-zonder"><!-- wp:column {"verticalAlignment":"center"} --><div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"align":"full","sizeSlug":"filmsFeatImg"} --><figure class="wp-block-image alignfull size-filmsFeatImg"><img src="' . asset_path('images/the_shining.jpg') . '" alt="' . esc_attr__( 'The Shining', 'indrukwekkend' ) . '"/></figure><!-- /wp:image --></div><!-- /wp:column --><!-- wp:column {"verticalAlignment":"center"} --><div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"align":"full",sizeSlug":"filmsFeatImg"} --><figure class="wp-block-image alignfull size-filmsFeatImg"><img src="' . asset_path('images/walken.jpg') . '" alt="' . esc_attr__( 'Christopher Walken -- Pulp Fiction', 'indrukwekkend' ) . '"/></figure><!-- /wp:image --></div><!-- /wp:column --></div><!-- /wp:columns -->',
		)
	);


	// TODO: Kijken of we hier homepage moten instellen:
	// 1. Header met Media kolommen, afbeelding en teksten
	register_block_pattern(
		'indrukwekkend/header-media',
		array(
			'title'       => esc_html__( 'Header met Media kolommen', 'indrukwekkend' ),
			'categories'  => array( 'indrukwekkend' ),
			'description' => esc_html_x( 'Achtergrond header', 'Block pattern description', 'indrukwekkend' ),
			'content'     => '<!-- wp:indrukwekkend/header-achtergrond {"url":"'. asset_path('images/beach.jpg') .'","id":57,"dimRatio":80,"overlayColor":"achtergrond-blauw","contentPosition":"center center","minHeight":823,"className":"is-style-indrukwekkend-klant"} -->
			<div class="wp-block-indrukwekkend-header-achtergrond alignfull has-background-dim-80 has-achtergrond-blauw-background-color has-background-dim is-position-center-center is-style-indrukwekkend-klant" style="background-image:url('. asset_path('images/beach.jpg') .');height:823px;margin-bottom:60px"><img class="mobile" aria-hidden="true" alt="" src="'. asset_path('images/beach.jpg') .'"/><div class="alignwide content-container"><!-- wp:media-text {"mediaId":57,"mediaLink":"http://indrukwekkendplatform.local/serey-kim-hkolya_3vza-unsplash/","mediaType":"image","mediaWidth":52} -->
			<div class="wp-block-media-text alignwide is-stacked-on-mobile" style="grid-template-columns:52% auto"><figure class="wp-block-media-text__media"><img src="'. asset_path('images/keyshot.png') .'" alt="" class="wp-image-57"/></figure><div class="wp-block-media-text__content"><!-- wp:indrukwekkend/header-content-blok -->
			<div class="wp-block-indrukwekkend-header-content-blok" style="min-height:110px;max-width:600px"><div class="overlay" style="opacity:0.75;transform:translateY(-0px)"></div><div class="container__content" style="transform:translateY(-0px)"><!-- wp:indrukwekkend/header-titel-blok /--><!-- wp:paragraph {"placeholder":"Subheader","className":"subheader"} -->
			<p class="subheader"></p>
			<!-- /wp:paragraph -->
			
			<!-- wp:paragraph {"placeholder":"Content…","className":"extra-content"} -->
			<p class="extra-content"></p>
			<!-- /wp:paragraph -->
			
			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secundair"} -->
			<div class="wp-block-button is-style-secundair"><a class="wp-block-button__link">Make this Recipe</a></div>
			<!-- /wp:button -->
			
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Make this Recipe</a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons --></div></div>
			<!-- /wp:indrukwekkend/header-content-blok --></div></div>
			<!-- /wp:media-text --></div></div>
			<!-- /wp:indrukwekkend/header-achtergrond -->',
		)
	);

	// 2. Header met aleen teksten en knoppen, over de achtergrond content heen
	register_block_pattern(
		'indrukwekkend/header-text',
		array(
			'title'       => esc_html__( 'Header met Teksten', 'indrukwekkend' ),
			'categories'  => array( 'indrukwekkend' ),
			'description' => esc_html_x( 'Achtergrond header', 'Block pattern description', 'indrukwekkend' ),
			'content'     => '
			<!-- wp:indrukwekkend/header-achtergrond {"url":"'. asset_path('images/beach.jpg') .'","id":57,"contentPosition":"center left","minHeight":823,"className":"is-style-indrukwekkend-klant"} -->
			<div class="wp-block-indrukwekkend-header-achtergrond alignfull has-background-dim is-position-center-left is-style-indrukwekkend-klant" style="background-image:url('. asset_path('images/beach.jpg') .');height:823px;margin-bottom:60px"><img class="mobile" aria-hidden="true" alt="" src="'. asset_path('images/beach.jpg') .'"/><div class="alignwide content-container"><!-- wp:indrukwekkend/header-content-blok {"backgroundColor":"#2d3676","transparantie":60,"maxWidth":680} -->
			<div class="wp-block-indrukwekkend-header-content-blok has-background" style="min-height:110px;max-width:680px"><div class="overlay" style="background-color:#2d3676;opacity:0.6;transform:translateY(-0px)"></div><div class="container__content" style="transform:translateY(-0px)"><!-- wp:indrukwekkend/header-titel-blok /-->

			<!-- wp:paragraph {"placeholder":"Subheader","className":"subheader"} -->
			<p class="subheader">Subheader Teksten</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Content…","className":"extra-content"} -->
			<p class="extra-content">Content Van de extra bonus </p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secundair"} -->
			<div class="wp-block-button is-style-secundair"><a class="wp-block-button__link">Make this Recipe</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"is-style-arrow"} -->
			<div class="wp-block-button is-style-arrow"><a class="wp-block-button__link">Make this Recipe</a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons --></div></div>
			<!-- /wp:indrukwekkend/header-content-blok --></div></div>
			<!-- /wp:indrukwekkend/header-achtergrond -->
			',
		)
	);

	// 3. Header met teksten en knoppen die onder de hoofdafbeelding vallen
	register_block_pattern(
		'indrukwekkend/header-bottom',
		array(
			'title'       => esc_html__( 'Header met Teksten onder', 'indrukwekkend' ),
			'categories'  => array( 'indrukwekkend' ),
			'description' => esc_html_x( 'Achtergrond header', 'Block pattern description', 'indrukwekkend' ),
			'content'     => '
			<!-- wp:indrukwekkend/header-achtergrond {"url":"http://indrukwekkendplatform.local/wp-content/uploads/2021/01/denys-nevozhai-QjuGO9hI90k-unsplash-scaled.jpg","id":277,"marginBottom":0,"contentPosition":"bottom center","minHeight":761,"className":"is-style-default"} -->
			<div class="wp-block-indrukwekkend-header-achtergrond alignfull has-background-dim is-position-bottom-center is-style-default" style="background-image:url(http://indrukwekkendplatform.local/wp-content/uploads/2021/01/denys-nevozhai-QjuGO9hI90k-unsplash-scaled.jpg);height:761px;margin-bottom:0px"><img class="mobile" aria-hidden="true" alt="" src="http://indrukwekkendplatform.local/wp-content/uploads/2021/01/denys-nevozhai-QjuGO9hI90k-unsplash-scaled.jpg"/><div class="content-container alignwide"><!-- wp:media-text {"mediaId":220,"mediaLink":"http://indrukwekkendplatform.local/normaal/patronen/alex-hudson-m3i92sgm3mk-unsplash/","mediaType":"image","mediaWidth":47,"verticalAlignment":"center"} -->
			<div class="wp-block-media-text alignwide is-stacked-on-mobile is-vertically-aligned-center" style="grid-template-columns:47% auto"><figure class="wp-block-media-text__media"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2021/01/alex-hudson-m3I92SgM3Mk-unsplash-1024x682.jpg" alt="" class="wp-image-220"/></figure><div class="wp-block-media-text__content"><!-- wp:indrukwekkend/header-content-blok {"backgroundColor":"#2d3676","transparantie":60,"textAlign":"left"} -->
			<div class="wp-block-indrukwekkend-header-content-blok alignfull has-background" style="min-height:110px;widht:100%"><div class="overlay" style="background-color:#2d3676;opacity:0.6;transform:translateY(-0px)"></div><div class="container__content" style="transform:translateY(-0px);text-align:left"><!-- wp:indrukwekkend/header-titel-blok /-->

			<!-- wp:paragraph {"placeholder":"Subheader","className":"subheader"} -->
			<p class="subheader">Sub Header</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Content…","className":"extra-content"} -->
			<p class="extra-content">TestContent</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"goud","className":"is-style-secundair"} -->
			<div class="wp-block-button is-style-secundair"><a class="wp-block-button__link has-goud-background-color has-background">Knop Teksten</a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons --></div></div>
			<!-- /wp:indrukwekkend/header-content-blok --></div></div>
			<!-- /wp:media-text --></div></div>
			<!-- /wp:indrukwekkend/header-achtergrond -->

			<!-- wp:indrukwekkend/header-bottom -->
			<div class="wp-block-indrukwekkend-header-bottom alignfull has-background" style="background-color:#2d3676"><div class="container__content"><!-- wp:indrukwekkend/header-content-blok {"align":"wide","backgroundColor":"#2d3676","transparantie":"100","textPositie":"60"} -->
			<div class="wp-block-indrukwekkend-header-content-blok alignwide has-background" style="min-height:110px;widht:100%"><div class="overlay" style="background-color:#2d3676;opacity:1;transform:translateY(-60px)"></div><div class="container__content" style="transform:translateY(-60px)"><!-- wp:indrukwekkend/header-titel-blok /-->

			<!-- wp:paragraph {"placeholder":"Subheader","className":"subheader"} -->
			<p class="subheader">Subheader teksten</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Content…","className":"extra-content"} -->
			<p class="extra-content">Content</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secundair"} -->
			<div class="wp-block-button is-style-secundair"><a class="wp-block-button__link">ButtonTekst</a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons --></div></div>
			<!-- /wp:indrukwekkend/header-content-blok --></div></div>
			<!-- /wp:indrukwekkend/header-bottom -->

			<!-- wp:heading -->
			<h2>Vervolg van de pagina....</h2>
			<!-- /wp:heading -->',
		)
	);

	// Voor demonstratie:
		// Gaat nog niet helemaal goed. Hier laden we een paar blokken in van indrukwekkend zelf maar die moeten eerst gecorrigeerd worden???
		register_block_pattern(
			'indrukwekkend/demo-patroon-pagina',
			array(
				'title'       => esc_html__( 'Demo-pagina', 'indrukwekkend' ),
				'categories'  => array( 'indrukwekkend' ),
				'description' => esc_html_x( 'Blok met drie kenmerken met afbeeldingen', 'Block pattern description', 'indrukwekkend' ),
				'content'     => '<!-- wp:indrukwekkend/header-achtergrond {"url":"http://indrukwekkendplatform.local/wp-content/themes/startertheme/dist/images/beach.jpg","id":57,"contentPosition":"center left","minHeight":823,"className":"is-style-indrukwekkend-klant"} -->
				<div class="wp-block-indrukwekkend-header-achtergrond alignfull has-background-dim is-position-center-left is-style-indrukwekkend-klant" style="background-image:url(http://indrukwekkendplatform.local/wp-content/themes/startertheme/dist/images/beach.jpg);height:823px;margin-bottom:60px"><img class="mobile" aria-hidden="true" alt="" src="http://indrukwekkendplatform.local/wp-content/themes/startertheme/dist/images/beach.jpg"/><div class="alignwide content-container"><!-- wp:indrukwekkend/header-content-blok {"backgroundColor":"#2d3676","transparantie":60,"maxWidth":680} -->
				<div class="wp-block-indrukwekkend-header-content-blok has-background" style="min-height:110px;max-width:680px"><div class="overlay" style="background-color:#2d3676;opacity:0.6;transform:translateY(-0px)"></div><div class="container__content" style="transform:translateY(-0px)"><!-- wp:indrukwekkend/header-titel-blok /-->
				
				<!-- wp:paragraph {"placeholder":"Subheader","className":"subheader"} -->
				<p class="subheader">Subheader Teksten</p>
				<!-- /wp:paragraph -->
				
				<!-- wp:paragraph {"placeholder":"Content…","className":"extra-content"} -->
				<p class="extra-content">Content Van de extra bonus </p>
				<!-- /wp:paragraph -->
				
				<!-- wp:buttons -->
				<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secundair"} -->
				<div class="wp-block-button is-style-secundair"><a class="wp-block-button__link">Make this Recipe</a></div>
				<!-- /wp:button -->
				
				<!-- wp:button {"className":"is-style-arrow"} -->
				<div class="wp-block-button is-style-arrow"><a class="wp-block-button__link">Make this Recipe</a></div>
				<!-- /wp:button --></div>
				<!-- /wp:buttons --></div></div>
				<!-- /wp:indrukwekkend/header-content-blok --></div></div>
				<!-- /wp:indrukwekkend/header-achtergrond -->
				
				<!-- wp:indrukwekkend/kenmerken -->
				<div class="wp-block-indrukwekkend-kenmerken alignwide columns-3"><!-- wp:indrukwekkend/kenmerk-one -->
				<div class="wp-block-indrukwekkend-kenmerk-one card"><a class="overlay"></a><div class="card-content has-thumbnail"><div class="card-content-container"><figure class="thumbnail"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2021/01/alex-hudson-m3I92SgM3Mk-unsplash.jpg"/></figure><div class="card-content__post-title"><p class="titel">Professionele aanpak</p></div><div class="card-content__post-excerpt"><!-- wp:paragraph -->
				<p>hallo content</p>
				<!-- /wp:paragraph --></div></div><div class="footer"><a class="knop is-style-outline">Bekijk</a></div></div></div>
				<!-- /wp:indrukwekkend/kenmerk-one -->
				
				<!-- wp:indrukwekkend/kenmerk-one -->
				<div class="wp-block-indrukwekkend-kenmerk-one card"><a class="overlay"></a><div class="card-content has-thumbnail"><div class="card-content-container"><figure class="thumbnail"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2021/01/denys-nevozhai-QjuGO9hI90k-unsplash-scaled.jpg"/></figure><div class="card-content__post-title"><p class="titel">test</p></div><div class="card-content__post-excerpt"><!-- wp:paragraph -->
				<p>Hier meer content</p>
				<!-- /wp:paragraph --></div></div><div class="footer"><a class="knop is-style-outline">Bekijk</a></div></div></div>
				<!-- /wp:indrukwekkend/kenmerk-one -->
				
				<!-- wp:indrukwekkend/kenmerk-one -->
				<div class="wp-block-indrukwekkend-kenmerk-one card"><a class="overlay"></a><div class="card-content has-thumbnail"><div class="card-content-container"><figure class="thumbnail"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/DSC07675-large-scaled.jpg"/></figure><div class="card-content__post-title"><p class="titel">Hotel de ville</p></div><div class="card-content__post-excerpt"><!-- wp:paragraph -->
				<p>Nog meer content</p>
				<!-- /wp:paragraph --></div></div><div class="footer"><a class="knop is-style-outline">Bekijk</a></div></div></div>
				<!-- /wp:indrukwekkend/kenmerk-one --></div>
				<!-- /wp:indrukwekkend/kenmerken -->
				
				<!-- wp:indrukwekkend/partners {"images":[{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/entrepreneur-tr-1-190x110.png","width":190,"height":110,"id":76,"alt":"","caption":""},{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/Inc-tr-1-190x110.png","width":190,"height":110,"id":74,"alt":"","caption":""},{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/client_logo_kleankanteen-e1545946864265-228x110.png","width":228,"height":110,"id":49,"alt":"","caption":""},{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/client_logo_bell-e1543958201828-338x110.png","width":338,"height":110,"id":48,"alt":"","caption":""},{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usertesting-tr-190x110.png","width":190,"height":110,"id":46,"alt":"","caption":""},{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usa-today-1-190x110.png","width":190,"height":110,"id":72,"alt":"","caption":""},{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usertesting-tr-1-190x110.png","width":190,"height":110,"id":71,"alt":"","caption":""},{"src":"http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usa-today-190x110.png","width":190,"height":110,"id":45,"alt":"","caption":""}]} -->
				<div class="wp-block-indrukwekkend-partners"><div class="wp-block-indrukwekkend-partners__header"><h3 class="podkit-title">Werken voor</h3></div><div class="wp-block-indrukwekkend-partners__icons" data-dots="false" data-speed="500" data-slidestoshow="4" data-slidestoscroll="2" data-autoscroll="false"><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/entrepreneur-tr-1-190x110.png" alt="" data-id="76"/></figure><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/Inc-tr-1-190x110.png" alt="" data-id="74"/></figure><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/client_logo_kleankanteen-e1545946864265-228x110.png" alt="" data-id="49"/></figure><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/client_logo_bell-e1543958201828-338x110.png" alt="" data-id="48"/></figure><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usertesting-tr-190x110.png" alt="" data-id="46"/></figure><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usa-today-1-190x110.png" alt="" data-id="72"/></figure><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usertesting-tr-1-190x110.png" alt="" data-id="71"/></figure><figure class="afbeelding"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2020/12/usa-today-190x110.png" alt="" data-id="45"/></figure></div></div>
				<!-- /wp:indrukwekkend/partners -->
				
				<!-- wp:separator {"color":"blauw","className":"is-style-default"} -->
				<hr class="wp-block-separator has-text-color has-background has-blauw-background-color has-blauw-color is-style-default"/>
				<!-- /wp:separator -->
				
				<!-- wp:media-text {"mediaId":277,"mediaLink":"http://indrukwekkendplatform.local/sample-page/header-bottom/denys-nevozhai-qjugo9hi90k-unsplash/","mediaType":"image"} -->
				<div class="wp-block-media-text alignwide is-stacked-on-mobile"><figure class="wp-block-media-text__media"><img src="http://indrukwekkendplatform.local/wp-content/uploads/2021/01/denys-nevozhai-QjuGO9hI90k-unsplash-1024x723.jpg" alt="" class="wp-image-277"/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph {"placeholder":"Content…","fontSize":"large"} -->
				<p class="has-large-font-size">Hier de content</p>
				<!-- /wp:paragraph -->
				
				<!-- wp:buttons -->
				<div class="wp-block-buttons"><!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link">Hier een knop</a></div>
				<!-- /wp:button --></div>
				<!-- /wp:buttons -->
				
				<!-- wp:paragraph -->
				<p></p>
				<!-- /wp:paragraph --></div></div>
				<!-- /wp:media-text -->
				
				<!-- wp:paragraph -->
				<p></p>
				<!-- /wp:paragraph -->',
			)
		);

});
