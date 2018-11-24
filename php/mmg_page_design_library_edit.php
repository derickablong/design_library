<?php
/**
 * Design Library
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */

$library = $design_library[0];
$lib_layers = unserialize($library->layers);
$lib_filters = unserialize($library->filters);


wp_enqueue_style('mmg-design-library-css');
?>


<div class="wrap dl-wrap">
	<h1>Edit Design Library</h1>
	
	<?php mmg_dl_message(); ?>

	<form action="" method="post">
		<input type="hidden" name="dl-nonce" value="<?php echo wp_create_nonce('dl-nonce') ?>">
		<input type="hidden" name="dl-id" value="<?php echo $library->id ?>">
		<input type="hidden" name="dl-code" value="<?php echo $library->code ?>">
		<table class="form-table">
			<tr>
				<th>Name</th>
				<td>
					<input type="text" name="dl-name" value="<?php echo $library->name ?>">
					<input type="hidden" name="dl-old-name" value="<?php echo $library->name ?>">
				</td>
			</tr>			
			<tr>				
				<td colspan="2">
					
					<div class="dl-box">
						<img src="<?php echo $library->main_file ?>" alt="">
						<div>
							<strong>Main File</strong>
						</div>
						<input type="hidden" name="dl-main-file" value="<?php echo $library->main_file ?>">
					</div>

					<div class="dl-box">
						<img src="<?php echo $library->mask_file ?>" alt="">
						<div>
							<strong>Mask File</strong>
						</div>
						<input type="hidden" name="dl-mask-file" value="<?php echo $library->mask_file ?>">
					</div>

				</td>
			</tr>
			<tr>
				<th>Assign Layers</th>
				<td>
					<div class="dl-title">Layers</div>
					<div class="dl-group">						
						<?php foreach( $layers as $layer ): ?>
						<label for="<?php echo $layer['slug'] ?>">
							<input type="checkbox" name="dl-layers[]" value="<?php echo $layer['slug'] ?>" id="<?php echo $layer['slug'] ?>" <?php echo (in_array( $layer['slug'], $lib_layers ))? 'checked' : '' ?>>
							<?php echo $layer['name']; ?>
						</label>
						<?php endforeach; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<h4>Filters</h4>
					<table cellpadding="0" cellspacing="0" class="dl-table-group">
						<tr>

							

						<?php
						foreach( $filters as $filter ):

							$parent_id = $filter['parent']['id'];
							$parent_name = $filter['parent']['name'];
						?>
							
							<td>
								<div class="dl-title">
									<?php echo empty( $parent_name )? 'No Parent' : $parent_name ?>
								</div>
								<div class="dl-group">	
							
									<?php
									if( is_array( $filter['child'] ) ):
										foreach( $filter['child'] as $child ): ?>
									<label for="<?php echo $child['slug'] ?>">
										<input type="checkbox" name="dl-filters[]" value="<?php echo $child['slug'] ?>" id="<?php echo $child['slug'] ?>" <?php echo (in_array( $child['slug'], $lib_filters ))? 'checked' : '' ?> >
										<?php echo $child['name']; ?>
									</label>
									<?php endforeach; endif; ?>

							
								</div>
							</td>


						<?php endforeach; ?>							


						</tr>
					</table>

				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" name="dl-update" value="Update Library" class="button button-primary">
				</td>
			</tr>
		</table>
	</form>
</div>