<?php
/**
 * Displays the page header (or not ;-).
 *
 * @package WordPress
 * @subpackage Indrukwekkend
 * @since Indrukwekkend 1.1
 */

$wrapper_classes  = 'page-header-agenda'; 

// controleer of de header transparent is ingesteld
$wrapper_classes .= true === get_theme_mod( 'display_transparant_nav', true ) ? ' transparant' : ''; ?>

<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
<h1><?php echo the_title() ?></h1>
</div>

