<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><div class="alert alert-<?php echo @$type ? @$type : 'info'; ?>">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<?php echo @$message; ?>
</div>