<?php wp_enqueue_style('mmg-design-library-css'); ?>
<div class="wrap  dl-wrap dl-add-new-wrap">
	<h1>Add New Design</h1>
	
	<?php mmg_dl_message(); ?>

	<form action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="dl-nonce" value="<?php echo wp_create_nonce('dl-nonce') ?>">
		<input type="hidden" name="dl-code" value="<?php echo $dl_code ?>">
		<table class="form-table">
			<tr>
				<th>Name</th>
				<td>
					<input type="text" name="dl-name">
				</td>
			</tr>
			<tr>
				<th>Main File</th>
				<td>
					<input type="file" name="dl-main-file">
				</td>
			</tr>
			<tr>
				<th>Mask File</th>
				<td>
					<input type="file" name="dl-mask-file">
				</td>
			</tr>
			<tr>
				<th>Assign Layers</th>
				<td>
					<div class="dl-title">Layers</div>
					<div class="dl-group">						
						<?php foreach( $layers as $layer ): ?>
						<label for="<?php echo $layer['slug'] ?>">
							<input type="checkbox" name="dl-layers[]" value="<?php echo $layer['slug'] ?>" id="<?php echo $layer['slug'] ?>">
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
										<input type="checkbox" name="dl-filters[]" value="<?php echo $child['slug'] ?>" id="<?php echo $child['slug'] ?>">
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
					<input type="submit" name="dl-add" value="Add Library" class="button button-primary">
				</td>
			</tr>
		</table>
	</form>
</div>