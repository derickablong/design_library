<?php
/**
 * Design Library
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */


/**
 * VIEW: Design Library
 * @since 1.1	 
 */
function mmg_design_librabry_page() {	
	global $wpdb, $mmgDesignLibrary;

	$table_name = $wpdb->prefix . DL_TABLE;
	
	$layers = $mmgDesignLibrary->mmg_categories;	
	$filters = $mmgDesignLibrary->mmg_filters;

	$mode = 'list';
	if( isset( $_GET['m'] ) )	
		$mode = $_GET['m'];

	if( isset( $_GET['e'] ) || isset( $_GET['code'] ) ) {

		if( isset( $_GET['e'] ) )
			$condition = 'id = ' . $_GET['e'];
		if( isset( $_GET['code'] ) )
			$condition = "code = '" . $_GET['code'] . "'";


		$query = "SELECT *
			 FROM {$table_name}
			 WHERE {$condition}";

		$file = 'php/mmg_page_design_library_edit.php';

	} else {


		/**
		 * View From Category Page
		 * @var string
		 */
		$WHERE = '';
		if( isset( $_GET['vc'] ) ) {
			$LIKE = '%:"'. $_GET['vc'] .'";%';
			$WHERE = "WHERE layers LIKE '{$LIKE}'";
		}

		/**
		 * View From Filter Page
		 * @var string
		 */		
		if( isset( $_GET['vf'] ) ) {
			$LIKE = '%:"'. $_GET['vf'] .'";%';
			$WHERE = "WHERE filters LIKE '{$LIKE}'";
		}

		/**
		 * View From Filter Form
		 */
		if( isset( $_GET['design-filter-form'] ) ) {
			$sort_category = $_GET['sort-design-categories'];
			$sort_filter = $_GET['sort-design-filters'];
			
			if( !empty( $sort_category ) ) {
				$LIKE_CAT = '%:"'. $sort_category .'";%';			
				$WHERE = "WHERE layers LIKE '{$LIKE_CAT}'";
			}

			if( !empty( $sort_filter ) ) {
				$LIKE_FIL = '%:"'. $sort_filter .'";%';
				if( !empty( $WHERE ) )
					$WHERE .= " AND filters LIKE '{$LIKE_FIL}'";
				else
					$WHERE = "WHERE filters LIKE '{$LIKE_FIL}'";
			}
		}


		$query = "SELECT *
			 FROM {$table_name}
			 $WHERE";
		
		$file = 'php/mmg_page_design_library.php';

	}

	
	
	$design_library = $wpdb->get_results($query);

	include DLDIR . $file;
}


/**
 * ADD: Design Library
 * @since 1.1	 
 */
function mmg_design_library_add() {	
	global $mmgDesignLibrary;

	$dl_code = $mmgDesignLibrary->mmg_last_code;
	$layers = $mmgDesignLibrary->mmg_categories;	
	$filters = $mmgDesignLibrary->mmg_filters;

	include DLDIR . 'php/mmg_page_design_library_add.php';
}


/**
 * CATEGORIES: Design Library
 * @since 1.1	 
 */
function mmg_design_library_categories() {
	global $mmgDesignLibrary;
	$layers = $mmgDesignLibrary->mmg_categories;

	$ce_name = '';
	$ce_slug = '';
	if( isset( $_GET['ce'] ) ) {
		$ce_option = $mmgDesignLibrary->get_category( $_GET['ce'] );
		$ce_name = $ce_option['name'];
		$ce_slug = $ce_option['slug'];
	}

	include DLDIR . 'php/mmg_page_design_library_categories.php';
}


/**
 * Settings
 * @since 1.1	 
 */
function mmg_design_library_settings() {	
	$settings = get_option('mmg_design_library_settings');
	include DLDIR . 'php/mmg_page_settings.php';
}


/**
 * FILTERS: Design Library
 * @since 1.1	 
 */
function mmg_design_library_filters() {
	global $mmgDesignLibrary;
	$filters = $mmgDesignLibrary->mmg_filters;
	
	
	$fe_name = '';
	$fe_slug = '';
	$fe_image = '';
	$fe_parent = '';

	if( isset( $_GET['fe'] ) ) {
		$fe_option = $mmgDesignLibrary->get_filter( $_GET['fe'] );
		
		$fe_slug = $fe_option['slug'];
		$fe_name = $fe_option['name'];
		$fe_image = $fe_option['image'];
		$fe_parent = $fe_option['parent'];
	}
	
	include DLDIR . 'php/mmg_page_design_library_filters.php';
}



/**
 * Plugin Page	 
 * @since 1.1	 
 */
function mmg_page( $page, $echo = true ) {

	$url = $_GET['page'];
	$page = str_replace('.', '=', $page);
	$page = empty($page)? $url : "{$url}&{$page}";
	$page = "?page={$page}";

	if($echo)
		echo $page;
	else
		return $page;
}



/**
 * Get File Name	 
 * @since 1.1	 
 */
function mmg_file_name( $file_url ) {
	$file = explode('/', $file_url);
	return $file[ count( $file ) - 1 ];
}