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
    background-image: url(/app/themes/cinema/dist/images/CE_logo_diap.svg) !important;
  }

  .home header.site-header .container .brand, 
  .single-films header.site-header .container .brand,
  .single-festivals header.site-header .container .brand {
    background-image: url(/app/themes/cinema/dist/images/CE_logo.svg) !important;
  }
</style>

<?php
  endif;
?>

<header class="<?php echo esc_attr( $wrapper_classes ); ?>" id="masthead" role="banner">
  <div class="container">
    <!-- logo en home -->
    <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
    
    <!-- Primary navigation -->
    <nav class="nav-primary">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
      @endif
    
      <!-- Search button -->
      <button class='search-header-icon' id='search-open'><i class="fas fa-search"></i></button>
      <!-- Cart button -->
      <a class="shopping-cart-icon cart" data-site="<?= TICKETURL ?>" href="#"><i class="far fa-shopping-cart"></i></a>

       <!-- Hamburger navigation -->
      <button class="hamburger hamburger--spin" type="button">
        <span class="hamburger-title">Menuu</span>
        <span class="hamburger-box">
          <span class="hamburger-inner"></span>
        </span>
      </button>
    
    </nav>

   
  </div>
</header>

<!-- mobile or hidden navigation -->
<header class="mobile-navigation-container start">

    <nav class="nav-mobile">
      <div class="search">
        
        <form action="/?s" method="get" class="search-form">
          <i class="fas fa-search"></i>
          <span class="widget">
            <input class="input-legacy  input-legacy--button input-legacy--open footer__input" type="search" name="s" placeholder="Zoeken">
          </span>
        </form>
      </div>
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'container_id' => 'cssmenu', 'walker' => new CSS_Menu_Maker_Walker()]) !!}
      @endif
      @if (has_nav_menu('top_navigation'))
        {!! wp_nav_menu(['theme_location' => 'top_navigation', 'menu_class' => 'nav']) !!}
      @endif
    </nav>
    
</header>
<div class="mobile-navigation-overlay start"><div class="image"></div></div>


<!-- Search hidden area -->
<div id="search">
<button class='search-header-icon' id='search-close'><i class="fas fa-times"></i></button>
    <form role="search" method="get" class="search-form-header" action="/">
        <label>
            <span class="screen-reader-text">Zoeken naar:</span>
            <input type="search" class="search-field" placeholder="Zoeken …" value="" name="s">
        </label>
        <button type="submit" class="search-submit">Zoeken</button>
    </form>
</div>