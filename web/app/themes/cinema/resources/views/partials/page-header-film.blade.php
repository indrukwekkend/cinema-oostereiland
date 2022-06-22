
<?php

  //
  // Header voor de film. 
  //
  // onderdeel van Content Single Film.
  // $film_id in hoofddocument
  //
  //

  $regisseur = get_post_meta( $film_id, 'regie', true );
  $image = get_field('header_img');

  if( is_array($image) ) {
    $backgroundImage = esc_url($image['url']); 
  } else {
    $backgroundImage = wp_get_attachment_image_url( $image, 'full' );
  }

  $label = get_post_meta( $film_id, 'extra_tekst_label', true );
  if ($label != '') {
    $label = '<span class="label">'.$label.'</span>';
  }

  //Youtube. TODO Vimeo
  $Youtube = get_post_meta( $film_id, 'youtube', true );

  // Youtube staat er als complete URL in, dus zoeken we de ?v=  : 
  parse_str( parse_url( $Youtube, PHP_URL_QUERY ), $my_array_of_vars );
  $Youtube = $my_array_of_vars['v'];

?>

<header class="page-header-film alignfull contrast" style="background-image: url(<?=$backgroundImage?>); ">

<div class="film_media">
  <button type="button" class="kill_btn youtube_btn" id="close-youtube">
    <i class="fa fa-times-circle fa-4x" aria-hidden="true"></i>
  </button>
  
  <?php if ($Youtube != '') : ?>
    <div class="fitVids-wrapper">
        <iframe class="position-absolute" id="video" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" src="https://www.youtube.com/embed/<?= $Youtube; ?>?enablejsapi=1&amp;controls=1&amp;showinfo=0&amp;rel=0&amp;modestbranding=1" frameborder="0" allowfullscreen="" data-gtm-yt-inspected-11196688_19="true"></iframe></div>
    </div>
  <?php endif; ?>

  <div class="video-button">
    <button type="button" class="play_btn" id="open-youtube">
      <i class="fa fa-play-circle fa-5x" aria-hidden="true"></i>
    </button>
  </div>



  <div class="header-content-wrapper">
      
      <!-- <div class="header-content-wrapper__content alignwide">
        <p>hier het label</p>
        <h1 class="entry-title">{!! get_the_title() !!}</h1>
        <p>hier de subtekst</p>
      </div> -->

      <!-- TODO: de button naar de film tickets in een balk, onderaan de header -->

      <div id="cta-bar" class="cta-bar alignfull">
        <div class="cta-bar__content alignwide">
          
          <div class="cta-bar__content__left">
            <h1 class="entry-title">{!! get_the_title() !!}</h1>
            <div class="film-info">
                <div class="ticketDates"></div>
                <!-- <p>&nbsp; / @php echo $regisseur . $label @endphp </p> -->
            </div>
          
          </div>

          <div class="cta-bar__content__right">
            <a class="btn wp-block-button__link" id="toon_tickets" href="#">Tickets</a>
          </div>


        </div>
      </div>
  </div>

</header>