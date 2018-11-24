<?php
/**
 * Uninstall
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;


$table_name = $wpdb->prefix . "design_asset";
$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );

delete_option('mmg_design_library_categories');
delete_option('mmg_design_library_filters');
delete_option('mmg_design_library_settings');


/**
 * Design Library
 * Delete Folder and Files		 
 */
$upload_dir = wp_upload_dir();
$folder_name = 'design-library';
$dl_dir = $upload_dir['basedir'] . "/{$folder_name}/";

mmg_rrmdir( $dl_dir );



/**
 * Filter Image
 * Delete Folder and Files		 
 */
$upload_dir = wp_upload_dir();
$folder_name = 'filter-image';
$dl_dir = $upload_dir['basedir'] . "/{$folder_name}/";

mmg_rrmdir( $dl_dir );


/**
 * Remove Folders
 * 
 * @param  $dir directory name
 * @return void
 */
function mmg_rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
		  if ($object != "." && $object != "..") {
		    if (filetype($dir."/".$object) == "dir") 
		       rrmdir($dir."/".$object); 
		    else unlink   ($dir."/".$object);
		  }
		}
		reset($objects);
		rmdir($dir);
	}
}