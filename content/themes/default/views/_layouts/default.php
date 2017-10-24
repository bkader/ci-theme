<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo @$header; ?>
<div class="container">
	<?php echo print_flash_alert(); ?>
	<div class="row">
		<?php echo @$content; ?>
		<?php echo @$sidebar; ?>
	</div><!--/.row-->
</div><!-- /.container -->
<?php echo @$footer; ?>
