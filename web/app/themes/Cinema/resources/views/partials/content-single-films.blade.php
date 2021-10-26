<div @php post_class() @endphp>

  <?php
  $film_id = get_the_id();
  $image = get_field('header_img');
  $backgroundImage = esc_url($image['url']); 
  // overige meta:
  $regisseur = get_post_meta( $film_id, 'regie', true );
  $label = get_post_meta( $film_id, 'extra_tekst_label', true );

  if ($label != '') {
    $label = '<span class="label">'.$label.'</span>';
  }
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
                  <p>&nbsp; / @php echo $regisseur . $label @endphp </p>
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

  <div class="wp-block-group alignfull has-festival-groen-background-color has-background">
    <div class="wp-block-group__inner-container">
      <div class="wp-block-indrukwekkend-festival alignwide">
        <div class="festival-content">
          
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="wp-block-columns">
            <div class="wp-block-column" style="flex-basis:55%">
              <img class="" src="<?=$backgroundImage?>">
            </div>
            <div class="wp-block-column" style="flex-basis:45%">
              <div class="text">
                <?php 
                    $intro = get_field( 'intro_tekst' ); 
                    if ($intro != '') {
                      echo "<p class='intro-text'>$intro</p>";
                    }
                ?>
                  <?php the_excerpt(); ?>
                  <a href="<?php the_permalink(); ?>">Lees alles over <?php the_title();?></a>
              </div>  
            </div>
          </div>
        </div>
        
        <div class="festival-films">
          <h3><?php the_field( 'reeks_titel' ); ?></h3>
          
          <?php
            // Hier de lijst met de films in het Festival
            $films = get_field('festival_films');
            
              if( $films ): 
                global $post; ?>
                <div class="festival-films-lijst filmsFeatImg slider">
                    
                  
                    <?php foreach( $films as $post ): 

                        // Setup this post for WP functions (variable must be named $post).
                        setup_postdata($post); 

                        $image = get_field('header_img');

                        $url = $image['url'];
                        $title = $image['title'];
                        $alt = $image['alt'];
                        $caption = $image['caption'];
                    
                        // Thumbnail size attributes.
                        $size = 'filmsFeatImg';
                        $thumb = $image['sizes'][ $size ];
                        $thumbnail = "<img src='$thumb' alt='$alt' />";
                    ?>

                      <li class='film'>
                        <div class="card <?= $size ?>">

                            <?php echo sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail);?>
                            <div class="text">
                              <a class="overlay" href="<?php the_permalink(); ?>" title="<?php echo esc_attr($title); ?>"></a>
                              <h3><?php the_title(); ?></h3>
                              <p>Extra informatie</p>
                            </div>
                        </div>
                      </li>

                    <?php endforeach; ?>

                  </div>
                  <?php 
                  // Reset the global post object so that the rest of the page works correctly.
                  wp_reset_postdata(); ?>

            <?php endif; ?> 

        </div>
      </div>
    </div>
  </div>
  <?php 
  // Reset the global post object so that the rest of the page works correctly.
  wp_reset_postdata(); ?>

<?php endif; ?>




<!-- OPTIE Special blok. Gekoppeld aan de Special CPT -->

<?php 

// For each special where === post ID 

$args = array(
  'post_type'			=> 	'specials',
  'meta_key'     => 'special_films',
  'meta_value'   => $film_id,
  //TODO naam verandert nog naar iest logischer
);

$the_query = new WP_Query( $args );

$list_items_markup = '';
if ($the_query->have_posts()):
	while ( $the_query->have_posts() ) : $the_query->the_post();
 
    		// Specail info
		$id = get_the_ID();
		$title = get_the_title();
		$link = get_permalink();
		$content = get_the_excerpt();
		$intro_tekst = get_field('intro_tekst');

		// Special koppeling
		$featured_post = get_field('special_films');
		$ticket = get_field('ticket' );

		$size = 'medium_large';
		$thumbnail = get_field( 'header_special', $id );

		if ( $thumbnail ) { 
			$url = $thumbnail['url'];
			$alt = esc_attr($thumbnail['alt']);
	
			// Thumbnail size attributes.
			$thumb = esc_url($thumbnail['sizes'][ $size ]);
			$thumbnail = "<img src='$thumb' alt='$alt' />";
		}


		$list_items_markup .= '<div class="special-content">';

			$list_items_markup .= sprintf(
				'<h2><a href="%2$s">%1$s</a></h2>',
				$title,
				$link
			);

			$list_items_markup .= '<div class="wp-block-columns">';
				$list_items_markup .= '<div class="wp-block-column image" style="flex-basis:65%">';
					$list_items_markup .= $thumbnail;
					$list_items_markup .= '<div class="movie_title">';
						$list_items_markup .= get_the_title($featured_post);
					$list_items_markup .= "</div>";
				$list_items_markup .= "</div>";

				$list_items_markup .= '<div class="wp-block-column" style="flex-basis:35%">';

					$list_items_markup .= sprintf(
						'<div class="text"><p class="intro-text">%1$s</p> <p>%2$s</p><a class="btn wp-block-button__link" href="%3$s">Kaarten</a><a class="btn" href="%4$s">Meer info over %5$s</a></div>',
						$intro_tekst,
						$content,
						$ticket,
						$link,
						$title
					);
				$list_items_markup .= "</div>";
			$list_items_markup .= "</div>";
		$list_items_markup .= "</div>";
		$list_items_markup .= '<div class="special-films">';
			
			$list_items_markup .= "<br>";
			$list_items_markup .= $ticket;

					// test
		$list_items_markup .= "</div>";


  endwhile;

  //Classes in de section
  $class = 'wp-block-indrukwekkend-special';
  if ( isset( $attributes['align'] ) ) {
    $class .= ' align' . $attributes['align'];
  }

  if ( isset( $attributes['className'] ) ) {
    $class .= ' ' . $attributes['className'];
  }

  echo sprintf(
    '<section class="%1$s">%2$s</section>',
      esc_attr( $class ),
      $list_items_markup
    );

endif;
wp_reset_postdata();



?>



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

              <li class='film'>
                <?php
                  $image = get_field('header_img');

                  $url = $image['url'];
                  $title = $image['title'];
                  $alt = $image['alt'];
                  $caption = $image['caption'];
              
                  // Thumbnail size attributes.
                  $size = 'filmsFeatImg';
                  $thumb = $image['sizes'][ $size ];

                  $thumbnail = "<img src='$thumb' alt='$alt' />";
                ?>

                <div class="card filmsFeatImg">

                    <?php echo sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail); ?>

                    <div class="text">
                      <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($title); ?>" class="overlay"></a>
                      <h3><?php the_title(); ?></h3>
                      <p class='extra'>Extra informatie</p>
                    </div>
                </div>
              </li> 
                        
            <?php endwhile;?>

        </div>
        </div>
      </div>
   <?php endif; //loop

endif;
?>

</div>
