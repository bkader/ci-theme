<nav class="ui fixed inverted menu">
	<div class="ui container">
		<a class="header item" href="<?php echo site_url(); ?>"><?php echo @$site_title; ?></a>
		<a class="item" href="<?php echo site_url(); ?>">Bootstrap Theme</a></li>
		<a class="item" href="https://goo.gl/gIMfYw" target="_blank">GitHub</a>
		<a class="item" href="http://www.codeigniter.com/" target="_blank">CodeIgniter</a>
		<a class="item" href="https://goo.gl/wGXHO9" target="_blank">@kader</a>
		<div class="ui simple dropdown item">
			My Other Repos <i class="dropdown icon"></i>
			<div class="menu">
				<a class="item" href="https:https://goo.gl/4nKdit" target="_blank">CodeIgniter Starter Kit</a>
				<a class="item" href="https://goo.gl/RzSsE9" target="_blank">Gettext Hook</a>
				<a class="item" href="https://goo.gl/dHupYv" target="_blank">Dinakit Framework</a>
				<div class="divider"></div>
				<a class="item" href="https://github.com/bkader?tab=repositories" target="_blank">View all</a>
			</div><!--/.menu-->
		</div><!--/.ui.simple.dropdown.item-->

		<div class="item">
			<div class="ui input">
				<input type="text" name="name" placeholder="Search">
			</div><!--/.div-group-->
		</div><!--/.navbar-div-->

		<div class="right menu">
			<div class="ui simple dropdown item">
				Admin Panel <i class="dropdown icon"></i>
				<div class="menu">
					<?php echo anchor('admin', 'Bootstrap', 'class="item"'); ?>
					<?php echo anchor('admin/semantic', 'Semantic UI', 'class="item"'); ?>
				</div><!--/.menu-->
			</div><!--/.item-->
		</div><!--/.navbar-right-->

		</div><!-- /.navbar-collapse -->
	</div><!--/.ui container-->
</nav><!--/.ui.fixed.inverted.menu-->
