<?php
/**
 * Design Library Save
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_save( $upload = false, $file_ok = true ) {	
	if( isset( $_POST['dl-add'] ) ) {		
		global $wpdb, $mmgDesignLibrary;


		$code = $_POST['dl-code'];
		$name = sanitize_text_field( $_POST['dl-name'] );	
		$folder_name = strtolower( str_replace(array( '&', ' ' ), '-', $name) );
		$layers = $_POST['dl-layers'];
		$filters = $_POST['dl-filters'];

		if( empty($name) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "<strong>Name</strong> must not be empty.";	
			$file_ok = false;		
		}


		/**
		 * Create "design-library" directory		 
		 */
		$upload_dir = wp_upload_dir();

		$dl_url = $upload_dir['baseurl'] . "/design-library/{$folder_name}/";
		$dl_dir = $upload_dir['basedir'] . "/design-library/{$folder_name}/";
		if( ! file_exists($dl_dir) )
			mkdir( $dl_dir, 755, true );
		


		/**
		 * Main File
		 * @var string
		 */
		$main_file_tmp = $_FILES["dl-main-file"]["tmp_name"];
		$main_file = basename($_FILES["dl-main-file"]["name"]);

		if( empty($main_file) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "<strong>Main file</strong> should not be empty.";
			$file_ok = false;
		}



		/**
		 * Main File
		 * @var string
		 */
		$mask_file_tmp = $_FILES["dl-mask-file"]["tmp_name"];
		$mask_file = basename($_FILES["dl-mask-file"]["name"]);

		if( empty($mask_file) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "<strong>Mask file</strong> should not be empty.";
			$file_ok = false;
		}


		$upload = mmg_image_exists($dl_dir . $main_file);			
		if( $file_ok && $upload === true ) {
			$mmgDesignLibrary->mmg_message['error'][] = "Sorry, <strong>{$main_file}</strong> was already exists.";			
			$file_ok = false;
		}

		$upload = mmg_image_exists($dl_dir . $mask_file);		
		if( $file_ok && $upload === true ) {
			$mmgDesignLibrary->mmg_message['error'][] = "Sorry, <strong>{$mask_file}</strong> was already exists.";
			$file_ok = false;			
		}
		$upload = $file_ok;

		
		if( $upload && mmg_check_image( $main_file_tmp ) && mmg_check_image( $mask_file_tmp ) ) {



			/**
			 * Upload file
			 */
			if( move_uploaded_file( $main_file_tmp, $dl_dir . $main_file )
			&& move_uploaded_file( $mask_file_tmp, $dl_dir . $mask_file ) ) {


				/**
				 * Get Current User
				 */
				$user = wp_get_current_user();
				$current_user = $user->data;

				$data = array(
					'code' => $code,
					'name' => $name,
					'main_file' => $dl_url . $main_file,
					'mask_file' => $dl_url . $mask_file,
					'layers' => serialize($layers),
					'filters' => serialize($filters),
					'user_id' => $current_user->ID,
					'user_name' => $current_user->display_name
				);

			
				$table_name = $wpdb->prefix . DL_TABLE;
				$wpdb->insert(
					$table_name,
					$data
				);



				$mmgDesignLibrary->mmg_message['success'][] = 'Design assets successfully saved.';


			} else {
				$mmgDesignLibrary->mmg_message['error'][] = 'File was not succesfully uploaded due to file permession.';				
			}


			
		}//end if image is valid
		

	}
}




