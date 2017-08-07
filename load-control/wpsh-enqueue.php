<?php

// load stylesheet if in admin section
function wpsh_admin_style() {
	wp_enqueue_style( 'wpsh_admin_style', plugins_url( 'css/wpsh-admin-style.css', dirname( __FILE__ ) ) );	
}

// load CSS and JS if current page is the display page for jobs
function wpsh_enqueue () {

	global $wpsh_database_settings_array;
	$wpsh_default_location = $wpsh_database_settings_array['wpsh_default_location'];
	
	if ( get_the_ID() == $wpsh_database_settings_array['wpsh_display_page'] ) {
		wp_enqueue_style( 'wpsh_styling', plugins_url( 'css/wpsh-style.css', dirname( __FILE__ ) ) );	
		wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
		wp_enqueue_script( 'wpsh_localize_variables', plugins_url( 'js/wpsh-scripts.js', dirname( __FILE__ ) ) );	

		wp_localize_script( 'wpsh_localize_variables', 'wpsh_localized_scripts_var', array(
			'wpsh_default_location' => $wpsh_default_location, 
		) );
	}
}

?>