<?php
/**
 * Displays the page header (or not ;-).
 *
 * @package WordPress
 * @subpackage Indrukwekkend
 * @since Indrukwekkend 1.1
 */

$wrapper_classes  = 'page-header'; 
$wrapper_classes .= true === get_theme_mod( 'display_filmcafe_nav', true ) ? ' has-title-and-tagline' : ''; 
// $wrapper_classes .= true === get_theme_mod( 'display_transparant_nav', true ) ? ' transparant' : '';
 ?>

  <div class="<?php echo esc_attr( $wrapper_classes ); ?>">
    <?php   if ( function_exists('yoast_breadcrumb') && !is_front_page() && !is_home()  ) : ?>
      <?php yoast_breadcrumb( '<p id="breadcrumbs">','</p>' ); ?>
    <?php endif; ?>
  </div>
