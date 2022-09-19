<?php
/**
 * Displays the page header (or not ;-).
 *
 * @package WordPress
 * @subpackage Indrukwekkend
 * @since Indrukwekkend 1.1
 */

$wrapper_classes  = 'page-header'; 
//$wrapper_classes .= true === get_theme_mod( 'display_title_and_tagline', true ) ? ' has-title-and-tagline' : ''; 
$wrapper_classes .= true === get_theme_mod( 'display_transparant_nav', true ) ? ' transparant' : '';

// doe niks als eer van de header blokken is geplaatst
if (   has_block( 'indrukwekkend/header-achtergrond' ) 
    || has_block( 'indrukwekkend/header-bottom' )
    || has_block( 'indrukwekkend/header-kolommen' )
    || has_block( 'indrukwekkend/header-slider' )
    ) { 

      $wrapper_classes .= 'light-mode';
    }

else { ?>
  
  <div class="<?php echo esc_attr( $wrapper_classes ); ?>">
    <?php   if ( function_exists('yoast_breadcrumb') && !is_front_page() && !is_home()  ) : ?>
      <?php yoast_breadcrumb( '<p id="breadcrumbs">','</p>' ); ?>
    <?php endif; ?>
    <h1><?php echo the_title() ?></h1>
  </div>

<?php } ?>