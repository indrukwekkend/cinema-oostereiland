<?php

function getFilmLijst($shows, $showsNietTonen = false,  $staand = false ) {

	$list_items_markup = '';
	$eventIds = array();

	// deze sluit films uit die we niet willen tonen op basis van een andere lijst (verwacht en deze week)
	if ($showsNietTonen) {
		foreach($showsNietTonen as $key => $show) {
			if ($key === 'status' || in_array($show['eventid'], $eventIds) ) {
				//print ($show);
				continue;
			}
			// (nog) niet overgeslagen, dus het ID in de lijst,
			$eventIds[] = $show['eventid'];
		}
	}




	foreach($shows as $key => $show) {

    // eerste key kan status aangeven, dus die slaan we over;
    // ik wil deze filmlijst uniek maken, in volgorde van de tickets, dus elke film die al in de lijst staat sla ik over
			if ($key === 'status' || in_array($show['eventid'], $eventIds) ) {
					//print ($show);
					continue;
			}

			// Ticketlab: alleen ingeplande + gepubliceerde voorstellingen.
			if ( function_exists( 'ticketlab_is_public_show' ) && ! ticketlab_is_public_show( $show ) ) {
				continue;
			}

      // (nog) niet overgeslagen, dus het ID in de lijst, zodat de volgende wordt overgeslagen (uniek maken)
      $eventIds[] = $show['eventid'];

			$dateTimestamp1 = strtotime($show['datetime']);
			$dateTimestamp2 = strtotime('now UTC +15 minutes');

			// Compare the timestamp date 15 minuten voor aanvang geen kaartverkoop via de site:
			// if ($dateTimestamp1 < $dateTimestamp2) continue;

			/**
			 * Shows heeft de volgende variabelen beschikbaar:
			 * 
			 * '@id' => string '/shows/29817' (length=12)
			 * '@type' => string 'Show' (length=4)
			 * 'creationdate' => string '2021-07-26T09:32:01+00:00' (length=25)
			 * 'scheduled' => string 'Y' (length=1)
			 * 'datetime' => string '2021-07-30T14:00:00+00:00' (length=25)
			 * 'pauselength' => int 0
			 * 'eventid' => string '/events/1888' (length=12)
			 * 'locationid' => string '/locations/3' (length=12)
			 * 'leaderlength' => int 0
			 * 'seating' => string 'Y' (length=1)
			 * 'seatlimit'
			 */

			// Zaalnummer
			// zaal zuiveren van tekst, Zaalnummer met 1 verlagen...
			$location = array("locations", "/");
			$zaalnummer = str_replace( $location, "", $show['locationid'] );
			$zaalnummer = intval($zaalnummer) - 1;

			//TODO: Specials voor de tickets uitzoeken
			// if is in array: get stuff titel van de special ophalen. link naar de special!

			//vorm datum en tijd naar bruikbaar NL:
			$format = 'Y-m-d\TH:i:s\+\0\0\:\0\0';
			$date = DateTime::createFromFormat($format, $show['datetime'], new DateTimeZone('UTC'));

			$nl_date = $date;
			$nl_date->setTimeZone(new DateTimeZone('Europe/Amsterdam'));
			setlocale(LC_ALL, 'nl_NL.utf8');

			/* Output: vrijdag 22 december 1978 */
			$datum = films_strftime_nl("%a <span class='datum_notatie'>%e %b</span>", strtotime($show['datetime']));
			$tijd = $nl_date->format('H:i');

			// create ticketlab link
			$shows_replace = array("shows", "/");
			$shownumber = str_replace( $shows_replace, "", $show['@id'] );

			$special_id = getMetaData($show['@id']);

			// Alle specials in en array plaatsen
			

			// OUD, dit gaat met JS in iFrame via het Thema JS
			// $link = 'https://tickets.cinemaoostereiland.nl/shop/tickets.php?showid='. $shownumber;
			$link = '#';
			
			////////////////////////////////
			//
			// Ophalen van de film WPDB
			//
			///////////////////////////////////

			global $wp_query;
			// Put default query object in a temp variable
			// $tmp_query = $wp_query;
			// // Now wipe it out completely
			// $wp_query = null;
			// // Re-populate the global with our custom query
			// $wp_query = $the_query;

			$titel = 'Geen titel' . $show['eventid'] ;
			$class = ' hidden';
			$filmlink = '';
			$film_info = 'Deze film is nog niet ingeladen';

			$args = array(
					'numberposts'	=> 1,
					'post_type'		=> 'films',
					'meta_key'		=> 'ticketlab_id',
					'meta_value'	=> $show['eventid'],
					'post_status' => array( 'publish' ),
			);

			$the_query = new WP_Query( $args );


      while ( $the_query->have_posts() ) : $the_query->the_post();
		
      $id = get_the_ID();
  
      $title = get_the_title();
      if ( ! $title ) {
        $title = __( '(no title)' );
      }
  
      $filmlink = get_the_permalink();
  
      $regisseur = get_post_meta( $id, 'regie', true );
  
      //$soort = $main_page_title;
      $soort='';
      $terms = get_the_terms( $id, 'film-genre' );
      if ( !empty( $terms ) ){
        // get the first term
        $term = array_shift( $terms );
        $soort = $term->name;
      }
  
      // TODO hier een andere om de kleinere in een grid te tonen
      // TODO title en alt teksten toevoegen aan de afbeeldingen
      
      if ($staand) {
        $thumbnail = get_field( 'alternatieve_afbeelding', $id );
        $size = 'medium_large';
      } else {
          $size = 'filmsFeatImg';
          $thumbnail = get_field( 'header_img', $id );
      }
  
      if ( $thumbnail ) { 
        $alt = 'Afbeelding van de Film:' . $title;
  
        if( is_array($thumbnail) ) {
  
          $thumb = esc_url($thumbnail['sizes'][ $size ]);
          $alt .= ' ' . esc_attr($thumbnail['alt']);
  
        } else {
  
          $thumb = wp_get_attachment_image_url( $thumbnail, $size );
        }
  
    
        // Thumbnail size attributes.
        // 
  
        $thumbnail = "<img src='$thumb' alt='$alt' />";
  
      }
  
      ////////////////////////////
      //
      // LI output
      //
      /////////////////////////////////
  
  
      $meta = '';
      $regisseur = "<div class='col content_row__content'>Regie: $regisseur</div>";
      include 'kijkwijzer.php';
  
  
      $list_items_markup .= '<li class="film">';
    
      // has thumbnail in de card class
      $list_items_markup .= '<div class="card ';
        if ( $thumbnail ) { 
          $list_items_markup .= 'has-thumbnail ';
          $list_items_markup .= $size;
        }
      $list_items_markup .= '">';
  
        if ( $thumbnail ) {
          $list_items_markup .= sprintf(
            '<picture class="thumbnail">%1$s</picture>',
            $thumbnail
          );
        }
  
        //overlay teksten
        $list_items_markup .= '<div class="text">';
        $list_items_markup .= '<a class="overlay" href="'.$filmlink.'" title="'.$title.'"></a>';
        $list_items_markup .= sprintf(
          '<div class="extra">%1$s</div>',
          $meta
        ); 
  
        
      $list_items_markup .= '</div>';
           
        $list_items_markup .= "<div class='title'>
        <h3>" .$title. "</h3>
        ". $regisseur ."
        </div>";
  
  
  
      $list_items_markup .= "</div></li>";
  
  
    endwhile;


			///////////////////////////////////
			//
			// Output naar de site begint hier:
			//
			//////////////////////////////////

		// 	$output .= '<li class="ticket'.$class.'">';
					
		// 			//Datum
		// 			$output .= '<div class="text datum">';
		// 					$output .= $datum;
		// 			$output .= '</div>';

		// 			//TODO: Specials
		// 			// Titel met URL
		// 			$output .= '<div class="text titel">';
		// 					$output .= '<h2><a href="'.$filmlink.'">'.$titel.'</a></h2>';
		// 					$output .= '<p class="film-info">'.$film_info.'</p>';

		// 			$output .= '</div>';
		// 			// Tijd:
		// 			$output .= '<div class="text tijd">';
							
		// 			$output .= '</div>';

		// 			// Zaal:
		// 			$output .= '<div class="text zaal">';
		// 					$output .= "Zaal $zaalnummer";
		// 					$output .= ' ';
		// 					$output .= '<span class="tijd">'.$tijd.'</span>';
		// 			$output .= '</div>';

					
		// 			// CTA:
		// 			$output .= '<div class="cta text">';
		// 					$output .= '<a class="btn bestellen" data-number="'.$shownumber.'" data-site="'.TICKETURL.'" href="'.$link.'">Tickets</a>';
		// 			$output .= '</div>';

    // $list_items_markup .= "</div></li>";
	}


	wp_reset_postdata();
	return $list_items_markup;
}


