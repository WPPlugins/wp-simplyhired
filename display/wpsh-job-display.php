<?php

include( 'wpsh-functions.php' );

function wpsh_display_advanced_options ( $wpsh_default_location, $wpsh_keywords_placeholder, $wpsh_location_placeholder, $wpsh_page_to_display_jobs, $wpsh_permalink_structure ) {
	
	$wpsh_select_miles = null;
	$wpsh_select_job_type = null;
	$wpsh_select_date_posted = null;
	
	$wpsh_advanced_form = "<div class='wpsh-advanced-option-content'>";
	$wpsh_advanced_form .= "<h4>Advanced Search</h4>";
	$wpsh_advanced_form .= "<form method='get' action=" . get_permalink() . "  name='wpsh_advanced_search_form' id='wpsh-advanced-search-form-display' />";
	$wpsh_advanced_form .= "<div class='wpsh-advanced-dropdown'><input type='text' placeholder='" . $wpsh_keywords_placeholder . "' name='search' id='wpsh_advanced_form_keywords' /></div>";
	if ( ! $wpsh_permalink_structure ) {
		$wpsh_advanced_form .= "<input type='hidden' name='page_id' value=" . get_the_ID(). " />";
	}
	
	if ( ! $wpsh_default_location ) {
		$wpsh_advanced_form .= "<div class='wpsh-advanced-dropdown'><input type='text' placeholder='" . $wpsh_location_placeholder . "' name='job_location' id='wpsh_advanced_form_location' /></div>";
		$wpsh_advanced_form .= "<div class='wpsh-advanced-dropdown'>";
		$wpsh_advanced_form .= "<select name='miles' id='wpsh-select-miles'>";	
		$wpsh_advanced_form .= "<option value=''  "  . selected( $wpsh_select_miles, '', false ) . " >Any Distance</option>";
		$wpsh_advanced_form .= "<option value='5' "  . selected( $wpsh_select_miles, '5', false ) . ">Within 5 miles</option>";
		$wpsh_advanced_form .= "<option value='10' " . selected( $wpsh_select_miles, '10', false ) . ">Within 10 miles</option>";	
		$wpsh_advanced_form .= "<option value='25' " . selected( $wpsh_select_miles, '25', false ) . ">Within 25 miles</option>";	
		$wpsh_advanced_form .= "<option value='50' " . selected( $wpsh_select_miles, '50', false ) . ">Within 50 miles</option>";
		$wpsh_advanced_form .= "<option value='100' " . selected( $wpsh_select_miles, '100', false ) . ">Within 100 miles</option>";
		$wpsh_advanced_form .= "</select></div>";
	}
	
	$wpsh_advanced_form .= "<div class='wpsh-advanced-dropdown'>";
	$wpsh_advanced_form .= "<select name='type' id='wpsh-select-job-type'>";
	$wpsh_advanced_form .= "<option value='' "  . selected( $wpsh_select_job_type, '', false  ) . ">Any Type</option>";		
	$wpsh_advanced_form .= "<option value='full-time' "  . selected( $wpsh_select_job_type, 'full-time', false  ) . ">Full Time</option>";
	$wpsh_advanced_form .= "<option value='part-time' "  . selected( $wpsh_select_job_type, 'part-time', false  ) . ">Part Time</option>";
	$wpsh_advanced_form .= "<option value='contract' "  . selected( $wpsh_select_job_type, 'contract', false  ) . ">Contract</option>";	
	$wpsh_advanced_form .= "</select></div>";	

	$wpsh_advanced_form .= "<div class='wpsh-advanced-dropdown'>";
	$wpsh_advanced_form .= "<select name='days' id='wpsh-select-date-posted'>";
	$wpsh_advanced_form .= "<option value='' "  . selected( $wpsh_select_date_posted, '', false  ) . ">Any Date</option>";
	$wpsh_advanced_form .= "<option value='1'  "  . selected( $wpsh_select_date_posted, '1', false  ) . ">Since yesterday</option>";
	$wpsh_advanced_form .= "<option value='7'  "  . selected( $wpsh_select_date_posted, '7', false  ) . ">Within 7 days</option>";
	$wpsh_advanced_form .= "<option value='14'  "  . selected( $wpsh_select_date_posted, '14', false  ) . ">Within 14 days</option>";
	$wpsh_advanced_form .= "<option value='30'  "  . selected( $wpsh_select_date_posted, '30', false  ) . ">Within 30 days</option>";	
	$wpsh_advanced_form .= "</select></div>";	
	
	$wpsh_advanced_form .= "<div class='wpsh-advanced-form-field'><input id='wpsh-advanced-submit-form' type='submit' name='findjobs' value='Search' /></div>";
	
	$wpsh_advanced_form .= "</form>";
	$wpsh_advanced_form .= "</div>";	
	
	return $wpsh_advanced_form;
}

