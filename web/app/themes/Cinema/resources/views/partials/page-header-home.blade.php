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
            <h2 class="entry-title">{{$titel}}</h2>
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
  <h2>Vandaag <span class='alles'></h2> 

  
  <div class="slider dagslider">
    @php echo getTicketTable($shows); @endphp 
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
    setlocale(LC_ALL, 'nl_NL');

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

    $titel = 'nog geen titel';
    $film_info = '';
    $filmlink = '';
    $thumbnail = "<img src='https://picsum.photos/150/200' />";

    while ( $the_query->have_posts() ) : $the_query->the_post();
      
        $id = get_the_ID();
        $titel = get_the_title();
        $filmlink = get_the_permalink();

        $regisseur = '';
        $duur = '';
        $land = '';

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
                  // $output .= '<p class="film-info">'.$film_info.'</p>';
            
              $output .= '</div>';
              $output .= '<div class="knoppen">';
                $output .= '<a class="btn bestellen" data-nummer="'.$shownumber.'" href="#">'.$tijd.'</a>';
              $output .= '</div>';
            $output .= '</div>';

            // Tijd:
            // Zaal:

    $output .= '</li>';


  }
  wp_reset_postdata();
  return $output;
}
?>