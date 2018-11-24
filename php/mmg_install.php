<?php
/**
 * On Install Plugin
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */

class MMG_DL_Install
{



	/**
	 * Install
	 * Install needed options
	 * and tables
	 */
	public function install()
	{				
		global $wpdb;


		/**
		 * Call WP Library
		 */
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$charset_collate = $wpdb->get_charset_collate();

		/**
		 * Design Library
		 * Create Table
		 * Name: prefix_design_assets		 
		 */
		$table_name = $wpdb->prefix . DL_TABLE;

		/**
		 * Drop Table First
		 */
		dbDelta("DROP TABLE IF EXISTS {$table_name}");

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		  code TINYTEXT NOT NULL,
		  name TINYTEXT NOT NULL,
		  main_file VARCHAR(200),
		  mask_file VARCHAR(200),
		  layers TEXT NULL,
		  filters TEXT NULL,
		  user_id mediumint(9) DEFAULT 0 NULL,
		  user_name TINYTEXT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";		
		dbDelta( $sql );



		/**
		 * Register Design Library Settings
		 * @var array
		 */
		$mmg_design_library_settings = array(
			

			/**
			 * Design Library Layers
			 * @since  1.1
			 */
			'layers' => array(
				array(
					'slug' => 'layer_1',
					'name' => 'Layer_1'
				),
				array(
					'slug' => 'layer_2',
					'name' => 'Layer_2'
				),
				array(
					'slug' => 'layer_3',
					'name' => 'Layer_3'
				),
				array(
					'slug' => 'layer_4',
					'name' => 'Layer_4'
				)
			)

		);


		/**
		 * Categories			 
		 */		
		$categories = $mmg_design_library_settings['layers'];
		if( get_option('mmg_design_library_categories') ) {
			update_option(
				'mmg_design_library_categories',
				$categories
			);	
		} else {
			add_option(
				'mmg_design_library_categories',
				$categories
			);	
		}		


	




		

		/**
		 * Register Design Library Settings
		 * @var array
		 */
		$mmg_design_library_settings = array(				

			

			/**
			 * Design Library Filters
			 * @since  1.1
			 */
			'filters' => array(
				
				array(
					'parent' => array(
						'slug' => 'colours',
						'name' => 'Colours',
						'image' => ''
					),

					'child' => array(
						array(
							'slug' => 'red',
							'name' => 'Red',
							'image' => ''
						),
						array(
							'slug' => 'green',
							'name' => 'Green',
							'image' => ''
						),
						array(
							'slug' => 'blue',
							'name' => 'Blue',
							'image' => ''
						)
					)
				),


				array(
					'parent' => array(
						'slug' => 'nature',
						'name' => 'Nature',
						'image' => ''
					),

					'child' => array(
						array(
							'slug' => 'water',
							'name' => 'Water',
							'image' => ''
						),
						array(
							'slug' => 'fire',
							'name' => 'Fire',
							'image' => ''
						),
						array(
							'slug' => 'sky',
							'name' => 'Sky',
							'image' => ''
						),
						array(
							'slug' => 'earth',
							'name' => 'Earth',
							'image' => ''
						)
					)
				),


				array(
					'parent' => array(
						'slug' => 'animals',
						'name' => 'Animals',
						'image' => ''
					),

					'child' => array(
						array(
							'slug' => 'reptiles',
							'name' => 'Reptiles',
							'image' => ''
						),
						array(
							'slug' => 'ocean',
							'name' => 'Ocean',
							'image' => ''
						),
						array(
							'slug' => 'land',
							'name' => 'Land',
							'image' => ''
						),
						array(
							'slug' => 'birds',
							'name' => 'Birds',
							'image' => ''
						)
					)
				),


				array(
					'parent' => array(
						'slug' => 'sports',
						'name' => 'Sports',
						'image' => ''
					),

					'child' => array(
						array(
							'slug' => 'hockey',
							'name' => 'Hockey',
							'image' => ''
						),
						array(
							'slug' => 'baseball',
							'name' => 'Baseball',
							'image' => ''
						),
						array(
							'slug' => 'football',
							'name' => 'Football',
							'image' => ''
						),
						array(
							'slug' => 'golf',
							'name' => 'Golf',
							'image' => ''
						)
					)
				)


			)


		);

		

		/**
		 * Filters			 
		 */		
		$filters = $mmg_design_library_settings['filters'];
		if( get_option('mmg_design_library_filters') ) {
			update_option(
				'mmg_design_library_filters',
				$filters
			);	
		} else {
			add_option(
				'mmg_design_library_filters',
				$filters
			);	
		}		
		



	}
}