function wpsh_job_display ( $content ) {

	global $wpsh_database_settings_array; 

	// Initializing variables
	$wpsh_keywords = null;
	$wpsh_location = null;
	$wpsh_sort_order = null;
	$wpsh_keywords_for_display = null;
	$wpsh_location_for_display = null;
	$wpsh_pagination_display = null;
	$wpsh_select_miles = null;
	$wpsh_select_job_type = null;
	$wpsh_select_date_posted = null;

	$wpsh_logo_attribution = $wpsh_database_settings_array['wpsh_logo_attribution'];
	$wpsh_page_to_display_jobs = $wpsh_database_settings_array['wpsh_display_page'];
	$wpsh_default_location = $wpsh_database_settings_array['wpsh_default_location'];
	$wpsh_keywords_placeholder = stripslashes( $wpsh_database_settings_array['wpsh_keywords_placeholder'] );
	$wpsh_location_placeholder = stripslashes( $wpsh_database_settings_array['wpsh_location_placeholder'] );
	$wpsh_permalink_structure = wpsh_url_structure();
		
	if ( $wpsh_page_to_display_jobs !=  get_the_ID() ) { // If not correct page, leave.
		return $content;	
	}
	
	if ( ! $wpsh_logo_attribution ) {
		return $content;
	}
	
	if ( isset( $_GET['advanced'] ) ) {
		if ( 'advanced' == strtolower( $_GET['advanced'] ) ) {
			$content = wpsh_display_advanced_options( $wpsh_default_location, $wpsh_keywords_placeholder, $wpsh_location_placeholder, $wpsh_page_to_display_jobs, $wpsh_permalink_structure );
			return $content;
		}
	}
	
	if ( isset( $_GET['miles'] ) ) {
		$wpsh_select_miles = $_GET['miles'];
	}
		
	if ( isset( $_GET['type'] ) ) {	
		$wpsh_select_job_type = $_GET['type'];
	}
	
	if ( isset( $_GET['days'] ) ) {		
		$wpsh_select_date_posted = $_GET['days'];
	}
	
	if ( isset( $_GET['sort'] ) ) {		
		$wpsh_sort_order = $_GET['sort'];
	}
		
	$wpsh_api_key = $wpsh_database_settings_array['wpsh_api_key'];
	$wpsh_pshid = wpsh_retrieve_pshid( $wpsh_api_key ); 
	
	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {		
		$wpsh_ip = $_SERVER['REMOTE_ADDR'];
	}

	if (isset( $_GET['search'] ) ) {
		if (isset( $_GET['search'] ) && ( '' != $_GET['search'] )  ) {
			$wpsh_keywords1 = urlencode( ( $_GET['search'] ) );
			$wpsh_keywords = urlencode( stripslashes( $_GET['search'] ) );
			$wpsh_keywords_for_display = stripslashes( urldecode( $wpsh_keywords1 ) );
		}
	}

	// If only searching title
	if ( '' != $wpsh_database_settings_array['wpsh_search_titles_only'] && ( $wpsh_keywords ) ) {
		$wpsh_keywords = 'title:(' . $wpsh_keywords . ')';
	}
		
	// If user specifies keywords, append to base query
	if ( ( '' != $wpsh_database_settings_array['wpsh_default_keywords'] ) ) {
		if ( $wpsh_keywords ) {
			$wpsh_keywords .= "+AND+(" . urlencode( html_entity_decode( stripslashes( $wpsh_database_settings_array['wpsh_default_keywords'] ) ) ) . ")";
		} else {
			$wpsh_keywords = urlencode( html_entity_decode( stripslashes( $wpsh_database_settings_array['wpsh_default_keywords'] ) ) );
		}	
	} 
	
	// Location
	if ( ( ! isset($_GET['job_location']) ) || ( '' == ($_GET['job_location'] ) ) ) {
		$wpsh_location = urlencode(html_entity_decode(stripslashes($wpsh_database_settings_array['wpsh_default_location'])));
	} else {
		$wpsh_location1 = urlencode( $_GET['job_location'] );
		$wpsh_location = urlencode( stripslashes( $_GET['job_location'] ) );
		$wpsh_location_for_display = stripslashes( urldecode( $wpsh_location1 ) );
	}
		
	$wpsh_page = wpsh_get_page_number();
	
	if ( wp_is_mobile() ) {
		$wpsh_ssty = 5;
	} else {
		$wpsh_ssty = 2;
	}	

	$wpsh_feedurl = "http://api.simplyhired.com/partner/api/xml/3/q-$wpsh_keywords/l-$wpsh_location/mi-$wpsh_select_miles/fjt-$wpsh_select_job_type/fdb-$wpsh_select_date_posted/pn-$wpsh_page/sb-$wpsh_sort_order/?pshid=$wpsh_pshid&ssty=$wpsh_ssty&cflg=r&auth=$wpsh_api_key&clip=$wpsh_ip";

	$wpsh_xml = wpsh_get_feed_contents( $wpsh_feedurl );

	if ( ! $wpsh_xml ) { // If unable to connect to simplyhired, exit
		_e( 'Unable to connect' );
		return $content;	
	}
	
	if ( $wpsh_xml->error ) {
		if ( $wpsh_xml->error->attributes()->type == 'invalidparam' ) { // If Bad API key, exit
			_e( 'Invalid SimplyHired API Key' );
			return $content;	
		} 
	}
	
	// Using page wrap to hide the flashing.
	$content .= "<div id='wpsh_page_wrap'>";
	$content .= "<div id='wpsh-search-form'>";
	$content .= "<form method='get' action=" . get_permalink() . "  name='wpsh_search_form' id='wpsh-search-form-display' >";
	
	if ( $wpsh_permalink_structure ) { // If permalink, use '?', otherwise use '&' for parameters on 'Advanced' link.
		$wpsh_query_param = '?';
	} else {
		$content .= "<input type='hidden' name='page_id' value=" . get_the_ID() . " />";
		$wpsh_query_param = '&';
	}
	
	$content .= "<div class='wpsh-form-field'><input type='text' name='search' placeholder='" . $wpsh_keywords_placeholder . "' value='" . $wpsh_keywords_for_display . "' id='wpsh-keywords' /></div>";
	
	if ( '' == $wpsh_database_settings_array['wpsh_default_location'] ) {
		$content .= "<div class='wpsh-form-field'><input type='text' name='job_location'  placeholder='" . $wpsh_location_placeholder . "' value='" . $wpsh_location_for_display . "' id='wpsh-location' /></div>";
	}
		
	$content .= "<div class='wpsh-form-field'><input id='wpsh-submit-form' type='submit' name='findjobs' value='Search' /><div><div id='wpsh-advanced-link'><a href='" . get_permalink() . $wpsh_query_param . "advanced=advanced'>Advanced</a></div></div></div>";
	$content .= "</form>";
	$content .= "</div>";
	$content .= "<div class='wpsh-jobs'>";

	if ( $wpsh_xml->rs->r ) { // If results exist from query
	
		$content .= "<div class='wpsh-numbers-and-sort-div'>";
		
		// Do not display job count if location and keywords are empty
		if( ( $wpsh_location_for_display ) || ( $wpsh_keywords_for_display  )  ) {
			$content .= "<span class='wpsh-display-numbers'>" . wpsh_display_number_of_results( $wpsh_xml ) . "</span>";
		}
	
		$wpsh_sort_query_string = wpsh_sort_query_string ( $wpsh_permalink_structure );

		if ( 'dd' == strtolower( $wpsh_sort_order) ) {
			$content .= "<span class='wpsh-sort-links'><span class='wpsh-relevance-margin'><a href='" . $wpsh_sort_query_string ."&sort=rd'><span class='fa fa-list-ul'></span> Relevance</a></span><span class='fa fa-calendar'></span> Date</span>";
		} else {
			$content .= "<span class='wpsh-sort-links'><span class='wpsh-relevance-margin'><span class='fa fa-list-ul'></span> Relevance</span><a href='" . $wpsh_sort_query_string ."&sort=dd'><span class='fa fa-calendar'></span> Date</a></span>";
		}
	
		$content .= "</div>";

		foreach( $wpsh_xml->rs->r as $wpsh_r ) { // Iterate through all jobs
			$wpsh_date_posted = date( 'n/d/Y', strtotime( $wpsh_r->dp ) );
			$content .= "<div class='wpsh-listing'>";
			$wpsh_url = $wpsh_r->src->attributes()->url;	
			$content .= "<span class='wpsh-jobtitle'><a rel='nofollow' target='_blank' href='$wpsh_url' onMouseDown='xml_sclk(this);'>" . $wpsh_r->jt . "</a></span>";
			$content .= "<p><span class='wpsh-company'>" . $wpsh_r->cn . " - </span><span class='wpsh-location'>" . $wpsh_r->loc . "</span></p>";
			$content .= "<p class='wpsh-description'>" . $wpsh_r->e . "</p>";
			$content .= "<p class='wpsh-time-posted'><span class='fa fa-calendar'></span> " . $wpsh_date_posted . "</p>";
			$content .= "<hr />";
			$content .= "</div>";
		}
		$content .= "<div class='wpsh-pagination'>";
		$content .= wpsh_pagination( $wpsh_xml );	
		$content .= "</div>";		
			
		/* Attribution below is required by SimplyHired and is part of their terms of service.
		See section 2.3 - https://simply-partner.com/partner-terms/1    */
		$content .= "<div class='wpsh-attribution'><br /><a style='text-decoration:none' href='http://www.simplyhired.com/' rel='nofollow'><img src='http://www.simply-partner.com/static/_/img/sh-logo-wide.png' alt='jobs by simply hired' title='simply hired job feed'></a></div>";
	} else  { // no results from query. Return 404 to avoid soft 404s.  
		header("HTTP/1.0 404 Not Found");
		$content .= '<h3>No results found</h3>';
	}

	$content .= "<script type='text/javascript' src='http://api.simplyhired.com/c/jobs-api/js/xml-v2.js'></script>";
	$content .= "</div>";
	$content .= "</div>";

	return $content;
}

?>