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

$titel_actueel = get_field('actuele_films_titel', 'options');
$alleen_vandaag = get_field('films_vandaag', 'options');
?>

<header class="page-header-film alignfull {{$wrapper_classes}}" style="background-image: url(<?=$backgroundImage?>); ">

  <div class="header-content-wrapper">

      <!-- TODO: de button naar de film tickets in een balk, onderaan de header -->

      <div id="cta-bar" class="cta-bar alignfull">
        <div class="cta-bar__content alignwide">
          
          <div class="cta-bar__content_right">
             <h2 class="entry-title"> <a href="{{$link}}"> {{$titel}}</a> </h2>
            <p><span>{{$label}}</span>{{$subheader}}</p>
              <?php if ($knop): ?>
                <a class="btn wp-block-button__link tickets" id="toon_tickets" href="{{$link}}#tickets">{{$linktekst}}</a>
                <a class="btn wp-block-button__link info" id="toon_info" href="{{$link}}">Informatie</a>
                    
              <?php endif; ?>
          </div>

        </div>
      </div>
  </div>

</header>

<header class="vandaag-slider alignfull">

  <?php 
  $output =  "";
  //data settings
    $today = strtotime('today');
    $tomorrow = strtotime('tomorrow +3 hours');
    $now = strtotime('now');
    // Datetime in data zetten: Vandaag en morgen 
    $data = array (
        'datumvandaag' => date("Y-m-d", $today),
        'datetime[strictly_after]' => date("Y-m-d\TH:i:s", $now),
        'datetime[strictly_before]' => date("Y-m-d\TH:i:s", $tomorrow),
        'order[datetime]' => 'asc',
    );
  $tickets = new TicketlabCurl();
  $shows = $tickets->getShowstoday( $data );
  ?>

  <div class="container alignwide">
  <h2 class='title inline'>@php echo $titel_actueel @endphp </h2> <a class='agenda' href='/agenda'>Toon alles <i class="fal fa-chevron-right"></i> </a> 

  
  <div class="slider dagslider">
    <?php 
      if ($alleen_vandaag): 
          echo getTicketTable($shows);
      else:
          echo getFilms();
      endif;
    ?>
  </div>

  </div>

</header>


<?php
// deze functie dan het liefte als methode in de getShowToday() methode. Dat nog ff uitzoeken... 
function getTicketTable($shows) {
  global $wp_query;

  $output = '';
  foreach($shows as $key => $show) {

    if ($key === 'status') {
      continue;
    }

    //vorm datum en tijd naar bruikbaar NL:
    $format = 'Y-m-d\TH:i:s\+\0\0\:\0\0';
    $date = DateTime::createFromFormat($format, $show['datetime'], new DateTimeZone('UTC'));

    $nl_date = $date;
    $nl_date->setTimeZone(new DateTimeZone('Europe/Amsterdam'));
    setlocale(LC_ALL, 'nl_NL.utf8');

    /* Output: vrijdag 22 december 1978 */
    $datum = strftime("%a <span class='datum_notatie'>%e %b</span>", strtotime($show['datetime']));
    $tijd = $nl_date->format('H:i');

    // create ticketlab link
    $shows_replace = array("shows", "/");
    $shownumber = str_replace( $shows_replace, "", $show['@id'] );

    // TODO: integreren in de site
    $link = 'https://tickets.cinemaoostereiland.nl/shop/tickets.php?showid='. $shownumber;

    $args = array(
      'numberposts'	=> 1,
      'post_type'		=> 'films',
      'meta_key'		=> 'ticketlab_id',
      'meta_value'	=> $show['eventid'],
    );

    $the_query = new WP_Query( $args );

    $titel = 'Nog geen titel';
    $film_info = '';
    $filmlink = '';
    $thumbnail = "<img src='https://picsum.photos/150/250' />";
    $meta = '';

    while ( $the_query->have_posts() ) : $the_query->the_post();
      
        $id = get_the_ID();
        $titel = get_the_title();
        $filmlink = get_the_permalink();

        $regisseur = '';
        $duur = '';
        $land = '';


        // Load leeftijd settings and values.
          $soorten = get_field('kijkwijzer_soort', $id);
          $leeftijd = get_field('kijkwijzer_leeftijd', $id);


          // Display labels. 
          // PLUGIN_URL is een defined constant.

          if( $soorten ): 
              $meta .= '<ul class="kijkwijzer">';

                  //leeftijd eerst
                  $meta .= "<li><img src='". PLUGIN_URL ."dist/images/B_".$leeftijd['label'].".png' alt='".$leeftijd['value']." jaar' /></li>";
                  
                  //soorten daarna:
                  foreach( $soorten as $soort ):
                      $meta .=  '<li><img src="'. PLUGIN_URL .'dist/images/'. strtolower($soort) .'.png" alt="'.$soort.'" /></li>';
                  endforeach;

              $meta .= '</ul>';
          endif;

        //metadata
        $regisseur = get_post_meta( $id, 'regie', true );
        $duur = get_post_meta( $id, 'duur', true );
        $land = get_post_meta( $id, 'land', true );
        $taal = get_post_meta( $id, 'taal', true );

        $film_info .= $duur .' min. '.$taal.' '.$regisseur;

        $size = 'medium_large';
        $thumbnail = get_field( 'alternatieve_afbeelding', $id );

        if ( $thumbnail ) { 

          $url = $thumbnail['url'];
          $alt = esc_attr($thumbnail['alt']);
      
          // Thumbnail size attributes.
          $thumb = esc_url($thumbnail['sizes'][ $size ]);
    
          $thumbnail = "<img src='$thumb' alt='$alt' />";
    
        }
    endwhile;


        ///////////////////////////////////
        //
        // Output naar de site begint hier:
        //
        //////////////////////////////////

    $output .= '<li class="ticket">';
                //TODO: Specials
            // Titel met URL
            $output .= '<div class="card filmsFeatImg">';
              $output .= sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail);
              $output .= '<div class="text">';
              $output .= '<a class="overlay" href="'.$filmlink.'" title="'.$titel.'"></a>';
                  $output .= '<h3>'.$titel.'</h3>';
                  $output .= '<div class="extra">'.$meta.'</div>';
            
              $output .= '</div>';
              $output .= '<div class="knoppen">';
                $output .= '<a class="btn bestellen" data-number="'.$shownumber.'" data-site="'.TICKETURL.'" href="#">'.$tijd.' uur</a>';
              $output .= '</div>';
            $output .= '</div>';

            // Tijd:
            // Zaal:

    $output .= '</li>';


  }
  wp_reset_postdata();
  return $output;
}

