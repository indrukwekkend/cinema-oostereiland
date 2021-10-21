<div @php post_class() @endphp>

  <?php
  $film_id = get_the_id();
  $image = get_field('header_img');
  $backgroundImage = esc_url($image['url']); 
  // overige meta:
  $regisseur = get_post_meta( $film_id, 'regie', true );
  ?>

  <header class="page-header-film alignfull" style="background-image: url(<?=$backgroundImage?>); ">

    <div class="video-button"></div>

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
                  <p>&nbsp; / @php echo $regisseur @endphp</p>
              </div>
             
            </div>

            <div class="cta-bar__content__right">
              <a class="btn wp-block-button__link" id="toon_tickets" href="#">kaarten</a>
            </div>


          </div>
        </div>
    </div>
  
  </header>


  <div class="entry-content">
    @php the_content() @endphp
  </div>





<!-- blok met Film festival als die beschikbaar is -->

<?php

$featured_post = get_field('festival_films');

if( $featured_post ): 
  global $post; 
  $post = $featured_post;
	setup_postdata( $post );
  
  $image = get_field('header_festival');
  $backgroundImage = esc_url($image['url']); ?>

  <div class="block-festival alignfull block-slider">
    <div class="alignwide content-container">
        
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="wp-block-columns festival-content">
          <div class="wp-block-column block-festival-image">
            <img class="page-header-film" src="<?=$backgroundImage?>">
          </div>
          <div class="wp-block-column block-festival-text">
          <?php 
            $intro = get_field( 'intro_tekst' ); 
            if ($intro != '') {
              echo "<p class='intro'>$intro</p>";
            }
          
          ?>
            <?php the_excerpt(); ?>
            <a href="<?php the_permalink(); ?>">Lees alles over <?php the_title();?></a>
          
          </div>
        </div>

        <h2><?php the_field( 'reeks_titel' ); ?></h2>
        
        <?php
          // Hier de lijst met de films in het Festival
          $films = get_field('festival_films');
          
            if( $films ): 
              global $post; ?>
              <div class="specials-block-films sliderlijst">
                  
                 
                  <?php foreach( $films as $post ): 

                      // Setup this post for WP functions (variable must be named $post).
                      setup_postdata($post); ?>

                    <li>
                      <?php
                      $image = get_field('header_img');

                      $url = $image['url'];
                      $title = $image['title'];
                      $alt = $image['alt'];
                      $caption = $image['caption'];
                  
                      // Thumbnail size attributes.
                      $size = 'filmsFeatImg';
                      $thumb = $image['sizes'][ $size ];
                      ?>
                          <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($title); ?>">
                              <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($alt); ?>" />
                          </a>
                          <h3><?php the_title(); ?></h3>
                          <p>Extra informatie</p>
                    </li>

                  <?php endforeach; ?>

                </div>
                <?php 
                // Reset the global post object so that the rest of the page works correctly.
                wp_reset_postdata(); ?>

          <?php endif; ?> 

    </div>
  </div>
      <?php 
      // Reset the global post object so that the rest of the page works correctly.
      wp_reset_postdata(); ?>

<?php endif; ?>








  <!-- OPTIE OVERIGE FILMS blok met interessante andere films -->

<?php 
//Get current active Genres:
$custom_terms = get_the_terms( $film_id, 'film-genre' );

if( !empty( $custom_terms ) ):
  $number = sizeof ($custom_terms);

    // going to hold our tax_query params
    $tax_query = array();

    // add the relation parameter (not sure if it causes trouble if only 1 term so what the heck)
    if( $number > 1 )
        $tax_query['relation'] = 'OR' ;

    // loop through venues and build a tax query
    foreach( $custom_terms as $custom_term ) {

        $tax_query[] = array(
            'taxonomy' => 'film-genre',
            'field' => 'slug',
            'terms' => $custom_term->slug,
        );

    }

    // put all the WP_Query args together
    $args = array( 'post_type' => 'films',
                    'post__not_in' => array($film_id),
                    'posts_per_page' => 5,
                    'tax_query' => $tax_query );

    // finally run the query
    $loop = new WP_Query($args);

    if( $loop->have_posts() ) : ?>
      <div class="alignwide block-aangeraden-films block-slider">
        <div class="title-element">
          <h2>Interessant</h2>
        </div>
        <div class="content-element">
          <span class="onderwerp h2">Voor jou </span>
          <!-- lijst met aangeraden films -->
          <div class="specials-block-films sliderlijst">
                  
                
            <?php
            while( $loop->have_posts() ) : $loop->the_post(); ?>

              <li>
                <?php
                $image = get_field('header_img');

                $url = $image['url'];
                $title = $image['title'];
                $alt = $image['alt'];
                $caption = $image['caption'];
            
                // Thumbnail size attributes.
                $size = 'filmsFeatImg';
                $thumb = $image['sizes'][ $size ];
                ?>
                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($title); ?>">
                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($alt); ?>" />
                    </a>
                    <h3><?php the_title(); ?></h3>
                    <p>Extra informatie</p>
              </li> 
                        
            <?php endwhile;?>

        </div>
        </div>
      </div>
   <?php endif; //loop

endif;
?>

</div>