/**
 * Design Library Edit
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_edit() {
	if( isset( $_POST['dl-update'] ) ) {		
		global $wpdb, $mmgDesignLibrary;

		$update_ok = true;
		$ID = sanitize_text_field( $_POST['dl-id'] );
		$code = $_POST['dl-code'];
		$name = sanitize_text_field( $_POST['dl-name'] );
		$old_name = sanitize_text_field( $_POST['dl-old-name'] );
		$main_file = sanitize_text_field( $_POST['dl-main-file'] );
		$mask_file = sanitize_text_field( $_POST['dl-mask-file'] );
		$layers = $_POST['dl-layers'];
		$filters = $_POST['dl-filters'];


		if( empty($name) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "<strong>Name</strong> must not be empty.";	
			$update_ok = false;				
		}
		

		/**
		 * Create "design-library" directory		 
		 */		
		if( $update_ok ) {
			
			$upload_dir = wp_upload_dir();
			$old_folder_name = strtolower( str_replace(array( '&', ' ' ), '-', $old_name) );
			$new_folder_name = strtolower( str_replace(array( '&', ' ' ), '-', $name) );
			$dl_old_dir = $upload_dir['basedir'] . "/design-library/{$old_folder_name}/";
			$dl_new_dir = $upload_dir['basedir'] . "/design-library/{$new_folder_name}/";
			if( ! file_exists($dl_new_dir) )
				rename($dl_old_dir, $dl_new_dir);


			/**
			 * Get Current User
			 */
			$user = wp_get_current_user();
			$current_user = $user->data;

			$data = array(
				'code' => $code,
				'name' => $name,
				'main_file' => str_replace( $old_name, $name, $main_file),
				'mask_file' => str_replace( $old_name, $name, $mask_file),
				'layers' => serialize($layers),
				'filters' => serialize($filters),
				'user_id' => $current_user->ID,
				'user_name' => $current_user->display_name
			);

		
			$table_name = $wpdb->prefix . DL_TABLE;
			$wpdb->update(
				$table_name,
				$data,
				array( 'id' => $ID )
			);		


			$mmgDesignLibrary->mmg_message['success'][] = 'Design assets successfully updated.';

		}


	}
}




/**
 * Design Library Delete
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_delete() {
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;


	$upload_dir = wp_upload_dir();	
	$dl_dir = $upload_dir['basedir'] . "/design-library";


	$dl_ids = $_POST['dl_id'];
	foreach( $dl_ids as $dl_id ) {
		$dl = explode('::', $dl_id);


		/**
		 * Detete library item from table
		 */
		$wpdb->delete(
			$table_name,
			array( 'id' => $dl[0] )
		);


		/**
		 * Delete Folder and Files		 
		 */
		$folder_name = strtolower( str_replace(array( '&', ' ' ), '-', $dl[1]) );
		$lib_path = $dl_dir . "/{$folder_name}";

		if( is_dir( $lib_path ) ) {
			array_map('unlink', glob("$lib_path/*.*"));
			rmdir($lib_path);
		}
	}

	echo json_encode(array(
		'status' => 'success',
		'message' => 'Design library items successfully deleted.'
	));

	die();
}




/**
 * Design Category Delete
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_category_delete() {	
	global $mmgDesignLibrary;
	
	$slugs = $_POST['dl_id'];	

	$new_categories = $mmgDesignLibrary->remove_category(		
		$slugs		
	);

	update_option( 'mmg_design_library_categories', $new_categories );

	echo json_encode(array(
		'status' => 'success',
		'message' => 'Selected categories successfully deleted.'
	));


	die();
}




/**
 * Add Category
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_category_add() {
	if( isset( $_POST['dl-add-category'] ) ) {
		global $wpdb, $mmgDesignLibrary;

		$add_category = true;
		$name = sanitize_text_field( $_POST['category-name'] );
		$slug = sanitize_text_field( $_POST['category-slug'] );

		
		if( empty( $name ) || empty( $slug ) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "Category name and slug is required.";
			$add_category = false;
		}


		if( $mmgDesignLibrary->is_category_exists( $name ) ) {

			$mmgDesignLibrary->mmg_message['error'][] = "Category name <strong>{$name}</strong> was already exists.";
			$add_category = false;

		} 
		
		if( $add_category === true 
			&& $mmgDesignLibrary->is_category_exists( null, $slug ) ) {
			
			$mmgDesignLibrary->mmg_message['error'][] = "Category slug <strong>{$slug}</strong> was already exists.";
			$add_category = false;

		}


		if( $add_category ) {


			$option = array(				
				'slug' => $slug,
				'name' => $name
			);

			$new_categories = $mmgDesignLibrary->add_category(				
				$option
			);

			update_option( 'mmg_design_library_categories', $new_categories );


			$mmgDesignLibrary->mmg_message['success'][] = "Category name <strong>{$name}</strong> was successfully added.";


			$mmgDesignLibrary->mmg_categories = mmg_get_categories();			


			/**
			 * Add New Column To Design Snapshots
			 *
			 * @since  1.1
			 */
			mmg_design_snapshots_insert_columns( $mmgDesignLibrary->mmg_categories );


		}		

	}
}




