<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Default theme functions file.
 *
 * This file contain any type of helpers used by the current theme.
 *
 * @package 	CodeIgniter
 * @subpackage 	CI-Theme
 * @category 	Theme Helpers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

if ( ! function_exists('bs_alert'))
{
	/**
	 * Returns a Bootstrap alert.
	 *
	 * @param 	string 	$message 	the message to be displayed.
	 * @return 	string 	Bootstrap full alert.
	 */
	function bs_alert($message = '', $type = 'info')
	{
		if (empty($message))
		{
			return;
		}

		// Turn 'error' into 'danger' because it does not exist on bootstrap.
		$type == 'error' && $type = 'danger';

		$alert =<<<EOD
<div class="alert alert-{type}">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	{message}
</div>
EOD;
		return str_replace(
			array('{type}', '{message}'),
			array($type, $message),
			$alert
		);
	}
}
