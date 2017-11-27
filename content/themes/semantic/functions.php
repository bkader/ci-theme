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

if ( ! function_exists('setup_assets')):

	/**
	 * Adding theme assets.
	 */
	function setup_assets()
	{
		// StyleSheets
		add_style(
			'semantic',	// Handle
			get_theme_url('assets/css/semantic.min.css'), // URL to file.
			null,	// Version.
			true	// Prepended or not
		);
		add_style('style', get_theme_url('assets/css/style.css'));

		// JavaScripts.
		add_script('semantic', get_theme_url('assets/js/semantic.min'));
	}

setup_assets();
endif;