/**
 * Update Category
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_category_update() {
	if( isset( $_POST['dl-update-category'] ) ) {
		global $wpdb, $mmgDesignLibrary;

		$update_category = true;

		$key_slug = $_POST['category-key'];
		$name = sanitize_text_field( $_POST['category-name'] );
		$old_name = sanitize_text_field( $_POST['category-name-old'] );
		$slug = sanitize_text_field( $_POST['category-slug'] );
		$old_slug = sanitize_text_field( $_POST['category-slug-old'] );

		
		if( empty( $name ) || empty( $slug ) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "Category name and slug is required.";
			$update_category = false;
		}


		if( $old_name !== $name 
			&& $mmgDesignLibrary->is_category_exists( $name ) ) {

			$mmgDesignLibrary->mmg_message['error'][] = "Category name <strong>{$name}</strong> was already exists.";
			$update_category = false;

		} 
		
		if( $old_slug !== $slug 
			&& $update_category === true 
			&& $mmgDesignLibrary->is_category_exists( null, $slug ) ) {
			
			$mmgDesignLibrary->mmg_message['error'][] = "Category slug <strong>{$slug}</strong> was already exists.";
			$update_category = false;

		}


		if( $update_category ) {

			$option = array(				
				'slug' => $slug,
				'name' => $name
			);

			$new_categories = $mmgDesignLibrary->update_category(				
				$key_slug,
				$option
			);

			update_option( 'mmg_design_library_categories', $new_categories );


			$mmgDesignLibrary->mmg_message['success'][] = "Category name <strong>{$name}</strong> was successfully updated.";
			
			$mmgDesignLibrary->mmg_categories = mmg_get_categories();		

			

			/**
			 * Update Column To Design Snapshots
			 *
			 * @since  1.1
			 */
			mmg_design_snapshots_update_columns(
				array(
					'old' => $key_slug,
					'new' => $slug
				)
			);	

		}		

	}
}




/**
 * Filter Add
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_filter_add() {
	if( isset( $_POST['dl-add-filter'] ) ) {
		global $wpdb, $mmgDesignLibrary;


		$filter_add = true;
		$name = sanitize_text_field( $_POST['filter-name'] );
		$slug = sanitize_text_field( $_POST['filter-slug'] );
		$parent_slug = $_POST['filter-parent-category'];

		if( empty( $name ) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "Filter name is required.";
			$filter_add = false;
		}

		if( $filter_add && empty( $slug ) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "Filter slug is required.";
			$filter_add = false;
		}

		if( $filter_add 
			&& $mmgDesignLibrary->is_filter_exists( $name ) ) {

			$mmgDesignLibrary->mmg_message['error'][] = "Filter name <strong>{$name}</strong> is already exists.";
			$filter_add = false;	
		}

		if( $filter_add 
			&& $mmgDesignLibrary->is_filter_exists( null, $slug ) ) {

			$mmgDesignLibrary->mmg_message['error'][] = "Filter slug <strong>{$slug}</strong> is already exists.";
			$filter_add = false;	
		}			


		/**
		 * Main File
		 * @var string
		 */
		$filter_image_tmp = $_FILES["dl-filter-image"]["tmp_name"];
		$filter_image = basename($_FILES["dl-filter-image"]["name"]);

		if( !empty($filter_image) ) {
			
			
			/**
			 * Create Directory
			 * for filter if not exists
			 */
			$upload_dir = wp_upload_dir();

			$folder_name = strtolower( str_replace(array( '&', ' ' ), '-', $name) );

			$dl_filter_url = $upload_dir['baseurl'] . "/filter-image/{$folder_name}/";
			$dl_filter_dir = $upload_dir['basedir'] . "/filter-image/{$folder_name}/";
			if( ! file_exists($dl_filter_dir) )
				mkdir( $dl_filter_dir, 755, true );


			/**
			 * Upload File
			 */
			if( move_uploaded_file( $filter_image_tmp, $dl_filter_dir . $filter_image ) ) {
				$filter_image = $dl_filter_url . $filter_image;
			}


		}


		if( $filter_add ) {

			$option = array(				
				'name' => $name,
				'slug' => $slug,
				'image' => $filter_image
			);

			$new_filters = $mmgDesignLibrary->add_filter(				
				$parent_slug,
				$option
			);
			
			update_option('mmg_design_library_filters', $new_filters);


			$mmgDesignLibrary->mmg_message['success'][] = "Filter was successfully added.";

			$mmgDesignLibrary->mmg_filters = mmg_get_filters();

		}
	}
}




