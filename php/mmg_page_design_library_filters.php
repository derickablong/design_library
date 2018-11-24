<?php wp_enqueue_style('mmg-design-library-css'); ?>
<?php wp_enqueue_script('mmg-design-library-js'); ?>
<div class="wrap dl-wrap">
	<h1>Design Library Filters</h1>


	<?php mmg_dl_message(); ?>
	<div class="dl-response"></div>
	

	<div id="col-container" class="wp-clearfix">

		<div id="col-left">
			<div class="col-wrap">


				<div class="form-wrap">
					<h2><?php echo (!empty($fe_name))? 'Update' : 'Add New' ?> Filter</h2>

					<form method="post" action="" class="validate" enctype="multipart/form-data">
						
						<input type="hidden" name="key-slug" value="<?php echo $_GET['fe'] ?>">

						<div class="form-field form-required term-name-wrap">
							<label for="filter-name">Name</label>
							<input name="filter-name" class="dl-name-tag" id="filter-name" type="text" value="<?php echo $fe_name ?>" size="40" aria-required="true">
							<input type="hidden" name="filter-name-old" value="<?php echo $fe_name ?>">
							<p>The name of you filter.</p>
						</div>


						<div class="form-field term-slug-wrap">
							<label for="filter-slug">Slug</label>
							<input name="filter-slug" class="dl-slug-tag" id="filter-slug" type="text" value="<?php echo $fe_slug ?>" size="40">
							<input type="hidden" name="filter-slug-old" value="<?php echo $fe_slug ?>">
							<p>The slug of your filter.</p>
						</div>						


						<div class="form-field term-image-wrap">
							<div class="dl-form-left">
							<label for="filter-image">Image</label>
							<input name="dl-filter-image" class="dl-filter-image" id="filter-image" type="file" value="<?php echo $fe_image ?>">							
							<p>Image identify for your created filter.</p>
							</div>

							<div class="dl-form-right dl-filter-image-preview">
								<?php if( !empty( $fe_image ) ): ?>
								<img src="<?php echo $fe_image ?>" alt="">
								<?php endif; ?>
							</div>
							<div class="wp-clearfix"></div>
						</div>						


						<div class="form-field select-wrap">
							<label for="filter-parent-category">Parent</label>
							
							<select name="filter-parent-category" id="filter-parent-category">
								<option value="0">None</option>
								<?php foreach( $filters as $p_key => $filter ): ?>
								<option value="<?php echo $filter['parent']['slug'] ?>" <?php echo ($filter['parent']['slug'] === $fe_parent)? 'selected' : '' ?>>
									<?php echo $filter['parent']['name'] ?>
								</option>
								<?php endforeach; ?>
							</select>

							<p>If <strong>None</strong>, the filter will automatically treated as a parent.</p>
						</div>		
		

						<p class="submit">
							<?php if( ! empty( $fe_name ) ): ?>
								<input type="submit" name="dl-update-filter" id="submit" class="button button-primary" value="Save Changes">								
								<a href="<?php mmg_page('') ?>" class="button button-default">Cancel</a>
							<?php else: ?>
								<input type="submit" name="dl-add-filter" id="submit" class="button button-primary" value="Add New Filter">								
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

						<input type="submit" id="doaction" class="button filter-delete-action" value="Apply">
					</div>

				</div>


				
				

				<h2 class="screen-reader-text">Filter lists</h2>
				<table class="wp-list-table widefat fixed striped tags">
					<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
								<input id="cb-select-all-1" type="checkbox">
							</td>	

							<th scope="col" width="60"></th>					

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

					<tbody class="filter-table" id="the-list" data-wp-lists="list:tag">
						
						
						
						<?php
							foreach( $filters as $filter ):

								$parent_slug = $filter['parent']['slug'];
								$parent_name = $filter['parent']['name'];
								$parent_image = $filter['parent']['image'];
						?>
							
							
						
							<tr class="filter-parent filter-parent-<?php echo $parent_id ?>">
								<th scope="row" class="check-column">
									<input type="checkbox" class="dl_checkbox" name="layers[]" value="<?php echo $parent_slug?>" <?php echo empty($parent_name)? 'style="display:none;"' : '' ?>>
								</th>
								<td scope="row">
									<?php if( !empty( $parent_image ) ): ?>
									<img src="<?php echo $parent_image ?>" alt="">
									<?php endif; ?>
								</td>
								<td scope="row">
									<?php if( empty( $parent_name ) ): ?>
									<strong>No Parent</strong>
									<?php else: ?>
									<strong><?php echo $parent_name ?></strong>
									<div class="row-actions">
										<span class="edit">
											<a href="<?php mmg_page("fe.{$parent_slug}") ?>">Edit Filter</a> 
										</span>
									</div>
									<?php endif; ?>

								</td>
								<td scope="row">
									
								</td>
								<td scope="row" align="center">
									
								</td>
							</tr>



						
						
							<?php
							if( is_array( $filter['child'] ) ):
								foreach( $filter['child'] as $child ):
							?>
							

							<tr class="filter-child filter-child-<?php echo $filter['parent']['slug'] ?>">
								<th scope="row" class="check-column">
									<input type="checkbox" class="dl_checkbox" name="layers[]" value="<?php echo $child['slug']?>" data-parent="<?php echo $filter['parent']['slug'] ?>">
								</th>	
								<td scope="row">
									<?php if( !empty( $child['image'] ) ): ?>
									<img src="<?php echo $child['image'] ?>" alt="">
									<?php endif; ?>
								</td>							
								<td scope="row">
									- <?php echo $child['name'] ?>
									<div class="row-actions">
										<span class="edit">
											<a href="<?php mmg_page("fe.".$child['slug']) ?>">Edit Filter</a> 
										</div>
								</td>
								<td scope="row">
									<?php echo $child['slug'] ?>
								</td>
								<td scope="row" align="center">
									<strong>
										<a href="?page=mmg_design_library&vf=<?php echo $child['slug'] ?>">
											<?php echo mmg_filters_total( $child['slug'] )  ?>
										</a>
									</strong>
								</td>
							</tr>

							

							<?php endforeach; endif; ?>

						
						

						<?php endforeach; ?>





						
						
						
					</tbody>

					<tfoot>
						<tr>
							<td id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
								<input id="cb-select-all-1" type="checkbox">
							</td>						
							
							<th scope="col"></th>

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

						<input type="submit" id="doaction" class="button filter-delete-action" value="Apply">
					</div>				
				</div>

				



				
			</div>
		</div><!-- /col-right -->

	</div>


</div>