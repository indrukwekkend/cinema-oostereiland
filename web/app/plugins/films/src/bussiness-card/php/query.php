<?php 
// Dit bestand haalt de Tickets van de film op. 
// Hier staan de ticket lijst in de modal die naar boven komt als je op "kaarten" klikt.
// TICKETURL is defined in env file!!

// import class ophalen

// Output voor AJAX call:
$output =  "<h2>Tickets</h2>";

// eerst kijken of deze pagina een ticketlab registratie heeft:
if (isset($_POST['film'])) {
    
    $id = ($_POST['id']);
    // eventID zuiveren van tekst
    $events = array("events", "/");
    $filmnummer = str_replace($events, "", $_POST['film'] );
    
     //kijk eerst in de database of de film tickets heeft:
    $refTable = 'tickets_'.$filmnummer;
    $shows = get_option( $refTable );
    // var_dump($shows);

    // optioneel, haal op vanuit de API, als de film niet is geladen:
    if (empty($shows) || $shows=='' ) {
        // haal de tickets op uit de class module:
        $tickets = new TicketlabCurl();

        $data = array (
            'datetime[strictly_after]' => date("Y-m-d\TH:i:s"),
            'order[datetime]' => 'asc',
            'eventid' => $filmnummer,
        );

        $voorstellingen = $tickets->getShowstoday( $data, true );
        update_option( $refTable, $voorstellingen );
        $shows = $voorstellingen;
        update_post_meta( $id, 'actief', false ); 
    }

    if ( function_exists( 'ticketlab_filter_public_shows' ) ) {
        $shows = ticketlab_filter_public_shows( $shows );
    }

    if (empty($shows)) : 
        // geen tickets gevonden:
        $output .=  "<p>Er zijn geen tickets meer voor deze voorstelling.</p>";
        // Zet de film op "inactief". Hierdoor komt hij daarna dan niet meer voor in de lijsten van 
        update_post_meta( $id, 'actief', false ); 
    
    else:
    // begin de lijst:
        $output .= '<ul class="tickets">';

        foreach($shows as $key => $show) {

            if ($key === 'status') {
                // print ($show); //controle op de status van de cache van de tickets. 
                continue;
            }

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
            // zaal zuiveren van tekst
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

            // TODO: integreren in de site
            // create ticketlab link
            $link = 'https://tickets.cinemaoostereiland.nl/shop/tickets.php?showid='. $show['@id'];

            $showId = intval(preg_replace('/[^0-9]+/', '', $show['@id']), 10);

            // Titel van het kaartje. Als variabele om te kunnen veranderen met Specials
            // $titel = 'Voorstelling zonder pauze';

            ///////////////////////////////////
            //
            // Output naar de site begint hier:
            //
            ////////////////////////////////

            $output .= '<li class="ticket">';

                //Datum
                $output .= '<div class="text datum">';
                $output .= '<a href="'.$link.'"></a>';
                    $output .= $datum;
                $output .= '</div>';

                // aanvang:
                $output .= '<div class="text aanvang">';
                    $output .= '<span class="aanhef">Aanvang</span>';
                    $output .= ' ';
                    $output .= "<span class='datum_notatie'>$tijd</span>";
                $output .= '</div>';

                // Zaal:
                $output .= '<div class="text zaal">';
                    $output .= '<span class="aanhef">Cinema</span>';
                    $output .= ' ';
                    if ($zaalnummer == 0) {
                        $output .= "<span class='datum_notatie'>Enkhuizen</span>";
                    } else {
                        $output .= "<span class='datum_notatie'>Zaal $zaalnummer</span>";
                    }
                $output .= '</div>';                

                 // Todo: Deze verbouwen tot een link in dit blok naar film bestel
                 $output .= '<div class="text special">';
                   
                    $output .= '<small>special</small>';
                $output .= '</div>';

                //  // Titel met URL
                //  $output .= '<div class="text titel">';
                //  //  $output .= '<h2><a href="'.$link.'">'.$titel.'</a></h2>';
                //   $output .= '<small><a href="#special-link">Film +</a></small>';
 

                // CTA:
                $output .= '<div class="cta">';
                    
                    $output .= '<a class="btn bestellen" href="#special-link"  data-site="'.TICKETURL.'" data-number="' . $showId . '">Bestellen</a>';

                $output .= '</div>';

            $output .= '</li>';
        }

        $output .= '</ul>'; 

    endif;

//als de film niet is gekoppeld aan een ticket systeem:
} else {
    $output .= '<p>Deze film is niet gekopeld met het ticket systeem</p>';
}

// return de output:
echo $output;
?>
              