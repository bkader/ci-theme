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
		$this->theme
				->title('Example')
				->add_css('style')
				->add_partial('header')
				->add_partial('sidebar')
				->add_partial('footer')
				->load('example');
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
				->add_css('style')
				->add_partial('header')
				->add_partial('sidebar')
				->add_partial('footer')
				->add_js('scripts')
				->load('example');
	}
}
