<?php

function wpsh_admin_settings() { // Set up and display admin settings

	global $wpsh_list_of_pages;
	global $wpsh_database_settings_array;
	
	_e( '<h2>WP SimplyHired</h2>' ); 

	$wpsh_api_key = $wpsh_database_settings_array['wpsh_api_key'];
	$wpsh_logo_attribution = $wpsh_database_settings_array['wpsh_logo_attribution'];
	$wpsh_display_page = $wpsh_database_settings_array['wpsh_display_page'];
	$wpsh_search_titles_only = $wpsh_database_settings_array['wpsh_search_titles_only'];
	$wpsh_default_keywords = $wpsh_database_settings_array['wpsh_default_keywords'];
	$wpsh_keywords_placeholder = $wpsh_database_settings_array['wpsh_keywords_placeholder'];
	$wpsh_default_location = $wpsh_database_settings_array['wpsh_default_location'];
	$wpsh_location_placeholder = $wpsh_database_settings_array['wpsh_location_placeholder'];
	
	if ( isset( $_POST['wpsh_hidden'] ) ) {
		if ( 'y' == $_POST['wpsh_hidden'] ) // If options were changed, save settings
		{
			$wpsh_api_key = esc_attr( $_POST['wpsh_api_key'] );
			
			// I need this since unchecked checkboxes do not get passed in forms
			if ( isset( $_POST['wpsh_logo_attribution'] ) ) {
				$wpsh_logo_attribution = esc_attr( $_POST['wpsh_logo_attribution'] );
			} else {
				$wpsh_logo_attribution = '';
			}
			
			// I need this since unchecked checkboxes do not get passed in forms
			if ( isset( $_POST['wpsh_search_titles_only'] ) ) {
				$wpsh_search_titles_only = esc_attr( $_POST['wpsh_search_titles_only'] );
			} else {
				$wpsh_search_titles_only = '';
			}
			
			$wpsh_display_page = $_POST['wpsh_display_page'];
			$wpsh_default_keywords = esc_attr( $_POST['wpsh_default_keywords'] );
			$wpsh_keywords_placeholder = esc_attr( $_POST['wpsh_keywords_placeholder'] );
			$wpsh_default_location = esc_attr( $_POST['wpsh_default_location'] );
			$wpsh_location_placeholder = esc_attr( $_POST['wpsh_location_placeholder'] );

			update_option( 'wpsh_admin_options', array(
				'wpsh_api_key' => $wpsh_api_key, 
				'wpsh_logo_attribution' => $wpsh_logo_attribution, 
				'wpsh_display_page' => $wpsh_display_page, 
				'wpsh_search_titles_only' => $wpsh_search_titles_only, 				
				'wpsh_default_keywords' => $wpsh_default_keywords,
				'wpsh_keywords_placeholder' => $wpsh_keywords_placeholder, 
				'wpsh_default_location' => $wpsh_default_location, 
				'wpsh_location_placeholder' => $wpsh_location_placeholder,
			) );
	
			$wpsh_database_settings_array = get_option( 'wpsh_admin_options' );  // update global DB variable
		
			?>
			<div class="updated"><p><strong><?php _e( 'Settings saved' ); ?></strong></p></div>
			<?php

		}
	}

	wpsh_are_api_and_attribution_correct();
	wpsh_check_for_pages(); // If no pages exist (or one is not selected), give error

	?>
	
	<!-- Display form in admin section -->
	<div class="wrap">
		<form name="wpsh_form" class="wpsh-admin-form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="wpsh_hidden" value="y" />
			
			<hr />
				
			<div><?php _e( '<h3>API & Page Settings</h3>' ); ?></div>
			
			<div class="wpsh-right-left-div-wrapper">
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_api_key"><?php _e( 'SimplyHired API Key: ' ); ?></label>
				</div>	
				<div  class="wpsh-admin-right-div">
					<input type="text" id="wpsh_api_key" name="wpsh_api_key" class="wpsh-api-key-text" value="<?php echo stripslashes($wpsh_api_key); ?>" maxlength="100" placeholder="Enter API Key" />
					<p class="description"><?php _e( "To register for a SimplyHired API key, go <a href='https://simply-partner.com/partners-signup' target='_blank'>here</a>." ); ?></p>
				</div>	
			</div>

			<div class="wpsh-right-left-div-wrapper">
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_logo_attribution"><?php _e( 'SimplyHired Attribution:' ); ?></label>
				</div>
				<div  class="wpsh-admin-right-div">
					<?php 

					if ($wpsh_logo_attribution) {
						$wpsh_attribution_checked_state = 'checked';
					} else {
						$wpsh_attribution_checked_state = '';	
					}
						
					echo "<input type='checkbox' id='wpsh_logo_attribution' name='wpsh_logo_attribution' " . $wpsh_attribution_checked_state . " >";

					?>
					<span class="description"><?php _e( "I agree to display SimplyHired's attribution as stated in section 2.3 of their <a href='https://simply-partner.com/partner-terms/1' target='_blank'>terms of service</a>." ); ?></span>
				</div>
			</div>			

			<div class="wpsh-right-left-div-wrapper">
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_display_page"><?php _e( 'Page to display jobs on:' ); ?></label>
				</div>
				<div  class="wpsh-admin-right-div">
					<?php 
				
					if ( $wpsh_list_of_pages ) { // Default is no page selected
						echo "<select id='wpsh_display_page' name='wpsh_display_page'>";
				
						if ( wpsh_default_jobs_page == $wpsh_display_page ) {
							$wpsh_selected = 'selected';
						} else {
							$wpsh_selected = '';
						}
				
						echo "<option value='" . wpsh_default_jobs_page . "' $wpsh_selected />" . wpsh_default_jobs_page . "</option>";
				
						foreach ( $wpsh_list_of_pages as $wpsh_page_list ) {
							if ( $wpsh_page_list->ID == $wpsh_display_page ) { // Set which option is selected
								$wpsh_selected = 'selected';
							} else {
								$wpsh_selected = '';
							}	
							echo "<option value='$wpsh_page_list->ID' $wpsh_selected />$wpsh_page_list->post_title</option>";
						}
						?>
						</select>
						<?php 
					} else { // No pages found, so display error msg
						echo "<p class='description'>" . wpsh_no_pages_error. "</p>";
					}	
					?>
				</div>
			</div>
			
			<hr />
				
			<div><?php _e('<h3>Job Search Settings</h3>' ); ?></div>

			<div class="wpsh-right-left-div-wrapper">
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_search_titles_only"><?php _e( 'Search job titles only:' ); ?></label>
				</div>
				<div  class="wpsh-admin-right-div">
					<?php 

					if ($wpsh_search_titles_only) {
						$wpsh_search_titles_only_checked_state = 'checked';
					} else {
						$wpsh_search_titles_only_checked_state = '';	
					}
						
					echo "<input type='checkbox' id='wpsh_search_titles_only' name='wpsh_search_titles_only' " . $wpsh_search_titles_only_checked_state . " >";

					?>
					<span class="description"><?php _e( "This will provide more accurate results, but will reduce the number of results since the job description will not be searched." ); ?></span>
				</div>
			</div>
			
			<div class="wpsh-right-left-div-wrapper">
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_default_keywords"><?php _e("Base keyword(s) query:"); ?></label>
				</div>	
				<div class="wpsh-admin-right-div">
					<input type="text" id="wpsh_default_keywords" class="wpsh-default-keywords-text" name="wpsh_default_keywords" value="<?php echo stripslashes($wpsh_default_keywords) ?>" maxlength="400" />	
					<p class="description">The base keyword(s) query is great for niche job boards that only want to display certain types of jobs, such as engineering.<br /><br />Use with caution, as this will filter job results. This will be combined with the keywords that users specify (using the <code>AND</code> operator) . For example, if you specify <code>title:sales</code> as the base query, and a user searches for the keyword <code>title:manager</code>, the results returned will have the word "sales" and "manager" in the title. Boolean searches (AND, OR, NOT) and parentheses group operators (  ) are supported. Boolean operators are <b>CASE SENSITIVE</b>. Leave blank to search all keywords. <a href="http://www.skipthedrive.com/wp-simplyhired-plugin/" target="_blank">More info</a></p><br />
					<h3>Examples</h3>
					<p><code>Software AND Web</code> returns jobs that contain <b>both</b> keywords</p>
					<p><code>Software OR Web</code> returns jobs that contain <b>at least one</b> keyword</p>
					<p><code>"Sales Manager"</code> returns jobs containing the <b>exact phrase</b></p>
					<p><code>Title:Sales</code> returns jobs where the word <i>sales</i> is <b>in the title</b></p>
					<p><code>Company:Dell</code> returns jobs for the specified company</p>
					<p><code>Sales NOT Manager</code> returns jobs that have the word <i>sales</i>, but <b>excludes jobs</b> containing the word <i>manager</i></p>
					<p><code>Title:Sales AND (Manager OR Management)</code> returns jobs that have the word <i>sales</i> in the title, and must include <b>either</b> <i>manager</i> or <i>management</i></p>
					<p><code>onet:(11-2022.00)</code> returns jobs for <i>sales managers</i>. This is <a target='_blank' href='http://www.onetonline.org/'>based on O*net</a> (the U.S. government's occupation classification system).</p>
				</div>
			</div>
			
			<div class="wpsh-right-left-div-wrapper">
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_default_location"><?php _e('Force location:'); ?></label>
				</div>	
				<div class="wpsh-admin-right-div">
					<input type="text" id="wpsh_default_location" name="wpsh_default_location" value="<?php echo stripslashes($wpsh_default_location) ?>" maxlength="100" />
					<p class="description">If this is set, this will be the only location searched and the location field will be hidden from users. Enter zip code, city, state, or city-state combination. Leave blank to allow users to search by location. <a href="http://www.skipthedrive.com/wp-simplyhired-plugin/"  target="_blank">More info</a></p>				
				</div>
			</div>
									
			<hr />				

			<div><?php _e( '<h3>Display Settings</h3>' ); ?></div>
			
			<div class="wpsh-right-left-div-wrapper">	
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_keywords_placeholder"><?php _e( 'Placeholder for keyword(s):' ); ?></label>
				</div>	
				<div class="wpsh-admin-right-div">	
					<input type="text" id="wpsh_keywords_placeholder" name="wpsh_keywords_placeholder" value="<?php echo stripslashes( $wpsh_keywords_placeholder ) ?>" maxlength="100" />
				</div>
			</div>	
			
			<div class="wpsh-right-left-div-wrapper">	
				<div class="wpsh-admin-left-div">
					<label class="description" for="wpsh_location_placeholder"><?php _e( 'Placeholder for location:' ); ?></label>
				</div>	
				<div class="wpsh-admin-right-div">	
					<input type="text" id="wpsh_location_placeholder" name="wpsh_location_placeholder" value="<?php echo stripslashes( $wpsh_location_placeholder ) ?>" maxlength="100" />
				</div>
			</div>	

			<hr />	
				
			<div class="wpsh-admin-left-div">	
				<?php submit_button(); ?>
			</div> 
					
		</form>
	</div>
	<?php
}

function wpsh_settings_menu() {

	
	add_options_page( 'WP SimplyHired', 'WP SimplyHired', 'manage_options', 'WP-SimplyHired', 'wpsh_admin_settings' );
}

function wpsh_check_for_pages() { // Check that there are published pages to choose from

	global $wpsh_list_of_pages;
	
	if ( ! $wpsh_list_of_pages ) {
		?>
		<div class="notice"><p><?php _e( 'WP-SimplyHired Notice: ' . wpsh_no_pages_error ); ?></p></div>
		<?php
	} else {
		wpsh_is_page_selected();
	}		

}

function wpsh_are_api_and_attribution_correct() {
	
	global $wpsh_database_settings_array;
	
	$wpsh_api_key = $wpsh_database_settings_array['wpsh_api_key'];
	$wpsh_logo_attribution = $wpsh_database_settings_array['wpsh_logo_attribution'];
	
	if ( ( ! $wpsh_api_key ) ) {	
		?>
		<div class="notice"><p><?php _e( wpsh_no_api_key ) ?></p></div>
		<?php
	}
	
	if ( ( ! $wpsh_logo_attribution ) ) {	
		?>
		<div class="notice"><p><?php _e( wpsh_logo_attribution_declined ) ?></p></div>
		<?php
	}


}

function wpsh_is_page_selected() {
	
	global $wpsh_database_settings_array;
	$wpsh_jobs_page_user_selected = null;
	
	$wpsh_display_page = $wpsh_database_settings_array['wpsh_display_page'];
	
	if ( isset( $_POST['wpsh_display_page'] ) ) {
		$wpsh_jobs_page_user_selected = $_POST['wpsh_display_page'];
	}
	
	if ( ( wpsh_default_jobs_page == $wpsh_display_page ) || ( wpsh_default_jobs_page == $wpsh_jobs_page_user_selected ) ) {	
		?>
		<div class="notice"><p><?php _e( wpsh_no_page_selected ) ?></p></div>
		<?php
	}

}

?>