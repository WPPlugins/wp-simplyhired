<?php

// Delete DB entries. If uninstall not called from WordPress, exit.
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}	
    
delete_option( 'wpsh_admin_options' );

?>