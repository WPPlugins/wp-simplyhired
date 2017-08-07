<?php
/*
Plugin Name: WP SimplyHired
Plugin URI: http://www.skipthedrive.com/wp-simplyhired-plugin/
Description: Connect to the SimplyHired API and display jobs on your site.
Version: 1.1
Author: Pete Metz
Author URI: http://www.skipthedrive.com
License: GPL2
License URL: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'No scripts please' );

/******************
* Globals and constants
*******************/

define( 'wpsh_no_pages_error', 'You have no pages created. You must create a page to display your jobs on.' );
define( 'wpsh_no_page_selected', 'You must select a page to display jobs on.' );
define( 'wpsh_no_api_key', 'You must enter a valid SimplyHired API key.' );
define( 'wpsh_logo_attribution_declined', 'You must agree to display SimplyHired attribution on your jobs page.' );
define( 'wpsh_default_jobs_page', '--- Select Page ---' );

$wpsh_list_of_pages = get_pages(); // Get list of all pubished pages
$wpsh_database_settings_array = get_option( 'wpsh_admin_options' ); // All plugin DB settings

/******************
* Files to include
*******************/

include( 'admin/wpsh-admin.php' );
include( 'display/wpsh-job-display.php' );
include( 'load-control/wpsh-enqueue.php' );
include( 'error-handling/wpsh-error-handling.php' );

/******************
* Hooks
*******************/

add_action( 'admin_menu', 'wpsh_settings_menu' ); // Create admin settings and form
add_filter( 'the_content', 'wpsh_job_display' ); // Display jobs
add_action( 'wp_enqueue_scripts', 'wpsh_enqueue', 9999 ); // High priority to ensure loading after theme
add_action( 'admin_enqueue_scripts', 'wpsh_admin_style' ); //Style for admin section

/******************
* Initialize DB
*******************/

register_activation_hook( __FILE__, 'wpsh_set_default_db_values' );

function wpsh_set_default_db_values () {

	global $wpsh_database_settings_array;
	
	if ( ! $wpsh_database_settings_array ) { // If the option isn't present, set defaults
		$wpsh_db_array = array(
			'wpsh_api_key' => '',
			'wpsh_logo_attribution' => false,
			'wpsh_display_page' => wpsh_default_jobs_page,
			'wpsh_search_titles_only' => false,			
			'wpsh_default_keywords' => '',
			'wpsh_keywords_placeholder' => 'Enter keyword(s)',
			'wpsh_default_location' => '',
			'wpsh_location_placeholder' => 'Enter location',
		);
		add_option( 'wpsh_admin_options', $wpsh_db_array );
	}
}

?>