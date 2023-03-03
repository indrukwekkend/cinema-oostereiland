
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
  if (isset($my_array_of_vars['v'])) {
    $Youtube = $my_array_of_vars['v'];
  } else {
    $Youtube = substr($Youtube, strrpos($Youtube, '/') + 1);
  }
  

?>

<header class="page-header-film alignfull contrast" style="background-image: url(<?=$backgroundImage?>); ">

  <div class="film_media">
    
    <?php if ($Youtube != '') : ?>
      <button type="button" class="kill_btn youtube_btn" id="close-youtube">
        <i class="fa fa-times-circle fa-2x" aria-hidden="true"></i>
      </button>
      <div class="fitVids-wrapper">
          <iframe class="position-absolute" id="video" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" src="https://www.youtube.com/embed/<?= $Youtube; ?>?enablejsapi=1&amp;controls=1&amp;showinfo=0&amp;rel=0&amp;modestbranding=1" frameborder="0" allowfullscreen="" data-gtm-yt-inspected-11196688_19="true"></iframe>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($Youtube != '') : ?>
    <div class="video-button">
      <button type="button" class="play_btn" id="open-youtube">
        <i class="fa fa-play-circle fa-5x" aria-hidden="true"></i>
      </button>
    </div>
  <?php endif; ?>
  
</div>

  <img src="<?= $backgroundImage; ?>" alt="" class="position-absolute">







</header>