function getFilms() {

  $output = '';

  // get states
  $args = array(
    'post_type' => 'films',
    'posts_per_page' => -1,
    'orderby' => 'rand',
    'post_status' => array( 'publish', 'future' ),
    //TODO: Alleen actieve films ophalen, de oude niet....
    'meta_key'   => 'actief',		
      'meta_query' => array(
        array(
            'key'     => 'actief',
            'value'   => 1,
            'compare' => 'IN',
        ),
    ),
  );

    $the_query = new WP_Query( $args );

    while ( $the_query->have_posts() ) : $the_query->the_post();

      $id = get_the_ID();
      $titel = get_the_title();
      $meta = '';
      $film_info = '';
      $filmlink = get_the_permalink();

      $regisseur = '';
      $duur = '';
      $land = '';

      $size = 'medium_large';
      $thumbnail = get_field( 'alternatieve_afbeelding');

      // Load leeftijd settings and values.
      $soorten = get_field('kijkwijzer_soort');
      $leeftijd = get_field('kijkwijzer_leeftijd');

      //Thumbnail
      if ( $thumbnail ) { 

        $url = $thumbnail['url'];
        $alt = esc_attr($thumbnail['alt']);
    
        // Thumbnail size attributes.
        $thumb = esc_url($thumbnail['sizes'][ $size ]);
  
        $thumbnail = "<img src='$thumb' alt='$alt' />";
  
      }

      // Display labels. 
      // PLUGIN_URL is een defined constant.
      if( $soorten ): 
        $meta .= '<ul class="kijkwijzer">';

            //leeftijd eerst
            $meta .= "<li><img src='". PLUGIN_URL ."dist/images/B_".$leeftijd['label'].".png' alt='".$leeftijd['value']." jaar' /></li>";
            
            //soorten daarna:
            foreach( $soorten as $soort ):
                $meta .=  '<li><img src="'. PLUGIN_URL .'dist/images/'. strtolower($soort) .'.png" alt="'.$soort.'" /></li>';
            endforeach;

          $meta .= '</ul>';
      endif;

      //metadata
      $regisseur = get_post_meta( $id, 'regie', true );
      $duur = get_post_meta( $id, 'duur', true );
      $land = get_post_meta( $id, 'land', true );
      $taal = get_post_meta( $id, 'taal', true );

      $film_info .= $duur .' min. '.$taal.' '.$regisseur;


      ///////////////////////////////////
      //
      // Output naar de site begint hier:
      //
      //////////////////////////////////

      $output .= '<li class="ticket">';
          //TODO: Specials
      // Titel met URL
      $output .= '<div class="card filmsFeatImg">';
        $output .= sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail);
        $output .= '<div class="text">';
        $output .= '<a class="overlay" href="'.$filmlink.'" title="'.$titel.'"></a>';
            $output .= '<h3>'.$titel.'</h3>';
            $output .= '<div class="extra">'.$meta.'</div>';
      
        $output .= '</div>';
      $output .= '</div>';

      // Tijd:
      // Zaal:

    $output .= '</li>';

  endwhile;



  wp_reset_postdata();
  return $output;
}


?>
