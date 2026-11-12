<?php
// Logging class initialization
// Enige actieve? Wort in elk geval aangeroepen vanuit de FireBase functie
// en de DB update functie

if ( ! function_exists( 'ticketlab_filter_public_shows' ) ) {
	require_once __DIR__ . '/ticketlab-shows.php';
}

class TicketlabCurl {

    public $url = 'https://api.ticketlab.io';

    public function __construct() {

        $bearerToken = get_option( 'bearerToken' );

        if ( $bearerToken == '' ) {
			$this->getToken();
		} else {
			$this->check_token($bearerToken);
		}
    }

    public function getBearerToken() {
        return get_option( 'bearerToken' );
    }

    private function tokenNeedsRefresh( $response, $http_code ) {
        if ( 401 !== (int) $http_code ) {
            return false;
        }
        $message = json_decode( $response, true );
        if ( ! is_array( $message ) || empty( $message['message'] ) ) {
            return true;
        }
        $known = array( 'Expired JWT Token', 'JWT Token not found', ' Token not found', 'Expired Token' );
        return in_array( $message['message'], $known, true );
    }

    private function getToken() {

        // echo 'getToken  <br>';

        $request_url = 'https://auth.ticketlab.io/tokens';

        $headers = array(
        'Content-Type: application/json',
        );
        
        $username = get_field('ticketlab_username', 'option');	
        $password = get_field('ticketlab_password', 'option');	

        // voor een "kapotte site" default CINEMA OOSTEREILAND
        if ($username == '') {
            $username = 'indrukwekkend_co';	
            $password = 'le9azoqHKjr2YDvc';	
        }
        
        $post_data = (object) array(
            'username'          =>     $username,
            'password'          =>      $password,
        );
        
        $handle = curl_init();
        
        curl_setopt_array($handle, array(
            CURLOPT_URL                     =>      $request_url,
            CURLOPT_HTTPHEADER              =>      $headers,
            CURLOPT_RETURNTRANSFER          =>      true,
            CURLOPT_POST                    =>      1,
            CURLOPT_POSTFIELDS              =>      json_encode($post_data),
            CURLOPT_SSL_VERIFYPEER          =>      1,
            CURLOPT_PROXY                   =>      '',
            CURLOPT_TIMEOUT                 =>      30
        ));
        
        $response = curl_exec($handle);
        
        if( curl_errno($handle) ) {
            $str = sprintf( "curl_exec() returned error '%s' (%d)", curl_error($handle), curl_errno($handle) );
            curl_close($handle);
            die($str);
        }
        
        $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        
        if( 200 != $http_code ) {
            // echo $password;
        $str = sprintf( "Error retrieving token 4: '%s' (%d)", (string) $response, $http_code );
        die($str);
        }
        
        $tokens = json_decode($response);
        
        // setcookie("TOKEN", $tokens->token);
        // $_SESSION["TOKEN"] = $tokens->token; 
        
        // $log->lwrite('Token opgehaald: , ' . $tokens->token);
        update_option( 'bearerToken', $tokens->token );   

    }

    public function check_token( $bearerToken ) {
        // set token
        // TODO: Dit moet nog maar werkt nu niet.. alles nog ontdubbelen!!
        $film_request_url = $this->url . '/shows?page=1&itemsPerPage=1';

        $filmrequest = curl_init();
        $film_headers = array(
        'Content-Type: application/json',
            "Authorization: Bearer $bearerToken",
        );

        curl_setopt_array($filmrequest, array(
            CURLOPT_URL                     =>      $film_request_url,
            CURLOPT_HTTPHEADER              =>      $film_headers,
            CURLOPT_RETURNTRANSFER          =>      true,
            CURLOPT_POST                    =>      0,
            CURLOPT_SSL_VERIFYPEER          =>      1,
            CURLOPT_PROXY                   =>      '',
            CURLOPT_TIMEOUT                 =>      30
        ));

        $response = curl_exec($filmrequest);

        $http_code = curl_getinfo($filmrequest, CURLINFO_HTTP_CODE);
        $this->http_response = $http_code;

        $this->response = $response;

        curl_close($filmrequest);

        if( 200 != $http_code ) {
            $str = sprintf( "Error retrieving token3: '%s' (%d)", (string) $response, $http_code );

            if ( $this->tokenNeedsRefresh( $response, $http_code ) ) {
                $this->getToken();
                return get_option( 'bearerToken' );
            }

            die($str);
        } else {
			return $bearerToken;
		}
		
	}


