<?php
/**
 * Plugin Name: CPT - Films
 * Plugin URI: https://github.com/indrukwekkend/
 * Description: Films — Custom post type Films met Single-page Template en meerdere blokken voor Gutenberg
 * Author: Indrukwekkend
 * Author URI: https://indrukwekkend.nl
 * Version: 0.0.8
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'src/lib/date-format.php';
require_once plugin_dir_path( __FILE__ ) . 'src/lib/ticketlab-shows.php';

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
