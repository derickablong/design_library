<?php wp_enqueue_script('mmg-design-library-js'); ?>
<div class="wrap dl-wrap">
	<h1>Design Library Categories</h1>


	<?php mmg_dl_message(); ?>
	<div class="dl-response"></div>
	

	<div id="col-container" class="wp-clearfix">

		<div id="col-left">
			<div class="col-wrap">


				<div class="form-wrap">
					<h2><?php echo (!empty($ce_name))? 'Update' : 'Add New' ?> Category</h2>

					<form method="post" action="" class="validate">
						
						<input type="hidden" name="category-key" value="<?php echo $_GET['ce'] ?>">

						<div class="form-field form-required term-name-wrap">
							<label for="category-name">Name</label>
							<input name="category-name" class="dl-name-tag" id="category-name" type="text" value="<?php echo $ce_name ?>" size="40" aria-required="true">
							<input type="hidden" name="category-name-old" value="<?php echo $ce_name ?>">
							<p>The name of you category.</p>
						</div>


						<div class="form-field term-slug-wrap">
							<label for="category-slug">Slug</label>
							<input name="category-slug" class="dl-slug-tag" id="category-slug" type="text" value="<?php echo $ce_slug ?>" size="40">
							<input type="hidden" name="category-slug-old" value="<?php echo $ce_slug ?>">
							<p>The slug of your category.</p>
						</div>						


						<p class="submit">
							<?php if( ! empty( $ce_name ) ): ?>
								<input type="submit" name="dl-update-category" id="submit" class="button button-primary" value="Save Changes">								
								<a href="<?php mmg_page('') ?>" class="button button-default">Cancel</a>
							<?php else: ?>
								<input type="submit" name="dl-add-category" id="submit" class="button button-primary" value="Add New Category">								
							<?php endif; ?>
						</p>

					</form>
				</div>

			</div>
		</div><!-- /col-left -->


		<div id="col-right">
			<div class="col-wrap">


			
				<div class="tablenav top">

					<div class="alignleft actions bulkactions">
						<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
						<select name="action" id="bulk-action-selector-top">
							<option value="-1">Bulk Actions</option>
							<option value="delete">Delete</option>
						</select>

						<input type="submit" id="doaction" class="button cat-delete-action" value="Apply">
					</div>

				</div>


				<h2 class="screen-reader-text">Categories list</h2>
				<table class="wp-list-table widefat fixed striped tags">
					<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
								<input id="cb-select-all-1" type="checkbox">
							</td>

							<th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
								<a href="#">
									<span>Name</span>									
								</a>
							</th>
							

							<th scope="col" id="slug" class="manage-column column-slug sortable desc">
								<a href="#">
									<span>Slug</span>										
								</a>
							</th>

							<th scope="col" id="posts" class="manage-column column-posts num sortable desc">
								<a href="#">
									<span>Count</span>										
								</a>
							</th>	
						</tr>
					</thead>

					<tbody id="the-list" data-wp-lists="list:tag">
						
						
						<?php foreach( $layers as $layer ): ?>
						<tr>
							<th scope="row" class="check-column">
								<input type="checkbox" class="dl_checkbox" name="layers[]" value="<?php echo $layer['slug']?>">
							</th>
							<td scope="row">
								<strong><?php echo $layer['name'] ?></strong>
								<div class="row-actions">
									<span class="edit">
										<a href="<?php mmg_page("ce.{$layer['slug']}") ?>">Edit Category</a> 
									</div>
							</td>
							<td scope="row">
								<strong><?php echo $layer['slug'] ?></strong>
							</td>
							<td scope="row" align="center">
								<strong>
									<a href="?page=mmg_design_library&vc=<?php echo $layer['slug'] ?>">
										<?php echo mmg_categories_total( $layer['slug'] )  ?>
									</a>
								</strong>
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

							<th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
								<a href="#">
									<span>Name</span>									
								</a>
							</th>
							

							<th scope="col" id="slug" class="manage-column column-slug sortable desc">
								<a href="#">
									<span>Slug</span>										
								</a>
							</th>

							<th scope="col" id="posts" class="manage-column column-posts num sortable desc">
								<a href="#">
									<span>Count</span>										
								</a>
							</th>	
						</tr>
					</tfoot>

				</table>
				



				<div class="tablenav bottom">
					<div class="alignleft actions bulkactions">
						<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
						<select name="action" id="bulk-action-selector-bottom">
							<option value="-1">Bulk Actions</option>
							<option value="delete">Delete</option>
						</select>

						<input type="submit" id="doaction" class="button cat-delete-action" value="Apply">
					</div>				
				</div>

				



				
			</div>
		</div><!-- /col-right -->

	</div>


</div>