<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo @$title; ?></title>
	<?php echo @$metadata; ?>

	<!-- StyleSheets -->
	<?php echo css('bootstrap.min', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'); ?>
	<?php echo @$css_files; ?>

	<!--[if lt IE 9]>
		<?php echo js('html5shiv.min', 'https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js', NULL, 'common'); ?>
		<?php echo js('respond.min', 'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js', NULL, 'common'); ?>
	<![endif]-->

	<?php echo js('https://buttons.github.io/buttons.js', null, 'async defer'); ?>

</head>
<body>
	<?php echo @$layout."\n"; ?>

	<!-- JavaScripts -->
	<?php echo js('jquery.min', 'http://code.jquery.com/jquery.min.js', NULL, 'common'); ?>
	<?php echo js('bootstrap.min', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'); ?>
	<?php echo @$js_files."\n"; ?>
</body>
</html>