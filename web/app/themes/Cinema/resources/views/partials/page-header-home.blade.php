<?php
  /**
   * Displays the page header on the homepage.
   *
   * @package WordPress
   * @subpackage Indrukwekkend
   * @since CinemaOostereiland 0.0.1
   */

$hero = get_field('hero');
if( $hero ):
  $image = $hero['afbeelding']['header_img']; 
  $backgroundImage = esc_url($image['url']);

  $titel = $hero['Teksten']['titel'];
  $subheader = get_field('hero_Teksten_subheader');
  $label = get_field('hero_Teksten_extra_tekst_label');
  $contrast = $hero['afbeelding']['contrast'];
  $knop = $hero['Teksten']['toon_knop'];
  $linktekst = $hero['Teksten']['linktekst'];
  $link = $hero['Teksten']['link'];

 
  $wrapper_classes  = ''; 
  $wrapper_classes .= true === $contrast ? ' contrast' : '';

endif; 
?>

<header class="page-header-film alignfull {{$wrapper_classes}}" style="background-image: url(<?=$backgroundImage?>); ">

  <div class="header-content-wrapper">

      <!-- TODO: de button naar de film tickets in een balk, onderaan de header -->

      <div id="cta-bar" class="cta-bar alignfull">
        <div class="cta-bar__content alignwide">
          
          <div class="cta-bar__content__left">
            <h1 class="entry-title">{{$titel}}</h1>
            <p><span>{{$label}}</span>{{$subheader}}</p>
            <div class="film-info">
                <div class="ticketDates"></div>
            </div>
          
          </div>
        <?php if ($knop): ?>
          <div class="cta-bar__content__right">
            <a class="btn wp-block-button__link" id="toon_tickets" href="{{$link}}">{{$linktekst}}</a>
          </div>
        <?php endif; ?>

        </div>
      </div>
  </div>

</header>