function getFilmsByCategory($perpage = 4, $order = 'asc', $orderBy = 'title', $category = '', $premiereFilms = false, $paged = 1, $staand = false ) {

  $list_items_markup  = '';
  $paged = (!empty($paged)) ? $paged : 1;

  //filter voor handmatige film lijst
  $premiereFilmsMeta = array();
  if ($premiereFilms) {
		$premiereFilmsMeta = array(
			'key'   => 'premiere_ticket',
			'value' => '1',
		);
	}

  $args = array(
		'posts_per_page'   	=> $perpage,
		'paged' 						=> $paged,
		'post_type'        	=> 'films',
		'post_status'      	=> 'publish',
		'order'            	=> $order,
		'orderby'          	=> $orderBy,
		'suppress_filters' 	=> false,
		'meta_query' => array(
			'relation' => 'AND',
				array(
						'key'   => 'actief',
						'value' => '1',
				),
				$premiereFilmsMeta
		)
	);

	if ( isset( $category ) && $category != '' ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'film-genre',
				'field' => 'id',
				'terms' =>  $category
			)
		);
	}

	$the_query = new WP_Query( $args );



	global $wp_query;
	// Put default query object in a temp variable
	$tmp_query = $wp_query;
	// Now wipe it out completely
	$wp_query = null;
	// Re-populate the global with our custom query
	$wp_query = $the_query;

	while ( $the_query->have_posts() ) : $the_query->the_post();
		
		$id = get_the_ID();

		$title = get_the_title();

		// echo $title;
		
		if ( ! $title ) {
			$title = __( '(no title)' );
		}

		$filmlink = get_the_permalink();

		$regisseur = get_post_meta( $id, 'regie', true );

		//$soort = $main_page_title;
		$soort='';
		$terms = get_the_terms( $id, 'film-genre' );
		if ( !empty( $terms ) ){
			// get the first term
			$term = array_shift( $terms );
			$soort = $term->name;
		}

		// TODO hier een andere om de kleinere in een grid te tonen
		// TODO title en alt teksten toevoegen aan de afbeeldingen
		
		if ($staand) {
			$thumbnail = get_field( 'alternatieve_afbeelding', $id );
			$size = 'medium_large';
		} else {
				$size = 'filmsFeatImg';
				$thumbnail = get_field( 'header_img', $id );
		}

		if ( $thumbnail ) { 
			$alt = 'Afbeelding van de Film:' . $title;

			if( is_array($thumbnail) ) {

				$thumb = esc_url($thumbnail['sizes'][ $size ]);
				$alt .= ' ' . esc_attr($thumbnail['alt']);

			} else {

				$thumb = wp_get_attachment_image_url( $thumbnail, $size );
			}

	
			// Thumbnail size attributes.
			// 

			$thumbnail = "<img src='$thumb' alt='$alt' />";

		}

		////////////////////////////
		//
		// LI output
		//
		/////////////////////////////////


		$meta = '';
		$regisseur = "<div class='col content_row__content'>Regie: $regisseur</div>";
		include 'kijkwijzer.php';


		$list_items_markup .= '<li class="film">';
	
		// has thumbnail in de card class
		$list_items_markup .= '<div class="card ';
			if ( $thumbnail ) { 
				$list_items_markup .= 'has-thumbnail ';
				$list_items_markup .= $size;
			}
		$list_items_markup .= '">';

			if ( $thumbnail ) {
				$list_items_markup .= sprintf(
					'<picture class="thumbnail">%1$s</picture>',
					$thumbnail
				);
			}

			//overlay teksten
			$list_items_markup .= '<div class="text">';
			$list_items_markup .= '<a class="overlay" href="'.$filmlink.'" title="'.$title.'"></a>';
			$list_items_markup .= sprintf(
				'<div class="extra">%1$s</div>',
				$meta
			); 

			
		$list_items_markup .= '</div>';
				 
			$list_items_markup .= "<div class='title'>
			<h3>" .$title. "</h3>
			". $regisseur ."
			</div>";



		$list_items_markup .= "</div></li>";


	endwhile;

	wp_reset_postdata();

  $wp_query = null;
  $wp_query = $tmp_query;

  return $list_items_markup;

}


