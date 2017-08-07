<?php

function wpsh_sort_query_string ( $wpsh_permalink_structure ) {

	$wpsh_sort_query_string = get_permalink();

	if ( ! $wpsh_permalink_structure ) {
		$wpsh_sort_query_string .= '&';
	} else {
		$wpsh_sort_query_string .= '?';
	} 	
		
	if ( isset( $_GET['search'] ) ) {
		$wpsh_sort_query_string .= '&search=' . urlencode( stripslashes( $_GET['search'] ) );;
	}
	
	if ( isset( $_GET['job_location'] ) ) {
		$wpsh_sort_query_string .= '&job_location=' . urlencode( stripslashes( $_GET['job_location'] ) );;
	}
	
	if ( isset( $_GET['miles'] ) ) {
		$wpsh_sort_query_string .= '&miles=' . $_GET['miles'] ;
	}
	
	if ( isset( $_GET['type'] ) ) {
		$wpsh_sort_query_string .= '&type=' . $_GET['type'];		
	}
	
	if ( isset( $_GET['days'] ) ) {
		$wpsh_sort_query_string .= '&days=' . $_GET['days'];		
	}

	// Clean up query string
	$wpsh_sort_query_string = str_replace('?&', '?', $wpsh_sort_query_string);
	$wpsh_sort_query_string = str_replace('&&', '&', $wpsh_sort_query_string);

	return $wpsh_sort_query_string;
}

function wpsh_display_number_of_results ( $wpsh_xml_page_data ) {
	
	$wpsh_display_numbers = null;
	$wpsh_si = (int) $wpsh_xml_page_data->rq->si; // Start Index
	$wpsh_firstjob = $wpsh_si + 1;// First job # for each page
	$wpsh_tv = (int) $wpsh_xml_page_data->rq->tv; // Total # of jobs returned for query
	$wpsh_tpages = ceil( $wpsh_tv/10 ); // Total # of pages 
	
	$wpsh_current_page = wpsh_get_page_number();
	
	if ( $wpsh_tpages == $wpsh_current_page ) {
		$wpsh_last_job = $wpsh_tv;
	} else {
		$wpsh_last_job = $wpsh_current_page * 10;
	}
	
	if ( 1  < $wpsh_tv) {
		$wpsh_display_numbers .= "<strong>" . $wpsh_firstjob . " - " . $wpsh_last_job . "</strong> of <strong>" . $wpsh_tv . "</strong> jobs";
	} elseif ( 1 == $wpsh_tv ) {
		$wpsh_display_numbers .= "One result";	
	}

	return $wpsh_display_numbers;
}

function wpsh_retrieve_pshid ( $wpsh_api_key ) { // Get the pshid from the API key
	
	if ( $wpsh_api_key ) {
		$wpsh_api_array = ( explode( ".", $wpsh_api_key ) );
		
		if ( array_key_exists( 1, $wpsh_api_array ) ) {
			return $wpsh_api_array[1];
		}
		
	}	
}

function wpsh_get_feed_contents( $wpsh_url_to_get_contents ) {

	set_error_handler( 'wpsh_error_handling' ); // catch warning 
	$wpsh_xml_contents = null;
	$wpsh_curl = curl_init(); // Use CURL to retrieve API data
	curl_setopt( $wpsh_curl, CURLOPT_URL, $wpsh_url_to_get_contents );
	curl_setopt( $wpsh_curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $wpsh_curl, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $wpsh_curl, CURLOPT_FAILONERROR, true) ;
	$wpsh_curl_response = curl_exec( $wpsh_curl );
	curl_close( $wpsh_curl );
	try {
		$wpsh_xml_contents = new SimpleXMLElement( $wpsh_curl_response ); 
	} catch ( Exception $e ) {
		echo "<b>Exception:</b>  Unable to connect to SimplyHired. ", $e->getMessage(), ".\n\n";
	}
	restore_error_handler(); // restore PHPs warnings
	
	return $wpsh_xml_contents;
}

function wpsh_pagination( $wpsh_xml_page_data ) {	

	$wpsh_pagination_display = null;
	$wpsh_tv = (int) $wpsh_xml_page_data->rq->tv; // Total # of jobs returned for query
	$wpsh_tpages = ceil( $wpsh_tv/10 ); // Total # of pages 

	$wpsh_pagination_display .= paginate_links(
	array(
		'total' => $wpsh_tpages,
	) );

	return $wpsh_pagination_display;
}

function wpsh_get_page_number() {

	if ( get_query_var( 'paged' ) ) {
		$wpsh_page_number = ( get_query_var( 'paged' ) );
	} elseif ( get_query_var( 'page' ) ) {
		$wpsh_page_number = ( get_query_var( 'page' ) );
	} else {
		$wpsh_page_number = 1;
	}	
	return $wpsh_page_number;

}

function wpsh_url_structure () {

	if ( get_option( 'permalink_structure' ) ) {
		$wpsh_permalink_set = true;
	} else {
		$wpsh_permalink_set = false;
	}
	
	return $wpsh_permalink_set;
	
}

?>