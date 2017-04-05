<?php theme_header(); ?>
<div class="ui grid container">
	<?php echo print_flash_alert(); ?>
	<div class="row">
		<?php echo @$content."\n"; ?>
		<?php theme_partial('sidebar'); ?>
	</div><!--/.row-->
</div><!-- /.ui.grid.container -->
<?php theme_footer(); ?>