function getMetaData($ticket) {
	global $wpdb;
	$specials = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_value LIKE '%$ticket%' AND meta_key = 'ticket'");
	 
	$special_id = '';
	foreach ($specials as $special ) {
		$special_id = $special->post_id;
	}

	return $special_id;
}

function getTicketTable($shows) {

	$output = '<ul class="tickets">';

	$dateTimestamp2 = strtotime('now UTC -15 minutes');
	// echo $dateTimestamp2;


	foreach($shows as $key => $show) {

			if ($key === 'status') {
					//print ($show);
					continue;
			}

			// Ticketlab: alleen ingeplande + gepubliceerde voorstellingen.
			if ( function_exists( 'ticketlab_is_public_show' ) && ! ticketlab_is_public_show( $show ) ) {
				continue;
			}

			// var_dump($show);

			$dateTimestamp1 = strtotime($show['datetime']);

			// echo '-'.$dateTimestamp1;

			// Compare the timestamp date 15 minuten voor aanvang geen kaartverkoop via de site:
			// if ($dateTimestamp1 < $dateTimestamp2) continue;

	
			/**
			 * Shows heeft de volgende variabelen beschikbaar:
			 * 
			 * '@id' => string '/shows/29817' (length=12)
			 * '@type' => string 'Show' (length=4)
			 * 'creationdate' => string '2021-07-26T09:32:01+00:00' (length=25)
			 * 'scheduled' => string 'Y' (length=1)
			 * 'datetime' => string '2021-07-30T14:00:00+00:00' (length=25)
			 * 'pauselength' => int 0
			 * 'eventid' => string '/events/1888' (length=12)
			 * 'locationid' => string '/locations/3' (length=12)
			 * 'leaderlength' => int 0
			 * 'seating' => string 'Y' (length=1)
			 * 'seatlimit'
			 */

			// Zaalnummer
			// zaal zuiveren van tekst, Zaalnummer met 1 verlagen...
			$location = array("locations", "/");
			$zaalnummer = str_replace( $location, "", $show['locationid'] );
			$zaalnummer = intval($zaalnummer) - 1;

			//TODO: Specials voor de tickets uitzoeken
			// if is in array: get stuff titel van de special ophalen. link naar de special!

			//vorm datum en tijd naar bruikbaar NL:
			$format = 'Y-m-d\TH:i:s\+\0\0\:\0\0';
			$date = DateTime::createFromFormat($format, $show['datetime'], new DateTimeZone('UTC'));

			$nl_date = $date;
			$nl_date->setTimeZone(new DateTimeZone('Europe/Amsterdam'));
			setlocale(LC_ALL, 'nl_NL.utf8');

			/* Output: vrijdag 22 december 1978 */
			$datum = films_strftime_nl("%a <span class='datum_notatie'>%e %b</span>", strtotime($show['datetime']));
			$tijd = $nl_date->format('H:i');

			// create ticketlab link
			$shows_replace = array("shows", "/");
			$shownumber = str_replace( $shows_replace, "", $show['@id'] );

			$special_id = getMetaData($show['@id']);

			// Alle specials in en array plaatsen
			

			// OUD, dit gaat met JS in iFrame via het Thema JS
			// $link = 'https://tickets.cinemaoostereiland.nl/shop/tickets.php?showid='. $shownumber;
			$link = '#';
			
			////////////////////////////////
			//
			// Ophalen van de film WPDB
			//
			///////////////////////////////////

			global $wp_query;
			// Put default query object in a temp variable
			// $tmp_query = $wp_query;
			// // Now wipe it out completely
			// $wp_query = null;
			// // Re-populate the global with our custom query
			// $wp_query = $the_query;

			$titel = 'Geen titel' . $show['eventid'] ;
			$class = ' hidden';
			$filmlink = '';
			$film_info = 'Deze film is nog niet ingeladen';

			$args = array(
					'numberposts'	=> 1,
					'post_type'		=> 'films',
					'meta_key'		=> 'ticketlab_id',
					'meta_value'	=> $show['eventid'],
					'post_status' => array( 'draft', 'publish' ),

			);

			$the_query = new WP_Query( $args );


			while ( $the_query->have_posts() ) : $the_query->the_post();
				 
					$id = get_the_ID();
					$titel = get_the_title();
					$filmlink = get_the_permalink();
					$film_info = '';
					// leeg de 'hidden' class
					$class = '';
					$post_status = get_post_status();

					if  ($post_status === 'draft') {
							$film_info = 'Deze film Staat nog op CONCEPT';
							$class = ' hidden';
					}

					$regisseur = '';
					$duur = '';
					$land = '';

					//metadata
					$regisseur = get_post_meta( $id, 'regie', true );
					$duur = get_post_meta( $id, 'duur', true );
					$taal = '';
					$land = get_post_meta( $id, 'land', true );
					$label = get_post_meta( $id, 'extra_tekst_label', true );

					//label
					if ($label != '' || $label != 0 ) {
							$label = '<span class="label">'.$label.'</span>';
					}

					//special
					if ($special_id != '') {
							$special_link = get_the_permalink($special_id);
							$special_id = '<a href="'.$special_link.'" class="special">Special</a>';
					}

					$film_info .= $duur .' min. '.$taal.' '.$regisseur .$label. $special_id;



			endwhile;

			if ($class === ' hidden' && is_user_logged_in() ) {
					$class = ' admin';
			}
			///////////////////////////////////
			//
			// Output naar de site begint hier:
			//
			//////////////////////////////////

			$output .= '<li class="ticket'.$class.'">';
					
					//Datum
					$output .= '<div class="text datum">';
							$output .= $datum;
					$output .= '</div>';

					//TODO: Specials
					// Titel met URL
					$output .= '<div class="text titel">';
							$output .= '<h2><a href="'.$filmlink.'">'.$titel.'</a></h2>';
							$output .= '<p class="film-info">'.$film_info.'</p>';

					$output .= '</div>';
					// Tijd:
					$output .= '<div class="text tijd">';
							
					$output .= '</div>';

					// Zaal:
					$output .= '<div class="text zaal">';
							if ($zaalnummer != 0) {
								$output .= "Zaal $zaalnummer";
							} 
							$output .= ' ';
							$output .= '<span class="tijd">'.$tijd.'</span>';
					$output .= '</div>';

					
					// CTA:
					$output .= '<div class="cta text">';
							$output .= '<a class="btn bestellen" data-number="'.$shownumber.'" data-site="'.TICKETURL.'" href="'.$link.'">Tickets</a>';
					$output .= '</div>';

			$output .= '</li>';
	}

	$output .= '</ul>';
	wp_reset_postdata();
	return $output;
}

