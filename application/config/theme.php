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
|  'theme.theme' 			the activated site theme.
|  'theme.master' 			theme default master view file.
|  'theme.layout' 			theme default layout file.
|  'theme.title_sep' 		string to be used as title separator.
|  'theme.compress' 		whether to compress HTML output to not.
|  'theme.cache_lifetime' 	whether to cache output or not.
|
| CDN settings:
|	'theme.cdn_enabled'		If true, the 2nd param of css(), js() is used.
|	'theme.cdn_server'		If your host your assets on a CDN, privide URL.
*/

// Site default theme
$config['theme']['theme'] = 'default';

// Site default master view file.
$config['theme']['master'] = 'default';

// Site default layout file
$config['theme']['layout'] = 'default';

// Site title separator
$config['theme']['title_sep'] = '&#150;';

// Minify HTML Output
$config['theme']['compress'] = (defined('ENVIRONMENT') && ENVIRONMENT == 'production');

// Cache life time
$config['theme']['cache_lifetime'] = 0;

// Enable CDN (to use 2nd argument of css() & js() functions)
$config['theme']['cdn_enabled'] = (defined('ENVIRONMENT') && ENVIRONMENT == 'production');

// The CDN URL if you host your files there
$config['theme']['cdn_server'] = ''; // i.e: 'http://static.myhost.com/';

// ------------------------------------------------------------------------
// Backup plan :D for site name, desription & keywords
// ------------------------------------------------------------------------

// Default site name, description and keywords.
$config['theme']['site_name']        = 'CI-Theme';
$config['theme']['site_description'] = 'Simply makes your CI-based applications themable. Easy and fun to use.';
$config['theme']['site_keywords']    = 'codeigniter, themes, libraries, bkader, bouyakoub';

/* End of file theme.php */
/* Location: ./application/config/theme.php */
