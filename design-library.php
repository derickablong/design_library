<?php
/**
* Plugin Name: Design Library
* Description: Plugin for make my gear
* Version: 1.0
* Author: Derick Ablong
* Text Domain: mmg-dl
*
*/
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MMG_VERSION', '1.1' );
define( 'DLDIR', plugin_dir_path( __FILE__ ) );
define( 'MMG_DL_URI', plugins_url() . '/design-library-v.'. MMG_VERSION );
define( 'DL_TABLE', 'design_asset' );

/**
 * Design Library Plugin
 */
class MMG_DESIGN_LIBRARY
{

	/**
	 * Message Container
	 * @var  array
	 */
	public $mmg_message;


	/**
	 * Categories
	 * @var  array
	 */
	public $mmg_categories;


	/**
	 * Filters
	 * @var  array
	 */
	public $mmg_filters;


	/**
	 * Last Code Asset
	 * @var  array
	 */
	public $mmg_last_code;


	/**
	 * Constructor
	 * Default function to call on 
	 * instantiate
	 */
	function __construct()
	{
		
		$this->load_dependencies();
		$this->add_hook();		

	}



	/**
	 * Load File Dependencies
	 * Primary php files
	 */
	public function load_dependencies()
	{

		/**
		 * Set Categories and Filters
		 *
		 * @since  1.1
		 */
		include DLDIR . 'php/mmg_options.php';						
		$options = mmg_categories_filters();
		$this->mmg_categories = $options['categories'];
		$this->mmg_filters = $options['filters'];


		if( is_admin() ) {			

			/**
			 * Set Last Asset Code
			 *
			 * @since  1.1
			 */
			$this->set_code_asset();
			
			include DLDIR . 'php/mmg_message.php';						
			include DLDIR . 'php/mmg_install.php';						
			include DLDIR . 'php/mmg_page.php';
			include DLDIR . 'php/mmg_actions.php';
		}	

	}



	/**
	 * Add Hooks
	 * Adds functions to relavent hooks and filters
	 */
	public function add_hook()
	{		
		add_action( 'admin_menu', array( $this, 'setup_admin_menu' ) );	
		add_action( 'admin_init', array( $this, 'design_library_scripts' ) );
		add_action( 'admin_init', 'mmg_design_library_save' );			
		add_action( 'admin_init', 'mmg_design_library_edit' );			
		add_action( 'admin_init', 'mmg_design_library_category_add' );			
		add_action( 'admin_init', 'mmg_design_library_category_update' );			
		add_action( 'admin_init', 'mmg_design_library_filter_add' );			
		add_action( 'admin_init', 'mmg_design_library_filter_update' );			
		add_action( 'admin_init', 'mmg_design_library_settings_save' );			
		
		add_action( 'wp_ajax_dl_library_delete', 'mmg_design_library_delete' );	
		add_action( 'wp_ajax_dl_category_delete', 'mmg_design_category_delete' );		
		add_action( 'wp_ajax_dl_filter_delete', 'mmg_design_filter_delete' );		
	}



	/**
	 * Admin Menu
	 * Add wp-admin menu on sidebar
	 */
	public function setup_admin_menu()
	{
		if ( function_exists( 'add_menu_page' ) ) {
			add_menu_page( 
				'Design Library', 
				__( 'Design Library', 'mmg-dl' ), 
				'moderate_comments', 
				'mmg_design_library', 
				'mmg_design_librabry_page', 
				MMG_DL_URI . '/assets/img/icon.png'
			);

			add_submenu_page( 
				'mmg_design_library', 
				__( 'Add New', 'mmg-dl' ), 
				__( 'Add New', 'mmg-dl' ), 
				'moderate_comments', 
				'mmg_design_library_add', 
				'mmg_design_library_add' 
			);

			add_submenu_page( 
				'mmg_design_library', 
				__( 'Categories', 'mmg-dl' ), 
				__( 'Categories', 'mmg-dl' ), 
				'moderate_comments', 
				'mmg_design_library_categories', 
				'mmg_design_library_categories' 
			);

			add_submenu_page( 
				'mmg_design_library', 
				__( 'Filters', 'mmg-dl' ), 
				__( 'Filters', 'mmg-dl' ), 
				'moderate_comments', 
				'mmg_design_library_filters', 
				'mmg_design_library_filters' 
			);	

			add_submenu_page( 
				'mmg_design_library', 
				__( 'Settings', 'mmg-dl' ), 
				__( 'Settings', 'mmg-dl' ), 
				'moderate_comments', 
				'mmg_design_library_settings', 
				'mmg_design_library_settings' 
			);			
			
		}
	}



	/**
	 * Design Library
	 * Add wp-admin menu on sidebar
	 */
	public function design_library_scripts()
	{
		
		wp_enqueue_style(
			'mmg-global-css',
			MMG_DL_URI . '/assets/css/mmg_global.css',
			array(),
			MMG_VERSION
		);

		wp_register_style(
			'mmg-design-library-css',
			MMG_DL_URI . '/assets/css/mmg_dl.css',
			array(),
			MMG_VERSION
		);

		wp_register_script(
			'mmg-design-library-js',
			MMG_DL_URI . '/assets/js/mmg_dl.js',
			array(),
			MMG_VERSION,
			null,
			true
		);	

		wp_localize_script( 
			'mmg-design-library-js', 
			'ajax_object',
            array( 
            	'ajax_url' => admin_url( 'admin-ajax.php' ), 
            	'we_value' => 1234 
            ) 
        );	
	}



	/**
	 * Set Code Asset
	 */
	public function set_code_asset()
	{
		$this->mmg_last_code = mmg_get_last_code_asset();
	}



