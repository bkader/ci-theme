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

class Example extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		// Make sure you remove this once you use the library.
		$this->config->set_item('base_url', 'http://localhost/ci-theme/');

		// You should probabely autoload it.
		$this->load->library('theme');

		// Github buttons (Remove this please)
		$this->theme->add_js('https://buttons.github.io/buttons.js');
	}

	public function index()
	{
		// These are used to add partial views.
		$this->theme
			->add_partial('header')
			->add_partial('sidebar')
			->add_partial('footer');
		
		// You can use this syntax here,
		// $this->theme->title('Example');
		// $this->theme->load('example');

		/**
		 * Or for the shorthand version.
		 * @see Theme.php:1057
		 */
		render('example', null, 'Example');
	}

	/**
	 * Example to use Semantic UI
	 *
	 * @return 	void
	 */
	public function semantic()
	{
		$this->theme
			->theme('semantic')
			->add_partial('header')
			->add_partial('sidebar')
			->add_partial('footer');

		// As the method above, you can use
		// either the long or the short version.
		// $this->theme
		// 	->title('Semantic Theme')
		// 	->add_css('style')
		// 	->add_js('scripts')
		// 	->load('example');

		/**
		 * Or for the shorthand version.
		 * @see Theme.php:1057
		 */
		render('example', null, 'Semantic Theme');
	}
}