function get_excerpt_trim($num_words='20', $more='...'){
	$excerpt = get_the_excerpt();
	$excerpt = wp_trim_words( $excerpt, $num_words , $more );
	return $excerpt;
}

function getFilmTable($searchfield = '') {

	// if soort == order dan A-Z

	// anders de film per Genre of soort ophalen, die nog splitsen naar: genre of soort, lastig, lastig
	global $wp_query;

	$output = '';
	
	if ($searchfield == '' || $searchfield == 'az' ) {
			$searchfield = '';
	} else {
			$output .= '<h4>Gezocht naar: ' . $searchfield . '</h4>';
	}

	// $output = "ingevuld: $searchfield";

			$args = array(
					'numberposts'	=> -1,
					'post_type'		=> 'films',
					's' => $searchfield,
					'orderby' => 'title',
					'post_status' => array( 'publish' ),
					'order' => 'ASC',
					'meta_query' => array(
							array(
									'key'   => 'actief',
									'value' => '1',
							)
					)
			);

			$the_query = new WP_Query( $args );

			
			// Put default query object in a temp variable
			$tmp_query = $wp_query;
			// Now wipe it out completely
			$wp_query = null;
			// Re-populate the global with our custom query
			$wp_query = $the_query;

		 

			if ( $the_query->have_posts() ) {

					while ( $the_query->have_posts() ) : $the_query->the_post();
					
							$id = get_the_ID();
							$titel = get_the_title();
							$filmlink = get_the_permalink();
							// $content = get_the_excerpt();
							$content = get_excerpt_trim();
							$intro_tekst = get_field('intro_tekst');

							$image = get_field('header_img');

							$url = $image['url'];
							$title = $image['title'];
							$alt = $image['alt'];
							$caption = $image['caption'];
					
							// Thumbnail size attributes.
							$size = 'filmsFeatImg';
							$thumb = $image['sizes'][ $size ];

							$thumbnail = "<img src='$thumb' alt='$alt' />";

							$film_info = '';
							$regisseur = '';
							$duur = '';
							$land = '';

							//metadata
							$regisseur = get_post_meta( $id, 'regie', true );
							$duur = get_post_meta( $id, 'duur', true );
							$land = get_post_meta( $id, 'land', true );

							$film_info .= $duur .' min. '.$regisseur;

							$output .= '<li class="film">';
									$output .= sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail);
									$output .= '<div class="text">';
											$output .= "<a href='$filmlink' title='$titel' class='overlay'></a>";
											$output .= "<h4>$titel</h4>";
											$output .= "<p class='intro'>$film_info</p>";
											$output .= "<p class='body'>$content</p>";
											$output .= '<a class="btn tickets" href="'.$filmlink.'/?kaarten=1">Tickets</a>';
									$output .= '</div>';
							$output .= '</li>';

					endwhile;
			} else {
					$output .= '<p>Geen films gevonden </p>';
			}

			wp_reset_postdata();

			return $output;
}