/**
 * Update Filter
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_filter_update() {
	if( isset( $_POST['dl-update-filter'] ) ) {
		global $wpdb, $mmgDesignLibrary;

		$update_filter = true;
		
		$key_slug = sanitize_text_field( $_POST['key-slug'] );
		$name = sanitize_text_field( $_POST['filter-name'] );
		$old_name = sanitize_text_field( $_POST['filter-name-old'] );
		$slug = sanitize_text_field( $_POST['filter-slug'] );
		$old_slug = sanitize_text_field( $_POST['filter-slug-old'] );
		$parent_slug = $_POST['filter-parent-category'];

		
		if( empty( $name ) || empty( $slug ) ) {
			$mmgDesignLibrary->mmg_message['error'][] = "Filter name and slug is required.";
			$update_filter = false;
		}


		if( $old_name !== $name 
			&& $mmgDesignLibrary->is_filter_exists( $name ) ) {

			$mmgDesignLibrary->mmg_message['error'][] = "Filter name <strong>{$name}</strong> was already exists.";
			$update_filter = false;

		} 
		
		if( $old_slug !== $slug 
			&& $update_filter === true 
			&& $mmgDesignLibrary->is_filter_exists( null, $slug ) ) {
			
			$mmgDesignLibrary->mmg_message['error'][] = "Filter slug <strong>{$slug}</strong> was already exists.";
			$update_filter = false;

		}


		/**
		 * Main File
		 * @var string
		 */
		$filter_image_tmp = $_FILES["dl-filter-image"]["tmp_name"];
		$filter_image = basename($_FILES["dl-filter-image"]["name"]);

		if( !empty($filter_image) ) {
			
			
			/**
			 * Create Directory
			 * for filter if not exists
			 */
			$upload_dir = wp_upload_dir();

			$folder_name = strtolower( str_replace(array( '&', ' ' ), '-', $name) );

			$dl_filter_url = $upload_dir['baseurl'] . "/filter-image/{$folder_name}/";
			$dl_filter_dir = $upload_dir['basedir'] . "/filter-image/{$folder_name}/";
			if( ! file_exists($dl_filter_dir) )
				mkdir( $dl_filter_dir, 755, true );


			/**
			 * Upload File
			 */
			if( move_uploaded_file( $filter_image_tmp, $dl_filter_dir . $filter_image ) ) {
				$filter_image = $dl_filter_url . $filter_image;
			}


		}


		if( $update_filter ) {


			$option = array(				
				'name' => $name,
				'slug' => $slug,
				'image' => $filter_image
			);

			$new_filters = $mmgDesignLibrary->update_filter(				
				$key_slug,
				$option
			);
			
			update_option('mmg_design_library_filters', $new_filters);

			$mmgDesignLibrary->mmg_message['success'][] = "Filter name <strong>{$name}</strong> was successfully updated.";
			
			$mmgDesignLibrary->mmg_filters = mmg_get_filters();			

		}		

	}
}




/**
 * Delete Filters
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_filter_delete() {	
	global $mmgDesignLibrary;

	$slugs = $_POST['dl_id'];
	$new_filters = $mmgDesignLibrary->remove_filter(		
		$slugs		
	);
	
	update_option('mmg_design_library_filters', $new_filters);

	echo json_encode(array(
		'status' => 'success',
		'message' => 'Selected filters successfully deleted.'		
	));


	die();
}




/**
 * Settings Save
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_design_library_settings_save() {	
	if( isset( $_POST['dl-save-settings'] ) ) {

		global $mmgDesignLibrary;

		
		$options = array(
			'dl-default-load' => $_POST['dl-default-load']
		);


		if( get_option('mmg_design_library_settings') ) {
			update_option(
				'mmg_design_library_settings',
				$options
			);	
		} else {
			add_option(
				'mmg_design_library_settings',
				$options
			);	
		}	

		$mmgDesignLibrary->mmg_message['success'][] = "Settings was successfully saved.";


	}
}




/**
 * Check Image
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_check_image( $file ) {
	if(isset($file) && getimagesize($file) !== false)
		return true;
	return false;
}




/**
 * Check Image Exists
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */
function mmg_image_exists( $file ) {
	if(isset($file) && file_exists($file) !== false)
		return true;
	return false;
}