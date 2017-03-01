<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Theme Library Configuration
 *
 * This files holds theme settings
 *
 * @package 	CodeIgniter
 * @category 	Configuration
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

/*
| -------------------------------------------------------------------
| Theme Settings
| -------------------------------------------------------------------
|
|  'theme.theme' 		the activated site theme
|  'theme.layout' 		theme default layout file
|  'theme.title_sep' 	string to be used as title separator
|  'theme.minify' 		whether to compress HTML output to not
|
*/

// Site default theme
$config['theme']['theme'] = 'default';

// Site default layout file
$config['theme']['layout'] = 'default';

// Site title separator
$config['theme']['title_sep'] = '&#151;';

// Minify HTML Output
$config['theme']['minify'] = (defined('ENVIRONMENT') && ENVIRONMENT == 'production');

// Cache life time
$config['theme']['cache_lifetime'] = 0; // 5 minutes

// Enable CDN (to use 2nd argument or css() & js() functions)
$config['theme']['cdn_enabled'] = (defined('ENVIRONMENT') && ENVIRONMENT == 'production');

// The CDN URL if you host your files there
$config['theme']['cdn_server'] = NULL; // i.e: 'http://static.myhost.com/';

// ------------------------------------------------------------------------
// Backup plan :D for site name, desription & keywords
// ------------------------------------------------------------------------

// Default site name
$config['theme']['site_name']        = 'CI-Theme';
$config['theme']['site_description'] = 'CodeIgniter Themes Library';
$config['theme']['site_keywords']    = 'codeigniter, themes, libraries, bkader';

/* End of file theme.php */
/* Location: ./application/config/theme.php */