<?php get_header(); ?>
<div class="container">
	<?php echo print_flash_alert(); ?>
	<div class="row">
		<?php echo @$content."\n"; ?>
		<?php get_partial('sidebar'); ?>
	</div><!--/.row-->
</div><!-- /.container -->
<?php get_footer(); ?>