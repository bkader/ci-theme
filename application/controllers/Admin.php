<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Admin Module
 *
 * @package 	CodeIgniter\CI-Theme
 * @category 	Controllers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 */

// class Admin extends Admin_Controller
class Admin extends CI_Controller
{
	/**
	 * Class constructot
	 * Used only to load the library.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('theme');
		// Github buttons (Remove this please)
		$this->theme->add_js('https://buttons.github.io/buttons.js');
	}

	/**
	 * Admin panel homepage.
	 */
	public function index()
	{
		$this->theme->title('Admin Panel')
					->add_partial('header')
					->add_partial('footer')
					->add_partial('sidebar')
					->load('admin');
	}

	/**
	 * Admin panel using Semantic UI theme.
	 */
	public function semantic()
	{
 		$this->theme->theme('semantic')
					->add_partial('header')
					->add_partial('footer')
					->add_partial('sidebar')
 					->title('Admin Panel')
					->add_css('style')
					->add_js('scripts')
					->load('admin');
	}
}

/* End of file Admin.php */
/* Location: ./application/modules/admin/controllers/Admin.php */
