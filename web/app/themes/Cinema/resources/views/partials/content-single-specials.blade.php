<div @php post_class() @endphp>

<?php
  $image = get_field('header_festival');
  $backgroundImage = esc_url($image['url']); 

  // overige meta:

  $intro_tekst = get_field('intro_tekst');
  $datum = get_field('ticketDatum');

  $format = "Ymd H:i";
  $date = date_create_from_format($format, $datum);

  $id = get_the_ID();
  $title = get_the_title();
  $link = get_permalink();
  $content = get_the_content();
  $intro_tekst = get_field('intro_tekst');

  // Special koppeling
  $film_id = get_field('special_films');
  $ticket = get_field('ticket', $id);
  $datum = get_field('ticketDatum', $id);

  $filmtitel = get_the_title($film_id);

  $format = "Ymd H:i";
  $date = date_create_from_format($format, $datum);

  $ticketDatum = date_format($date, 'd M Y H:i');
  // $ticketDatum = $date;

  //afbeeldingen
  $size = 'medium_large';
  $thumbnail = get_field( 'header_special', $id );

  if ( $thumbnail ) { 
    $url = $thumbnail['url'];
    $alt = esc_attr($thumbnail['alt']);

    // Thumbnail size attributes.
    $thumb = esc_url($thumbnail['sizes'][ $size ]);
    $thumbnail = "<img src='$thumb' alt='$alt' />";
  }

  ?>

  <header class="page-header alignfull">

<?php
	$list_items_markup = '';
    $list_items_markup .= '<div class="special-content alignwide">';

			$list_items_markup .= sprintf(
				'<h1>%1$s</h1>
        <h2>%2$s</h2>
        %3$s
        %4$s',
				$title,
        $intro_tekst,
        $ticketDatum,
        $filmtitel,
			);

			$list_items_markup .= '<div class="wp-block-columns">';
				$list_items_markup .= '<div class="wp-block-column image" style="flex-basis:50%">';
					$list_items_markup .= $thumbnail;
					$list_items_markup .= '<div class="movie_title">';
						$list_items_markup .= $filmtitel;
					$list_items_markup .= "</div>";
				$list_items_markup .= "</div>";

				$list_items_markup .= '<div class="wp-block-column" style="flex-basis:50%">';

					$list_items_markup .= sprintf(
						'<div class="text"><p class="intro-text">%1$s</p> <p>%2$s</p> <a class="btn wp-block-button__link" href="%3$s">Kaarten</a></div>',
						$ticketDatum,
						$content,
						$ticket
					);
				$list_items_markup .= "</div>";
			$list_items_markup .= "</div>";
		$list_items_markup .= "</div>";

  echo $list_items_markup;
?>

  
  </header>


<?php
  $film_id = get_field('special_films');
  // De geselecteerde film:
  if ($film_id != '') :?>

    <section class="film-details alignfull">
      <div class="container alignwide">

      <?php  // The Query
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
                  echo get_the_content();
              }

          } else {
              // no posts found
          }
          /* Restore original Post Data */
          wp_reset_postdata();

      ?>

      <h3>Op een ander moment naar deze film? Bekijk alle kaarten voor deze voorstelling.</h3>
    </div>
  </div>
<?php endif;?>

</div>
