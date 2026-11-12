<?php
// require_once("config.php");
// Logging class initialization

class TicketlabCurl {

    public $url = 'https://api.ticketlab.io';

    // construct moet testen of de Token nog geldig is en anders een nieuwe token ophalen.
    public function __construct() {

        //$log = new Logging();
        $bearerToken = get_option( 'bearerToken' );

        $token = $_SESSION["TOKEN"];


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
            $str = sprintf( "Error retrieving Filmrequest: '%s' (%d)", (string) $response, $http_code );
            $message = (json_decode($response, true));

            if ($message['message'] === 'Expired JWT Token') :
                // remove all session variables
                session_unset();

                // destroy the session
                session_destroy();

                // Hij moet nu opnieuw de CURL uit proberen te voeren:
                $this->getToken();

            elseif ($message['message'] === 'JWT Token not found') :
                
                // Hij moet nu opnieuw de CURL uit proberen te voeren:
                $this->getToken();  

            else:
                // $log->lwrite('Fout connectie: , ' . $str);
                session_destroy();
                die($str);
            endif;
        }
    }

    private function getToken() {

        $request_url = 'https://auth.ticketlab.io/tokens';

        $headers = array(
        'Content-Type: application/json',
        );
        
        $username = get_field('ticketlab_username', 'option');	
        $password = get_field('ticketlab_password', 'option');	
        
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
            $str = sprintf( "Error retrieving token 2: '%s' (%d)", (string) $response, $http_code );
            die($str);
        }
        
        $tokens = json_decode($response);
        
        // setcookie("TOKEN", $tokens->token);
        $_SESSION["TOKEN"] = $tokens->token;

        // $log->lwrite('Token opgehaald: , ' . $tokens->token);
        update_option( 'bearerToken', $response );                  

    }

    //deze niet gebruikt voor REACT, maar wel voor anderen nog?
    public function getShowsToday( $data ) {

        $vandaag = 'tickets_'.$data['datumvandaag'];

        // test of deze film al eerder is opgehaald en of de tickets in een Sessie zitten:
        if(isset($_SESSION[$vandaag])) {
            
            // 'films bestaan al', zet arr gelijk aan de sessie voor return:
            $arr = $_SESSION[$vandaag];
            $arr['status'] = 'bestaat al';

        } else {

            $arr['status'] = 'films bestaat nog niet';

            if ( function_exists( 'ticketlab_apply_public_show_query_params' ) ) {
                $data = ticketlab_apply_public_show_query_params( (array) $data );
            } else {
                $data['scheduled'] = 'Y';
                $data['visible']   = 'Y';
            }

            // bouw de query:
            $query = http_build_query($data);
            
            // request naar de ticketlab server:
            $film_request_url = $this->url . '/shows?'.$query;

            //token uit de session
            $bearerToken = get_option( 'bearerToken' );
            // $token = $_SESSION["TOKEN"];

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

            // maak een sessie voor deze film:
            $_SESSION[$vandaag] = $arr;
        }

        if ( function_exists( 'ticketlab_filter_public_shows' ) ) {
            $arr = ticketlab_filter_public_shows( $arr );
        }

        return $arr;
    }
}