function getSpecialTable() {

	$output = 'Op dit moment zijn er geen speciale voorstellingen gepland';

	// if soort == order dan A-Z

	// anders de film per Genre of soort ophalen, die nog splitsen naar: genre of soort, lastig, lastig
	global $wp_query;

	$args = array(
			'numberposts'	=> -1,
			'post_type'		=> array ('specials', 'festivals'),
			'post_status' => array( 'publish' ),
			'meta_key' => 'ticketDatum',
			'orderby' => 'meta_value_num',
			'meta_type' => 'DATE',
			'order' => 'ASC'
	);

	$the_query = new WP_Query( $args );
			
	// // Put default query object in a temp variable
	// $tmp_query = $wp_query;
	// // Now wipe it out completely
	// $wp_query = null;
	// // Re-populate the global with our custom query
	// $wp_query = $the_query;

	if ( $the_query->have_posts() ) :
		$output = '';

		while ( $the_query->have_posts() ) : $the_query->the_post();
				
				$id = get_the_ID();
				$titel = get_the_title();
				$post_type = get_post_type();
				$filmlink = get_the_permalink();
				// $content = get_the_excerpt();
				$content = get_excerpt_trim();
				$intro_tekst = get_field('intro_tekst');
				$datum = get_field('ticketDatum');

				$format = "Ymd H:i";

				$date = date_create_from_format($format, $datum);

				if ($post_type === 'festivals') {
						$image = get_field('header_festival');
						$class = 'festival';
				} else {
						$image = get_field('header_special');
						$class = 'special';
				}

				$url = $image['url'];
				$title = $image['title'];
				$alt = $image['alt'];
				$caption = $image['caption'];
		
				// Thumbnail size attributes.
				$size = 'filmsFeatImg';
				$thumb = $image['sizes'][ $size ];

				$thumbnail = "<img src='$thumb' alt='$alt' />";

				$film_info = '';
				$regisseur = '';
				$duur = '';
				$land = '';

				//metadata
				$regisseur = get_post_meta( $id, 'regie', true );
				$duur = get_post_meta( $id, 'duur', true );
				$land = get_post_meta( $id, 'land', true );

				// $film_info .= $duur .' min. '.$regisseur;
				$film_info .= $intro_tekst;

				$output .= "<li class='film $class'>";
						$output .= sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail);
						$output .= '<div class="text">';
								$output .= "<a href='$filmlink' title='$titel' class='overlay'></a>";
								$output .= "<h4>$titel</h4>";
						// 		// $output .= "$post_type"; //test het type
								// $output .=  date_format($date, 'Y/m/d H:i');
								$output .= "<p class='intro'>$film_info</p>";
								$output .= "<p class='body'>$content</p>";
								$output .= '<a class="btn tickets" href="'.$filmlink.'/?kaarten=1">Info</a>';
						$output .= '</div>';
				$output .= '</li>';

		endwhile;
	endif;

	// wp_reset_postdata();

	return $output;
}

?>
