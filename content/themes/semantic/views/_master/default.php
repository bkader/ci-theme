<!DOCTYPE html>
<html class="<?php echo Events::trigger('html_class', 'no-js', 'string'); ?>" lang="<?php echo @$lang_abbr ?: 'en'; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?php echo base_url(); ?>">
	<title><?php echo Events::trigger('the_title', $title, 'string'); ?></title>
	<link rel="icon" href="<?php echo base_url('favicon.ico'); ?>">
	<?php echo @$metadata; ?>

	<!-- StyleSheets -->
	<?php echo @$css_files; ?>

	<!--[if lt IE 9]>
	<?php echo js('html5shiv-3.7.3.min', 'https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js', null, 'common'); ?>
	<?php echo js('respond-1.4.2.min', 'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js', null, 'common'); ?>
	<![endif]-->

</head>
<body class="<?php echo Events::trigger('body_class', '', 'string'); ?>">
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
	
	<?php echo @$layout; ?>

	<!-- JavaScripts -->
	<?php echo js('modernizr-2.8.3.min', 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', null, 'common'); ?>
    <script src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo js_url('jquery-1.12.4.min', 'common'); ?>"><\/script>')</script>
	<?php echo @$js_files; ?>

<?php if (config_item('ga_enabled') && (! empty(config_item('ga_siteid')) && config_item('ga_siteid') <> 'UA-XXXXX-Y')): ?>
    <!-- Google Analytics-->
    <script>
        window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
        ga('create','<?php echo config_item('ga_siteid'); ?>','auto');ga('send','pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async defer></script>
<?php endif; ?>

</body>
</html>
