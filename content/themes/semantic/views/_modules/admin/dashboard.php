<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><div class="sixteen wide tablet ten wide computer column">
	<div class="ui segment">
		<h1 class="ui header" style="margin-top: 0;">Admin Panel</h1>
		
		<p>Each module can have its own admin area. All you need to do is to create the admin controller:</p>
		<p><code>./application/modules/<strong>module_name</strong>/controllers/<strong>Admin.php</strong></code></p>
		<p>This controller should extends <code><strong>Admin_Controller</strong></code> and I guess you know why (<em class="text-muted">don't you?!!</em>).</p>
		<p>A module called <code><strong>admin</strong></code> is provided to display admin panel main page. Feel free to add anything to it.</p>
		<hr>
		<h4>More Details</h4>
		<p>Let's suppose you have a module called <code><strong>users</strong></code> that has as many controllers, libraries ... as you want. If you want to create an admin area to this module, simply create the <code><strong>Admin.php</strong></code> controller. DONE!</p>
		<p>Next, you will have to create views! The best thing to do is to create views inside one of the following folders:</p>
		<p>
			<ul>
				<li><code>../themes/<strong>theme_name</strong>/modules/<strong>module_name</strong>/admin/<strong>view_file.php</strong></code></li>
				<li><code>../themes/<strong>theme_name</strong>/views/admin/<strong>view_file.php</strong></code></li>
				<li><code>../application/modules/<strong>module_name</strong>/views/admin/<strong>view_file.php</strong></code></li>
			</ul>
		</p>
	
	</div><!--/.ui.segment-->
</div><!--/.column-->
