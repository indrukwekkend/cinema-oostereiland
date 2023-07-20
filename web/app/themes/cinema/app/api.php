<?php
namespace App;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use WP_Query;
use WP_Post;
use stdClass;


define( 'INDRUKWEKKEND_VACATURES_NAMESPACE', 'filmlijst/v1' );
new FilmsApi();

class FilmsApi {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize.
	 */
	private function init() {
		/**
		 * Register Rest Api Endpoints Routes.
		 */
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}


	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes(): void {


		/**
		 * Register filmlijst api endpoint.
		 * e.g. https://cinemabedrock.local/wp-json/filmlijst/v1/inhoorn?post_type=films
		 */
		register_rest_route(
			INDRUKWEKKEND_VACATURES_NAMESPACE,
			'/inhoorn',
			[
				'methods'             => ['GET'],
				'callback'            => [ $this, 'get_items' ],
				'permission_callback' => '__return_true',
				'status'              => 'publish',
				'args'                => [
					'post_type' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Search Query', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
          'page_no' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Page no', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'posts_per_page' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Posts per page', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
          'token' => [
						'required'          => false,
						'type'              => 'string',
					],
				],
			]
		);
		
	}

	/**
	 * Get the items for vacature choices.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function get_items( WP_REST_Request $request ): WP_REST_Response {
		
		$post_type			= $request->get_param( 'post_type' );
		$post_in			= $request->get_param( 'post_in' );
		// overige taxonomies

		$page_no        = $request->get_param( 'page_no' );
		$posts_per_page = $request->get_param( 'posts_per_page' );
		$search_query   = [
			'post_type'              => $post_type ? $post_type : 'post',
			'posts_per_page'         => $posts_per_page ? intval( $posts_per_page ) : -1,
			'post_status'            => 'publish',
			'paged'                  => $page_no ? intval( $page_no ) : 1,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query' => [],
      'meta_query' => array(
        array(
            'key'   => 'actief',
            'value' => '1',
        )
      )
		];


		$results = new WP_Query( $search_query );

		$response = $this->build_response( $results );

		return rest_ensure_response( $response );
	}


	/**
	 * Build the response data for choices list.
	 *
	 * @param object $results List of choices.
	 *
	 * @return stdClass
	 * @since 1.0.0
	 * 
	 */
	private function build_response( object $results ): stdClass {
		$the_posts = [];

		if ( ! empty( $results->posts ) && is_array( $results->posts ) ) {
			foreach ( $results->posts as $the_post ) {
				if ( ! $the_post instanceof WP_Post || empty( $the_post ) ) {
					continue;
				}
				$meta=[];

				$id = $the_post->ID;
        // voor de content geldt tegenwoordig natuurlijk dat je blokken krijgt. Je kunt die dus niet makkelijk gebruiken om een soort excerpt te genereren. 
        $content = $the_post->post_content;
        //strip alle html tags
        $content = strip_tags($content);
        $content = trim(preg_replace('/\s\s+/', ' ', $content));


        //
        //images
        //  -->
          $thumbnail = get_field( 'header_img', $id );
          $thumb = null;
          $thumbnail1 = get_field( 'alternatieve_afbeelding', $id );
          $thumb1 = null;

          $size = 'large';
          if ( $thumbnail ) { 
            $url = $thumbnail['url'];
            // Thumbnail size attributes.
            $thumb = esc_url($thumbnail['sizes'][ $size ]);
          }
          $size = 'filmsSliderStanding';
          if ( $thumbnail1 ) { 
            $url = $thumbnail1['url'];
            // Thumbnail size attributes.
            $thumb1 = esc_url($thumbnail1['sizes'][ $size ]);
          }
        // <--
        //end images
				
				//Content kijk hiernaar... 
				$post_excerpt = get_the_excerpt($id);
        $excerpt = esc_html( wp_trim_words( $post_excerpt, $excerpt_length=40, ' &hellip; ' ) );
				///

        // Ophalen van de voorstellingen:
        $film_nummer = get_field( "ticketlab_id", $id );
        $duur = get_post_meta( $id, 'duur', true );
        $events = array("events", "/");
        $filmnummer = str_replace($events, "",  $film_nummer );
        // //kijk eerst in de database of de film tickets heeft:
        $refTable = 'tickets_'.$filmnummer;
        // haal alle shows op die bij deze film horen:
        $shows = get_option( $refTable );

        // bewerk de Array shows zodat deze geschikt is voor de API:
        if ($shows) {
           // change the show eventid to the url of the event:
           $i = 0;
          foreach($shows as $key => $show) {

            // remove the @id and @type from the array:
            unset($shows[$i]['@id']);
            unset($shows[$i]['@type']);
            unset($shows[$i]['creationdate']);
            unset($shows[$i]['scheduled']);
            unset($shows[$i]['pauselength']);
            unset($shows[$i]['locationid']);
            unset($shows[$i]['leaderlength']);
            unset($shows[$i]['seating']);
            unset($shows[$i]['eventid']);

            $events = array("events", "/");
            $eventnummer = str_replace($events, "",  $show['eventid'] );
            $shows[$key]['eventid'] = $eventnummer;

            $location = array("locations", "/");
            $zaalnummer = str_replace( $location, "", $show['locationid'] );
            $zaalnummer = intval($zaalnummer) - 1;
            $shows[$key]['zaalnummer'] = $zaalnummer;
            
            $i++;
          }
        }

        // genereer de output:
				$the_posts[] = [
					'id'        => $id,
					'title'     => $the_post->post_title,
          'duur'     => $duur,
					'post_status'   => 'actief',
          'samenvatting'     => $excerpt,
          'content'     => $content,
					'permalink' => get_the_permalink( $the_post ),
					'thumbnail' => $thumb,
          'filmposter' => $thumb1,
          'voorstellingen' => $shows,
					
				];
			}
		}

		// Get total number of pages.
		$total_pages = $this->calculate_page_count(
			$results->found_posts ?? 0,
			$results->query['posts_per_page'] ?? 0
		);

		// Return the formatted result.
		return (object) [
			'films'          => $the_posts,
			'posts_per_page' => $results->query['posts_per_page'],
			'tax_query'     => $results->query['tax_query'],
			'total_posts'    => $results->found_posts,
			'no_of_pages'    => $total_pages,
			'current_page'   => $results->query['paged'],
		];
	}


	/**
	 * Calculate page count.
	 *
	 * @param int $total_found_posts Total posts found.
	 * @param int $post_per_page     Post per page count.
	 *
	 * @return int
	 */
	public function calculate_page_count( int $total_found_posts, int $post_per_page ): int {
		if ( empty( $total_found_posts ) || empty( $post_per_page ) ) {
			return 0;
		}
		return ( (int) ( $total_found_posts / $post_per_page ) + ( ( $total_found_posts % $post_per_page ) ? 1 : 0 ) );
	}

	/** 
	 * Check permissions for the request.
	 */
	public function check_permissions() {

		//hier nog verschillende functies maken voor verschillende situaties met een input parameter
			return current_user_can("edit_posts");
			// return true;
	}
}
?>
