<?php 
$sort_category = isset($_GET['sort-design-categories'])? $_GET['sort-design-categories'] : '';
$sort_filter = isset($_GET['sort-design-filters'])? $_GET['sort-design-filters'] : '';

wp_enqueue_style('mmg-design-library-css');
wp_enqueue_script('mmg-design-library-js');
?>
<div class="wrap dl-wrap">
	<h1 class="wp-heading-inline">Design Library</h1>

	<a href="?page=mmg_design_library_add" class="page-title-action">Add New</a>
	<hr class="wp-header-end">
	
	<div class="dl-response"></div>

	<h2 class="screen-reader-text">Filter design library items list</h2>
	
	<div class="wp-filter">
		<form action="" method="get">
			<input type="hidden" name="page" value="<?php echo $_GET['page'] ?>">

			<div class="filter-items">			
				<div class="view-switch">
					<a href="<?php mmg_page('m.list') ?>" class="view-list <?php echo ($mode == 'list')? 'current' : '' ?>" id="view-switch-list">
						<span class="screen-reader-text">List View</span>
					</a>
					<a href="<?php mmg_page('m.grid') ?>" class="view-grid <?php echo ($mode == 'grid')? 'current' : '' ?>" id="view-switch-grid">
						<span class="screen-reader-text">Grid View</span>
					</a>
				</div>

				<label for="design-categories" class="screen-reader-text">Design Categories</label>
				<select class="sort-design-categories" name="sort-design-categories" id="design-categories">
					<option value="">All Design Categories</option>					
					
					
					<?php foreach( $layers as $layer ): ?>
					<option value="<?php echo $layer['slug'] ?>" <?php echo ($sort_category == $layer['slug'] )? 'selected' : ''?>>
						<?php echo $layer['name'] ?>							
					</option>
					<?php endforeach; ?>					

				</select>

			<div class="actions">		
				<label for="design-filters" class="screen-reader-text">Design Filters</label>
				<select class="sort-design-filters" name="sort-design-filters" id="design-filters">
					<option value="">All Design Filters</option>					
					

					<?php foreach( $filters as $filter ): ?>								
						

						<optgroup label="<?php echo $filter['parent']['name'] ?>">

							<?php foreach( $filter['child'] as $child ): ?>
							<option value="<?php echo $child['slug'] ?>" <?php echo ($sort_filter == $child['slug'] )? 'selected' : ''?>>
								<?php echo $child['name'] ?>									
							</option>
							<?php endforeach; ?>

						</optgroup>					
						


					<?php endforeach; ?>					

				</select>
				
				<input type="submit" name="design-filter-form" id="post-query-submit" class="button" value="Filter">	
			</div>
		</form>
	</div>

	
	<div class="search-form">
		<label for="media-search-input" class="screen-reader-text">Search Design Library</label>
		<input type="search" placeholder="Search design library items..." id="media-search-input" class="search" name="s" value=""></div>
	</div>

	
	<div class="alignleft actions bulkactions">
		<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
		<select name="action" id="bulk-action-selector-top">
			<option value="-1">Bulk Actions</option>
			<option value="delete">Delete</option>
		</select>
		<input type="submit" id="doaction" class="button lib-delete-action" value="Apply">
	</div>



	
	<h2 class="screen-reader-text">Design Library List</h2>
	<table class="wp-list-table widefat fixed striped media">


		<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Name</span>							
					</a>
				</th>
				
				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Main File</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Mask File</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Layer</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Filter</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>User</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					
				</th>

				
			</tr>
		</thead>



		<tbody id="the-list">
			<?php foreach( $design_library as $library ): ?>

				<tr class="author-self status-inherit">
					<th scope="row" class="check-column">
						<input type="checkbox" class="dl_checkbox" name="library[]" value="<?php echo $library->id . '::' . $library->name ?>">
					</th>

					<td class="has-row-actions column-primary" data-colname="Name">
						<strong>
							<a href="<?php echo mmg_page("e.{$library->id}") ?>">
								<?php echo ucfirst($library->name); ?>
							</a>
						</strong>
					</td>

					<td class="title column-title has-row-actions column-primary" data-colname="Main File">
						<strong class="has-media-icon">							
							<span class="media-icon image-icon">
								<img width="24" height="24" src="<?php echo $library->main_file ?>" class="attachment-60x60 size-60x60" alt="" sizes="100vw">
							</span> <?php echo mmg_file_name($library->main_file); ?>							
						</strong>

						<p class="filename">
							<span class="screen-reader-text">File name: </span>
							<?php echo mmg_file_name($library->main_file); ?>
						</p>
						

					</td>


					<td class="title column-title has-row-actions column-primary" data-colname="Mask File">
						<strong class="has-media-icon">							
							<span class="media-icon image-icon">
								<img width="24" height="24" src="<?php echo $library->mask_file ?>" class="attachment-60x60 size-60x60" alt="" sizes="100vw">
							</span> <?php echo mmg_file_name($library->mask_file); ?>							
						</strong>

						<p class="filename">
							<span class="screen-reader-text">File name: </span>
							<?php echo mmg_file_name($library->mask_file); ?>
						</p>
						
					</td>


					<td class="has-row-actions column-primary" data-colname="Layer">
						<?php $dl_layers = unserialize($library->layers); ?>
						<ul>
						<?php foreach( $dl_layers as $layer_slug ):?>
							
							<?php
							$layer_option = $mmgDesignLibrary->get_category( $layer_slug );

							if( !empty( $layer_option['name'] ) ): ?>

							<li><?php echo $layer_option['name'] ?></li>

						<?php endif; endforeach; ?>
						</ul>
					</td>


					<td class="has-row-actions column-primary" data-colname="Filters">
						<?php $dl_filters = unserialize($library->filters); ?>
						<ul>
						<?php 
						if( is_array( $dl_filters ) ):
							foreach( $dl_filters as $filter_slug ): ?>
							
							<?php
							$filter_option = $mmgDesignLibrary->get_filter( $filter_slug );

							if( !empty( $filter_option['name'] ) ): ?>

							<li><?php echo $filter_option['name'] ?></li>
							
						<?php endif; endforeach; endif;?>
						</ul>
					</td>

					<td class="has-row-actions column-primary" data-colname="User">
						<strong>
							<?php echo ucfirst($library->user_name); ?>
						</strong>
					</td>

					<td>
						<a href="<?php echo mmg_page("e.{$library->id}") ?>" class="button button-default">Edit</a>
					</td>

				</tr>

			<?php endforeach; ?>
		</tbody>

		<tfoot>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Name</span>							
					</a>
				</th>
				
				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Main File</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Mask File</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Layer</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>Filter</span>							
					</a>
				</th>

				<th scope="col" id="title" class="manage-column column-primary sortable desc">
					<a href="#">
						<span>User</span>							
					</a>
				</th>
				
				<th></th>
				
			</tr>
		</tfoot>

	</table>

	<div class="tablenav bottom">

		<div class="alignleft actions bulkactions">
			<label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
			<select name="action2" id="bulk-action-selector-bottom">
				<option value="-1">Bulk Actions</option>
				<option value="delete">Delete</option>
			</select>

			<input type="submit" id="doaction2" class="button lib-delete-action" value="Apply">
		</div>		
	</div>


</div>