	/**
	 * Load Categories
	 */
	public function load_categories()
	{
		return is_array($this->mmg_categories)? $this->mmg_categories : mmg_get_categories();
	}



	/**
	 * Get Category
	 */
	public function get_category( $search )
	{

		$categories = $this->load_categories();
		$option = array();
		
		foreach( $categories as $category ) {
			if( $category['slug'] === $search ) {
				$option = $category;
				break;
			}
		}
		return $option;

	}



	/**
	 * Update Category
	 */
	public function update_category( $search, $option )
	{

		$categories = $this->load_categories();

		foreach( $categories as $row => $category ) {
			if( $category['slug'] === $search ) {
				$categories[$row] = $option;
				break;
			}
		}
		return $categories;

	}



	/**
	 * Add Category
	 */
	public function add_category( $option )
	{
		$categories = $this->load_categories();
		$categories[] = $option;
		return $categories;
	}



	/**
	 * Remove Category
	 */
	public function remove_category( $slugs )
	{

		$categories = $this->load_categories();
		foreach( $slugs as $slug ) {
			foreach( $categories as $row => $category ) {
				if( $category['slug'] === $slug ) {
					unset( $categories[$row] );
					break;
				}
			}

			/**
			 * Drop Column From Design Snapshots
			 *
			 * @since  1.1
			 */
			mmg_design_snapshots_drop_columns( $slug );	

		}
		return $categories;
	}



	/**
	 * Load Filters
	 */
	public function load_filters()
	{
		return is_array($this->mmg_filters)? $this->mmg_filters : mmg_get_filters();
	}




	/**
	 * Get Filter
	 */
	public function get_filter( $slug )
	{

		$filters = $this->load_filters();
		$option = array();

		foreach( $filters as $filter ) {

			/**
			 * Parent
			 */
			if( $filter['parent']['slug'] === $slug ) {
				$option = array(
					'slug' => $filter['parent']['slug'],
					'name' => $filter['parent']['name'],
					'image' => $filter['parent']['image'],
					'parent' => $filter['parent']['slug']
				);				
				break;
			}


			/**
			 * Child
			 */
			if( is_array( $filter['child'] ) ) {
				foreach( $filter['child'] as $child ) {
					if( $child['slug'] === $slug ) {
						$option = array(
							'slug' => $child['slug'],
							'name' => $child['name'],
							'image' => $child['image'],
							'parent' => $filter['parent']['slug']
						);	
						break;
					}
				}		
			}	

		}
		return $option;
	}



	/**
	 * Update Filter
	 */
	public function update_filter( $slug, $option )
	{
		
		$filters = $this->load_filters();

		foreach( $filters as $row => $filter ) {

			/**
			 * Parent
			 */
			if( $filter['parent']['slug'] === $slug ) {
				$filters[$row]['parent'] = $option;				
				break;
			}


			/**
			 * Child
			 */
			if( is_array( $filter['child'] ) ) {
				foreach( $filter['child'] as $section => $child ) {
					if( $child['slug'] === $slug ) {
						$filters[$row]['child'][$section] = $option;
						break;
					}
				}		
			}	

		}

		return $filters;

	}



	/**
	 * Add Filter
	 */
	public function add_filter( $parent, $option )
	{

		$filters = $this->load_filters();
		$is_found = false;

		foreach( $filters as $row => $filter ) {
			
			if( $filter['parent']['slug'] === $parent ) {
				$filters[$row]['child'][] = $option;				
				$is_found = true;
				break;
			}			

		}


		/**
		 * Insert as new parent
		 * if not found in filters
		 */
		if( $is_found !== true ) {
			$filters[] = array(
				'parent' => $option
			);
		}

		return $filters;

	}



	/**
	 * Remove Filter
	 */
	public function remove_filter( $slugs )
	{

		$filters = $this->load_filters();

		foreach( $slugs as $slug ) {
			foreach( $filters as $row => $filter ) {

				/**
				 * Parent, delete all
				 */
				if( $filter['parent']['slug'] === $slug ) {
					unset( $filters[$row] );
					break;
				}

				/**
				 * Child, delete				 
				 */
				foreach( $filter['child'] as $section => $child ) {
					if( $child['slug'] === $slug ) {
						unset( $filters[$row]['child'][$section] );
						break;
					}
				}

			}
		}

		return $filters;

	}



	/**
	 * Category Exists
	 */
	public function is_category_exists( $search_name = null, $search_slug = null )
	{

		$categories = $this->load_categories();
		$exist = false;

		foreach( $categories as $category ) {
			if( $search_name !== null && $search_name === $category['name'] ) {
				$exist = true;
				break;
			}
			if( $search_slug !== null && $search_slug === $category['slug'] ) {
				$exist = true;
				break;
			}
		}
		return $exist;
	}



	/**
	 * Filter Exists
	 */
	public function is_filter_exists( $name = null, $slug = null )
	{

		$filters = $this->load_filters();
		$exist = false;
		foreach( $filters as $filter ) {

			/**
			 * Parent			 
			 */
			if( $name !== null && $name === $filter['parent']['name'] ) {
				$exist = true;
				break;
			}
			if( $slug !== null && $slug === $filter['parent']['slug'] ) {
				$exist = true;
				break;
			}


			/**
			 * Child			 
			 */
			if( is_array( $filter['child'] ) ) {
				foreach( $filter['child'] as $child ) {
					if( $name !== null && $name === $child['name'] ) {
						$exist = true;
						break;
					}
					if( $slug !== null && $slug === $child['slug']) {
						$exist = true;
						break;
					}
				}
			}
		}
		return $exist;
	}


}

$mmgDesignLibrary = new MMG_DESIGN_LIBRARY();
register_activation_hook( __FILE__, array( 'MMG_DL_Install', 'install' ) );