
// eliminate flashing of search form
document.write( '<style type="text/css">#wpsh_page_wrap{display:none}</style>' );
document.write( '<style type="text/css">.wpsh-advanced-option-content{display:none}</style>' );
jQuery( function( $ ) {
	$( '#wpsh_page_wrap' ).css( 'display','block' );
	$( '.wpsh-advanced-option-content' ).css( 'display','block' );
});

jQuery( document ).ready( function( $ ) {
	
	var wpsh_is_location_field_showing;
	
	wpsh_is_location_field_showing = wpsh_localized_scripts_var.wpsh_default_location;

	jQuery.fn.wpsh_calculate_form_display = function() {
		
		var wpsh_search_form, 
			wpsh_min_inline_field_length, 
			wpsh_div_size_collapse, 
			wpsh_numbers_and_sort_div,
			wpsh_advanced_option_content,
			wpsh_max_block_field_length;
		
		wpsh_search_form = $( '#wpsh-search-form' ).outerWidth();	
		wpsh_numbers_and_sort_div = $( '.wpsh-jobs' ).outerWidth();
		wpsh_advanced_option_content = $( '.wpsh-advanced-option-content' ).outerWidth();
		wpsh_advanced_field_inputs_width = .9 * wpsh_advanced_option_content;
		
		// Advanced field widths
		$( '#wpsh-select-job-type' ).css( 'width', wpsh_advanced_field_inputs_width );
		$( '#wpsh-select-miles' ).css( 'width', wpsh_advanced_field_inputs_width );
		$( '#wpsh-select-date-posted' ).css( 'width', wpsh_advanced_field_inputs_width );
		$( '#wpsh_advanced_form_keywords' ).css( 'width', wpsh_advanced_field_inputs_width );
		$( '#wpsh_advanced_form_location' ).css( 'width', wpsh_advanced_field_inputs_width );
		
		if 	( '' == wpsh_is_location_field_showing ) {	
			wpsh_min_inline_field_length = Math.ceil( .35 * wpsh_search_form );
			wpsh_div_size_collapse = 500;
		} else {
			wpsh_min_inline_field_length = Math.ceil( .5 * wpsh_search_form );
			wpsh_div_size_collapse = 400;
		}
		
		wpsh_max_block_field_length = ( .6 * wpsh_div_size_collapse );
		
		if ( wpsh_search_form >= wpsh_div_size_collapse ) {
			$( '.wpsh-form-field' ).css( 'display', 'inline-block' );
			$( '#wpsh-search-form' ).css( 'text-align', 'center' );
			$( '.wpsh-form-field' ).css( 'margin-right', '2px' );
			$( '.wpsh-form-field' ).css( 'margin-left', '2px' );			
			$( '#wpsh-keywords' ).css( 'width', wpsh_min_inline_field_length );
			$( '#wpsh-location' ).css( 'width', wpsh_min_inline_field_length );	
			$( '#wpsh-submit-form' ).css( 'width', 'initial' );	
			$( '#wpsh-submit-form' ).css( 'padding-right', '15px' );
			$( '#wpsh-submit-form' ).css( 'padding-left', '15px' );
			$( '#wpsh-submit-form' ).css( 'vertical-align', 'top' );				
			$( '.wpsh-form-field' ).css( 'vertical-align', 'top' );	
			$( '#wpsh-keywords' ).css( 'max-width', '400px' );
			$( '#wpsh-location' ).css( 'max-width', '400px' );		
			$( '.wpsh-numbers-and-sort-div' ).css( 'max-width', '700px' );
			$( '.wpsh-numbers-and-sort-div' ).css( 'width', wpsh_numbers_and_sort_div );	
										
		} else {		
			$( '.wpsh-form-field' ).css( 'display', 'block' );				
			$( '.wpsh-form-field' ).css( 'margin-top', '5px' );
			$( '.wpsh-form-field' ).css( 'margin-bottom', '5px' );
			$( '#wpsh-keywords' ).css( 'max-width', ( .9 * wpsh_numbers_and_sort_div) );
			$( '#wpsh-location' ).css( 'max-width', ( .9 * wpsh_numbers_and_sort_div) );
			$( '#wpsh-keywords' ).css( 'width', ( .9 * wpsh_numbers_and_sort_div) );
			$( '#wpsh-location' ).css( 'width', ( .9 * wpsh_numbers_and_sort_div) );
			$( '#wpsh-submit-form' ).css( 'width', ( .9 * wpsh_numbers_and_sort_div) );
			$( '.wpsh-numbers-and-sort-div' ).css( 'width', wpsh_numbers_and_sort_div );
		} 
	};
	
	// Calculate form size upon resizing
	jQuery( window ).resize( function( $ ) {
		jQuery.fn.wpsh_calculate_form_display();
	});
	
	// Calculate form size upon page load
	jQuery.fn.wpsh_calculate_form_display();
	
});	