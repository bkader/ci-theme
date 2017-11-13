<?php
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
		render('example', null, 'Semantic Theme', array(
			'css' => 'style',	// as string
			'js' => array('scritps') // as array for multiple files.
		));
	}
}