    //Dit is de functie die de shows (van vandaag en anderen) ophaalt, in de FIREBASE REACT APP, en GetFilmsSDB wordt deze functie gebruikt
    public function getShowsToday( $data, $cache=false ) {
        $bearerToken = get_option( 'bearerToken' );

        // echo 'getShowsToday, tomorrow  <br>';

            // Alleen ingeplande én gepubliceerde voorstellingen (Ticketlab).
            if ( function_exists( 'ticketlab_apply_public_show_query_params' ) ) {
                $data = ticketlab_apply_public_show_query_params( (array) $data );
            }

            // bouw de query:
            $query = http_build_query($data);
            
            // request naar de ticketlab server:
            $film_request_url = $this->url . '/shows?'.$query;

            $filmrequest = curl_init();
            $film_headers = array(
            'Content-Type: application/json',
                "Authorization: Bearer $bearerToken",
            );

            curl_setopt_array($filmrequest, array(
                CURLOPT_URL                     =>      $film_request_url,
                CURLOPT_HTTPHEADER              =>      $film_headers,
                CURLOPT_RETURNTRANSFER          =>      true,
                CURLOPT_POST                    =>      0,
                CURLOPT_SSL_VERIFYPEER          =>      1,
                CURLOPT_PROXY                   =>      '',
                CURLOPT_TIMEOUT                 =>      30
            ));

            $response = curl_exec($filmrequest);

            $http_code = curl_getinfo($filmrequest, CURLINFO_HTTP_CODE);
            $this->http_response = $http_code;

            curl_close($filmrequest);

            //resultaat verwerken:
            $obj = json_decode($response, true);
            $arr = array_filter((array)$obj['hydra:member']);

            if ( function_exists( 'ticketlab_filter_public_shows' ) ) {
                $arr = ticketlab_filter_public_shows( $arr );
            }

        return $arr;
    }



    //verouderd, wordt niet meer gebruikt?



    //Weer niet of zeze nog wordt aangeroepen, zou eigenlijk weg moeten, legacy code, deprecated
    public function getShows( $data ) {

        $filmSesieNummer = 'tickets_show_'.$data['eventid'];

        // test of deze film al eerder is opgehaald en of de tickets in een Sessie zitten:
        if(isset($_SESSION[$filmSesieNummer])) {
            
            // 'films bestaan al', zet arr gelijk aan de sessie voor return:
            $arr = $_SESSION[$filmSesieNummer];
            $arr['status'] = 'bestaat al';

        } else {

            $arr['status'] = 'films bestaat nog niet';

            if ( function_exists( 'ticketlab_apply_public_show_query_params' ) ) {
                $data = ticketlab_apply_public_show_query_params( (array) $data );
            }

            // bouw de query:
            $query = http_build_query($data);
            
            // request naar de ticketlab server:
            $film_request_url = $this->url . '/shows?'.$query;

            //token uit de session
            $token = $_SESSION["TOKEN"];

            $filmrequest = curl_init();
            $film_headers = array(
            'Content-Type: application/json',
                "Authorization: Bearer $token",
            );

            curl_setopt_array($filmrequest, array(
                CURLOPT_URL                     =>      $film_request_url,
                CURLOPT_HTTPHEADER              =>      $film_headers,
                CURLOPT_RETURNTRANSFER          =>      true,
                CURLOPT_POST                    =>      0,
                CURLOPT_SSL_VERIFYPEER          =>      1,
                CURLOPT_PROXY                   =>      '',
                CURLOPT_TIMEOUT                 =>      30
            ));

            $response = curl_exec($filmrequest);

            $http_code = curl_getinfo($filmrequest, CURLINFO_HTTP_CODE);
            $this->http_response = $http_code;

            curl_close($filmrequest);

            //resultaat verwerken:
            $obj = json_decode($response, true);
            $arr = array_filter((array)$obj['hydra:member']);

            if ( function_exists( 'ticketlab_filter_public_shows' ) ) {
                $arr = ticketlab_filter_public_shows( $arr );
            }

            // maak een sessie voor deze film:
            $_SESSION[$filmSesieNummer] = $arr;
        }

        if ( function_exists( 'ticketlab_filter_public_shows' ) ) {
            $arr = ticketlab_filter_public_shows( $arr );
        }

        return $arr;
    }


    // Dee is nu hetzelfde als hierboven dus zou eigeniljk weg moeten kunnen.
    // Zoveel mogelijk verwijdert, maar mogelijk nog aangeroepen??
    public function getShowsDay( $data, $email=true ) {

        $bearerToken = get_option( 'bearerToken' );
       

        // echo 'getShowsDay CurlImport <br>';

            if ( function_exists( 'ticketlab_apply_public_show_query_params' ) ) {
                $data = ticketlab_apply_public_show_query_params( (array) $data );
            }

            // bouw de query:
            $query = http_build_query($data);
            
            // request naar de ticketlab server:
            $film_request_url = $this->url . '/shows?'.$query;

            $filmrequest = curl_init();
            $film_headers = array(
            'Content-Type: application/json',
                "Authorization: Bearer $bearerToken",
            );

            curl_setopt_array($filmrequest, array(
                CURLOPT_URL                     =>      $film_request_url,
                CURLOPT_HTTPHEADER              =>      $film_headers,
                CURLOPT_RETURNTRANSFER          =>      true,
                CURLOPT_POST                    =>      0,
                CURLOPT_SSL_VERIFYPEER          =>      1,
                CURLOPT_PROXY                   =>      '',
                CURLOPT_TIMEOUT                 =>      30
            ));

            $response = curl_exec($filmrequest);

            // var_dump ($response);

            $http_code = curl_getinfo($filmrequest, CURLINFO_HTTP_CODE);
            $this->http_response = $http_code;

            curl_close($filmrequest);

            //resultaat verwerken:
            $obj = json_decode($response, true);
            $arr = array_filter((array)$obj['hydra:member']);

            if ( function_exists( 'ticketlab_filter_public_shows' ) ) {
                $arr = ticketlab_filter_public_shows( $arr );
            }

        return $arr;
    }

  

}