<?php wp_enqueue_style('mmg-design-library-css'); ?>
<div class="wrap  dl-wrap">
	<h1>Settings</h1>
	
	<?php mmg_dl_message(); ?>

	<form action="" method="post">
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr>
				<th>Number of assets to show in each tabs:</th>
				<td>
					<input type="number" class="widefat" name="dl-default-load" value="<?php echo $settings['dl-default-load'] ?>">
				</td>
			</tr>	
			<tr>
				<td colspan="2" align="right">
					<input type="submit" name="dl-save-settings" value="Save Settings" class="button button-primary">
				</td>
			</tr>		
		</table>
	</form>

</div>