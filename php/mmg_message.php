<?php
/**
 * Design Library
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_dl_message( $return = '' ) {
	global $mmgDesignLibrary;
	if( is_array($mmgDesignLibrary->mmg_message ) ) {
		foreach($mmgDesignLibrary->mmg_message as $status => $messages) {
			$return .= "<div class='notice notice-{$status} is-dismissible'>";
			foreach( $messages as $message )
				$return .= "<p>{$message}</p>";        
	    	$return .= '</div>';
		}
	}
	echo $return;
}