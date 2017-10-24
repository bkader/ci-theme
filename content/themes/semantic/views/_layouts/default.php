<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo @$header; ?>
<div class="ui grid container">
	<?php echo print_flash_alert(); ?>
	<div class="row">
		<?php echo @$content; ?>
		<?php echo @$sidebar; ?>
	</div><!--/.row-->
</div><!-- /.ui.grid.container -->
<?php echo @$footer; ?>
