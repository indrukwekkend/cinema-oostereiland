<?php
/**
 * Ticketlab show helpers.
 *
 * Ticketlab: only shows that are both scheduled AND published (visible)
 * should appear on the website.
 *
 * @see https://api.ticketlab.io/docs
 */

if ( ! function_exists( 'ticketlab_is_public_show' ) ) {
	/**
	 * Whether a show may be shown on the website.
	 *
	 * @param array|object $show Show from Ticketlab API or cached option.
	 */
	function ticketlab_is_public_show( $show ): bool {
		if ( is_object( $show ) ) {
			$show = (array) $show;
		}

		if ( ! is_array( $show ) ) {
			return false;
		}

		// Skip non-show keys such as 'status' in cached arrays.
		if ( ! isset( $show['@id'] ) && ! isset( $show['datetime'] ) && ! isset( $show['eventid'] ) ) {
			return false;
		}

		$scheduled = isset( $show['scheduled'] ) ? strtoupper( (string) $show['scheduled'] ) : '';
		$visible   = isset( $show['visible'] ) ? strtoupper( (string) $show['visible'] ) : '';

		// If fields are missing (very old cache), keep the show to avoid hiding everything
		// until the next sync has run with the new API filters.
		if ( $scheduled === '' && $visible === '' ) {
			return true;
		}

		return $scheduled === 'Y' && $visible === 'Y';
	}
}

if ( ! function_exists( 'ticketlab_filter_public_shows' ) ) {
	/**
	 * Filter a list of shows to scheduled + visible only.
	 * Preserves a possible 'status' key used by some callers.
	 *
	 * @param array $shows
	 * @return array
	 */
	function ticketlab_filter_public_shows( $shows ) {
		if ( ! is_array( $shows ) || empty( $shows ) ) {
			return is_array( $shows ) ? $shows : array();
		}

		$status = null;
		if ( array_key_exists( 'status', $shows ) ) {
			$status = $shows['status'];
			unset( $shows['status'] );
		}

		$filtered = array_values(
			array_filter(
				$shows,
				static function ( $show ) {
					return ticketlab_is_public_show( $show );
				}
			)
		);

		if ( null !== $status ) {
			$filtered['status'] = $status;
		}

		return $filtered;
	}
}

if ( ! function_exists( 'ticketlab_apply_public_show_query_params' ) ) {
	/**
	 * Ensure API query requests only scheduled + published shows.
	 *
	 * @param array $data Query params for GET /shows
	 * @return array
	 */
	function ticketlab_apply_public_show_query_params( array $data ): array {
		$data['scheduled'] = 'Y';
		$data['visible']   = 'Y';
		return $data;
	}
}
