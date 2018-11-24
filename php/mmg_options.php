<?php
/**
 * Design Library Save
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */


/**
 * Get Last Asset Code
 *  
 * @since 1.1	 
 */
function mmg_get_last_code_asset() {	
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;	
	$query = "SELECT id 
	          FROM {$table_name} 
	          ORDER BY id DESC
	          LIMIT 1";


	$result = $wpdb->get_results( $query );
	$code = 'design_asset_000000001';

	if( count( $result ) ) {
		$counter = ($result[0]->id + 1);		
		$code = str_pad($counter, 9, '0', STR_PAD_LEFT);
		$code = 'design_asset_' . $code;
	}

	return $code;
}


/**
 * Get Categories and filters	
 *  
 * @since 1.1	 
 */
function mmg_categories_filters() {	
	return array(
		'categories' => mmg_get_categories(),
		'filters' => mmg_get_filters()
	);
}


/**
 * Get Categories Data	 
 * 
 * @since 1.1	 
 */
function mmg_get_categories() {	
	return get_option('mmg_design_library_categories');
}


/**
 * Get Filters Data	 
 * 
 * @since 1.1	 
 */
function mmg_get_filters() {
	return get_option('mmg_design_library_filters');
}


/**
 * Total Items From Category
 * 	 
 * @since 1.1	 
 */
function mmg_categories_total( $id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;
	$like = '%:"'. $id .'";%';
	$query = "SELECT * 
	          FROM {$table_name} 
	          WHERE layers LIKE '{$like}'";


	$categories = $wpdb->get_results( $query );
	return count( $categories );
}


/**
 * Total Items From Filters	 
 * 
 * @since 1.1	 
 */
function mmg_filters_total( $id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;
	$like = '%:"'. $id .'";%';
	$query = "SELECT * 
	          FROM {$table_name} 
	          WHERE filters LIKE '{$like}'";


	$filters = $wpdb->get_results( $query );
	return count( $filters );
}


/**
 * API Get All Assets
 *  
 * @since 1.1	 
 */
function mmg_design_library_api() {
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;
	$query = "SELECT *
			  FROM {$table_name}
			  ORDER BY name ASC";

	return $wpdb->get_results( $query );
}


/**
 * API Get Asset By ID
 *  
 * @since 1.1	 
 */
function mmg_design_library_api_id( $asset_id = 0 ) {
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;
	$query = "SELECT *
			  FROM {$table_name}
			  WHERE id = {$asset_id}
			  ORDER BY name ASC";

	return $wpdb->get_results( $query );
}


/**
 * API Get Asset By Code
 *  
 * @since 1.1	 
 */
function mmg_design_library_api_code( $code = '' ) {
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;
	$query = "SELECT *
			  FROM {$table_name}
			  WHERE code = '{$code}'
			  ORDER BY name ASC";

	$results = $wpdb->get_results( $query );

	return $results[0];
}


/**
 * API Asset Codes By ID
 *  
 * @since 1.1	 
 */
function mmg_design_library_codes_api( $layer ) {
	global $wpdb;

	$table_name = $wpdb->prefix . DL_TABLE;
	$query = "SELECT id, code
			  FROM {$table_name}
			  WHERE layers LIKE '%{$layer}%'
			  ORDER BY code";

	$results = $wpdb->get_results( $query );

	$codes = array();
	foreach( $results as $result ) {
		
		$codes[] = array(
			'id' => $result->id,
			'code' => $result->code
		);
		
	}

	return $codes;
}


/**
 * API Assets By Layer
 *  
 * @since 1.1	 
 */
function mmg_design_library_assets_api( $layer, $limit = 18, $paged = 1 ) {
	global $wpdb;

	if ($paged > 1) {
		$start = $limit * $paged;
		$limit = "{$start}, $limit";
	}

	$table_name = $wpdb->prefix . DL_TABLE;
	$query = "SELECT *
			  FROM {$table_name}
			  WHERE layers LIKE '%{$layer}%'
			  ORDER BY code
			  LIMIT {$limit}";

	$results = $wpdb->get_results( $query );

	return $results;
}


/**
 * API Assets By Layer/Filter
 *  
 * @since 1.1	 
 */
function mmg_design_library_assets_filter_api( $layer, $filters, $limit = 18, $paged = 1 ) {
	global $wpdb;

	
	$start = $limit * ($paged - 1);
	$limit = "{$start}, $limit";


	$filter_condition = [];	
	$count = 1;
	foreach ( $filters as $filter ) {

		$OR = "OR";
		if ($count === 1 )
			$OR = "";

		$condition = "{$OR} filters LIKE '%{$filter}%'";		
		$filter_condition[] = $condition;
		
		$count++;
	}

	if ( count( $filter_condition ) ) {
		$condition = "(" . implode(' ', $filter_condition) . ")";	
		$condition .= " AND ";
	} else {
		$condition = "";
	}

	$table_name = $wpdb->prefix . DL_TABLE;
	$query = "SELECT *
			  FROM {$table_name}
			  WHERE {$condition}
			  layers LIKE '%{$layer}%'
			  ORDER BY code
			  LIMIT {$limit}";

	return array($wpdb->get_results( $query ), $query);
	
}