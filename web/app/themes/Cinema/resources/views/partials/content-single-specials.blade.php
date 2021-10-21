<div @php post_class() @endphp>

<?php
  $image = get_field('header_festival');
  $backgroundImage = esc_url($image['url']); 

  // overige meta:
  ?>

  <header class="page-header alignfull">

        
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
                
              </div>
             
            </div>

            <div class="cta-bar__content__right">
              hier een koppeling met de geselecteerde kaart:
              <p>Geselecteerde ticket: @php the_field('ticket') @endphp</p>
              <a class="btn wp-block-button__link" id="toon_tickets" href="#">kaarten</a>
            </div>


          </div>
        </div>
    </div>

  
  </header>

  <div class="entry-content">
    
    
    @php the_content() @endphp
    
    @php $film_id = get_field('special_films'); @endphp
    <p>Hier komt het blok met de film informatie. Ook een link naar de film zelf voor overige tijden:</p>

  </div>


<?php
  // De geselecteerde film:
  if ($film_id != '') {

     // The Query
    $args = array(
      'p' => $film_id,
      'post_type' => 'films'
    );
      
      $the_query = new WP_Query( $args );
      
      // The Loop
      if ( $the_query->have_posts() ) {

          while ( $the_query->have_posts() ) {
              $the_query->the_post();
              echo '<h2>' . get_the_title() . '</h2>';
              echo get_the_permalink();
              echo get_the_excerpt();
          }

      } else {
          // no posts found
      }
      /* Restore original Post Data */
      wp_reset_postdata();
  }
 
?>

</div>
