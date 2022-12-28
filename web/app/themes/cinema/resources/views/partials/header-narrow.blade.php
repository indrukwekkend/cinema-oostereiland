<?php 
/**
 * Displays the site header.
 *
 * @package WordPress
 * @subpackage Indrukwekkend
 * @since Indrukwekkend 1.1
 */

$wrapper_classes  = 'banner site-header';

  if (TICKETURL != 'tickets.cinemaoostereiland.nl') :
?>

 <style>
  header.site-header .container .brand,
  .template-agenda header.site-header .container .brand {
    background-image: url(/app/themes/cinema/dist/images/CE_logo_diap.svg);
  }

  .home header.site-header .container .brand, .single-films header.site-header .container .brand {
    background-image: url(/app/themes/cinema/dist/images/CE_logo.svg);
  }
</style>

<?php

  $time = @date('d M Y');
  endif;
?>

<header class="<?php echo esc_attr( $wrapper_classes ); ?>" id="masthead" role="banner">
  <div class="container">
    <!-- logo en home -->
    <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>

    <div id="datumTijd"><h1><span id="tijd"><?php echo($time); ?></span></h1></div>		
								
   
  </div>
</header>
