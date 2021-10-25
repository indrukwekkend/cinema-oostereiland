<?php

$foot_nav3 = array(
  'theme_location' => 'footer4_navigation',
  'container' => '',
  'menu_class' => '',
);
?>

 <?php  
  //footer titels uit options ACF pagina
  // TODO verwijderen?? 
  $footTitle1 = get_field('titel_kolom_1', 'option');
  $footTitle2 = get_field('titel_kolom_2', 'option');
  $footTitle3 = get_field('titel_kolom_3', 'option');

  // Footer contact informatie
	$contact_text = get_field('opt_foot_contact_text', 'option');
	if( have_rows('opt_foot_contact_info', 'option') ):
		while( have_rows('opt_foot_contact_info', 'option') ): the_row(); 

			$linkedin = get_sub_field('linkedin', 'option');
			$pinterest = get_sub_field('pinterest', 'option');
			$facebook = get_sub_field('facebook', 'option');
			$instagram = get_sub_field('instagram', 'option');
			$twitter = get_sub_field('twitter', 'option');
			$youtube = get_sub_field('youtube', 'option');
			$website = get_sub_field('website', 'option');
		endwhile;
	endif;

 ?>

<!-- Footer navigatie -->

<?php
if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
  <footer  id="footer-widget-area" class="content-info site-footer" role="contentinfo">
      <div class="content-holder container alignwide">
        <div class="col footer_titel">
          <a href="/" title="home">
            <img src="@asset('images/logo.svg')" alt="Logo: Cinemaoostereiland" class="logoduurzaam">
          </a>
        </div>

        <div  class="widget-area">
          <?php dynamic_sidebar( 'sidebar-footer' ); ?>
        </div>

        <div class="socials">
          @if ($linkedin)
            <a target="_blank" href="<?= $linkedin; ?>" title="LinkedIn">
              <i class="fab fa-linkedin"></i>
            </a>
          @endif
          @if ($pinterest)
            <a target="_blank" href="<?= $pinterest; ?>" title="Pinterest">
              <i class="fab fa-pinterest-square"></i>
            </a>
          @endif
          @if ($facebook)
            <a target="_blank" href="<?= $facebook; ?>" title="Facebook">
              <i class="fab fa-facebook-square"></i>
            </a>
          @endif
          @if ($instagram)
            <a target="_blank" href="<?= $instagram; ?>" title="Instagram">
              <i class="fab fa-instagram-square"></i>
            </a>
          @endif
          @if ($twitter)
            <a target="_blank" href="<?= $twitter; ?>" title="Twitter">
              <i class="fab fa-twitter-square"></i>
            </a>
          @endif
          @if ($youtube)
            <a target="_blank" href="<?= $youtube; ?>" title="YouTube">
              <i class="fab fa-youtube-square"></i>
            </a>
          @endif
        </div>
      </div>
  </footer>
<?php endif; ?>

<!-- Footer copyright -->
<footer class="content-info site-footer" id="mainfooter" role="contentinfo">
  <div class="container alignwide"> 
    <?php if ( get_bloginfo( 'name' ) && get_theme_mod( 'display_title_and_tagline', true ) ) : ?>
      <p>&copy; <?php bloginfo( 'name' ); ?></p>  
    <?php endif; ?>
    {!! wp_nav_menu($foot_nav3) !!}
    <?php if ( get_theme_mod( 'display_title_and_tagline', true ) ) : ?>
      <p class='powered_by'>Website: <a href="https://indrukwekkend.nl" target="_blank" title='Website realisatie: Indrukwekkend, full service communicatie'><img src="@asset('images/indrukwekkendEye.svg')"></a></p>  
    <?php endif; ?>  
  </div>
</footer>

<!-- Pijltje naar boven -->
<a href="#" class="text-center p-2 show" id="back-to-top" title="Back to top">
		<svg class="svg-inline--fa fa-chevron-up fa-w-14 fa-3x" aria-hidden="true" data-prefix="fas" data-icon="chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path></svg><!-- <i class="fas fa-chevron-up fa-3x"></i> -->
</a>

