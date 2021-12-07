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
