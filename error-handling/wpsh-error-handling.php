<?php

function wpsh_error_handling ( $errno, $errstr, $errfile, $errline ) {
	
	_e ( 'Error: Invalid SimplyHired API Call' );

	return;
}

?>