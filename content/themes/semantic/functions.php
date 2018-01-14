<?php
/**
 * CI-Theme Library
 *
 * This library makes your CodeIgniter applications themable.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 - 2018, Kader Bouyakoub <bkader@mail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package 	CodeIgniter
 * @author 		Kader Bouyakoub <bkader@mail.com>
 * @copyright	Copyright (c) 2017 - 2018, Kader Bouyakoub <bkader@mail.com>
 * @license 	http://opensource.org/licenses/MIT	MIT License
 * @link 		https://github.com/bkader
 * @since 		Version 1.0.0
 */
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

/**
 * This class is here to demonstrate the use of 
 * Events library with Theme library.
 */
class Theme_class
{
	public function __construct()
	{
		/**
		 * With this event registered, theme can independently enqueue
		 * all needed StyleSheets without adding them in controllers.
		 */
		Events::register('enqueue_styles', array($this, 'styles'));

		/**
		 * With this event registered, theme can independently enqueue
		 * all needed JS files without adding them in controllers.
		 */
		Events::register('enqueue_scripts', array($this, 'scripts'));

		/**
		 * With this event registered, theme can independently enqueue
		 * all needed meta tags without adding them in controllers.
		 */
		Events::register('enqueue_metadata', array($this, 'metadata'));

		// Manipulating <html> class.
		Events::register('html_class', array($this, 'html_class'));

		// Manipulating <body> class.
		Events::register('body_class', array($this, 'body_class'));
	}

	// ------------------------------------------------------------------------

	/**
	 * Enqueue all theme's StyleSheets.
	 * @access 	public
	 * @return 	void
	 */
	public function styles()
	{
		// Let's add semantic css file.
		add_style('assets/css/semantic.min');

		// Now we add the the default StyleSheet.
		add_style('assets/css/style');

	}

	// ------------------------------------------------------------------------

	/**
	 * Enqueue all theme's JavaScripts.
	 * @access 	public
	 * @return 	void
	 */
	public function scripts()
	{
		// Let's add semantic js file.
		prepend_script('assets/js/semantic.min');
		add_script('assets/js/scripts');
	}

	// ------------------------------------------------------------------------

	/**
	 * Enqueue all theme's Meta tags.
	 * @access 	public
	 * @return 	void
	 */
	public function metadata()
	{
		add_meta('generator', 'CI-Theme Library');
		add_meta('author', 'Kader Bouyakoub');
		add_meta('author', 'https://github.com/bkader', 'rel');

		// Let's add some extra tags.
		add_meta('twitter:card', 'summary');
		add_meta('twitter:site', '@KaderBouyakoub');
		add_meta('twitter:creator', '@KaderBouyakoub');
		add_meta('og:url', current_url());
		add_meta('og:title', 'CI-Theme Library');
		add_meta('og:description', 'Simply makes your CI-based applications themable. Easy and fun to use.');
		add_meta('og:image', get_theme_url('screenshot.png'));

		// And why not more!
		add_meta('manifest', base_url('site.webmanifest'), 'rel');
		add_meta('apple-touch-icon', base_url('icon.png'), 'rel');
	}

	// ------------------------------------------------------------------------

	/**
	 * Let's manipulate html class.
	 * @access 	public
	 * @param 	string 	$class
	 * @return 	string
	 */
	public function html_class($class)
	{
		// You can add class for a specific module!
		// if (is_module('module_name')) {}
		// if (is_module('mod_1, mod_2'))

		// Or set class for a specific controller.
		if (is_controller('example'))
		{
			return 'html-class-semantic-theme controller-example';
		}

		if (is_controller('admin'))
		{
			return 'html-class-semantic-theme controller-admin';
		}

		// You can as well set if for a specific method.
		// if (is_method(...)) {}

		// And you can chain all.
		// if (is_controller('example') && is_method('index')) {}

		return $class;
	}

	// ------------------------------------------------------------------------

	/**
	 * Manipulating <body> class.
	 * @access 	public
	 * @param 	string 	$class
	 * @return 	string
	 */
	public function body_class($class)
	{
		return 'body-class-semantic-theme';
	}

}

// Always instantiate the class so trigger get registered.
$theme_class = new Theme_class;

// ------------------------------------------------------------------------

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

		$alert =<<<END
<div class="alert alert-{type}">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	{message}
</div>
END;
		return str_replace(
			array('{type}', '{message}'),
			array($type, $message),
			$alert
		);
	}
}
