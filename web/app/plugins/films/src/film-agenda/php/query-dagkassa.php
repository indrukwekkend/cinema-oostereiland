<?php 
/**
 * Dit bestuurd de queries die de agenda punten ophalen uit de database of uit de 
 * Ticketlab API.
 * 
 * 4 mogelijkheden:
 * 1. Datum query
 * 2. Query op Genre: haal tickets op "kinderen" bijvoorbeeld
 * 3. Films in alfabetische volgorde ophalen
 * 4. Films van vandaag ophalen (default)
 * 
 * 
 */

// import class ophalen (API en SESSION)
require_once("curl-import.php");

// Output voor AJAX call:
$output =  "";

// er is altijd maar 1 waarde geldig. 
// Als ze allemaal ongeldig zijn, dan is het de kalender van vandaag. 

    $output .= '';

    //data settings
    $today = strtotime('today');
    $tomorrow = strtotime('tomorrow +3 hours');
    $now = strtotime('now');
    // Datetime in data zetten: Vandaag en morgen 
    $data = array (
        'datumvandaag' => date("Y-m-d", $today),
        'datetime[strictly_after]' => date("Y-m-d\TH:i:s", $today),
        'datetime[strictly_before]' => date("Y-m-d\TH:i:s", $tomorrow),
        'order[datetime]' => 'asc',
    );

    $tickets = new TicketlabCurl();
    $shows = $tickets->getShowstoday( $data );

    $output .= getTicketTable($shows);

/**
 * Start met de queries
 * Op basis van de input variabelen
 */
echo $output;

?>
        
<?php
/**
 *  Hier functies om de tickets weer te geven in de verschillende vormen:
 * 
 *  Vorm 1.
 * Haal tickets per datum op (bijvoorbeeld vandaag)
 * 
 * Vorm 2 haal tickets per FilmSoort (genre) op.
 * 
 * 
 * 
 * */


// Vorm 1 maak een lijst met tickets vanuit de input uit de 

function getTicketTable($shows) {

    $output = '<ul class="tickets">';

    foreach($shows as $key => $show) {

        if ($key === 'status') {
            //print ($show);
            continue;
        }

        // Ticketlab: alleen ingeplande + gepubliceerde voorstellingen.
        if ( function_exists( 'ticketlab_is_public_show' ) && ! ticketlab_is_public_show( $show ) ) {
            continue;
        }

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

        // TODO: integreren in de site
        $link = 'https://tickets.cinemaoostereiland.nl/shop/tickets.php?showid='. $shownumber;
        
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

        $args = array(
            'numberposts'	=> 1,
            'post_type'		=> 'films',
            'meta_key'		=> 'ticketlab_id',
            'meta_value'	=> $show['eventid'],
        );

        $the_query = new WP_Query( $args );

        $titel = 'nog geen titel';
        $film_info = '';

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

            $film_info .= $duur .' min. '.$taal.' '.$regisseur;

        endwhile;

        ///////////////////////////////////
        //
        // Output naar de site begint hier:
        //
        //////////////////////////////////

        $output .= '<li class="ticket">';


            //TODO: Specials
            // Titel met URL
            $output .= '<div class="text titel">';
                $output .= '<h2><a href="'.$filmlink.'">'.$titel.'</a></h2>';
                $output .= '<p class="film-info">'.$film_info.'</p>';

            $output .= '</div>';
            // Tijd:

            // Zaal:
            $output .= '<div class="text tijd">';
                $output .= '<a class="btn bestellen" href="'.$link.'">'.$tijd.'</a>';
            $output .= '</div>';


        $output .= '</li>';
    }

    $output .= '</ul>';
    wp_reset_postdata();
    return $output;
}

?>
