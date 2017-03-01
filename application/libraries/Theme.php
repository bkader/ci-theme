<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Theme Library
 * @package 	CodeIgniter\Designith
 * @category 	Libraries
 * @author 	Kader Bouyakoub <bkade@mail.com>
 * @link 	http://www.bkader.com/
 */
class Theme
{
	/**
	 * Instance of CI object
	 * @var 	object
	 */
	protected $CI;

	/**
	 * Configuration arra
	 * @var array
	 */
	protected $config = array(
		'theme'            => 'default',
		'layout'           => 'default',
		'title_sep'        => '-',
		'minify'           => FALSE,
		'cdn_enabled'      => FALSE,
		'cdn_server'       => NULL,
		'site_name'        => 'CodeIgniter',
		'site_description' => 'CodeIgniter Themes Library',
		'site_keywords'    => 'codeigniter, themes, libraries, bkader'
	);

	/**
	 * Current module's details
	 * @var 	string 	$module 		module's name if any
	 * @var 	string 	$controller 	controller's name
	 * @var 	string 	$method 		method's name
	 */
	protected $module     = NULL;
	protected $controller = NULL;
	protected $method     = NULL;

	/**
	 * Additional partial views
	 * @var 	array
	 */
	protected $partials = array();

	/**
	 * Page title, description, & keywords
	 */
	protected $title       = '';
	protected $description = '';
	protected $keywords    = '';

	/**
	 * Page's additional CSS, JS & meta tags
	 */
	protected $css_files = array();
	protected $js_files  = array();
	protected $metatags  = array();

	/**
	 * Array of variables to pass to view
	 * @var 	array
	 */
	protected $data = array();

	/**
	 * Constructor
	 */
	public function __construct($config = array())
	{

		// Prepare instance of CI object
		$this->CI =& get_instance();

		// We load the configuration file before this library's config
		if ($_config = $this->CI->config->item('theme')) 
		{
			$config = $_config;
			unset($_config);
		}

		// If the config does not exist, we load library's default config
		elseif (is_array($config) && isset($config['theme']))
		{
			$config = $config['theme'];
		}
		// Otherwise, we use our default config set above ($this->config)
		else
		{
			$config = $this->config;
		}
		
		// We loop through all settings and replaces our default config
		// (only if config is different from default one)
		if ($config != $this->config)
		{
			$this->config = array_replace_recursive($this->config, $config);
		}

		// Are site_name, site_description and site_keywords overriden?
		foreach (array('site_name', 'site_description', 'site_keywords') as $meta)
		{
			if ($_meta = $this->CI->config->item($meta))
			{
				$this->config[$meta] = $_meta;
			}
		}
		unset($meta, $_meta);

		// Prepare title separator
		$this->title_sep = ' '.trim($this->config['title_sep']).' ';

		// Make sure URL helper is load then we load our helper
		function_exists('base_url') OR $this->CI->load->helper('url');
		$this->CI->load->helper('theme');

		// Prepare current module's details
		if (method_exists($this->CI->router, 'fetch_module')) {
			$this->module = $this->CI->router->fetch_module();
		}
		$this->controller = $this->CI->router->fetch_class();
		$this->method     = $this->CI->router->fetch_method();

		// Set some useful variables
		$this->set(array(
			'site_name'   => $this->CI->config->item('site_name'),
			'site_url'    => $this->CI->config->site_url(),
			'base_url'    => $this->CI->config->base_url(),
			'current_url' => $this->CI->config->current_url(),
			'assets_url'  => $this->assets_url(),
			'uploads_url'  => $this->uploads_url(),
			'uri_string'  => $this->CI->uri->uri_string(),
		), NULL, TRUE);
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic __set
	 * @access 	public
	 * @param 	string 	$var 	property's name
	 * @param 	mixed 	$val 	property's value
	 * @return 	void
	 */
	public function __set($var, $val = NULL)
	{
		$this->$var = $val;
	}

	/**
	 * Magic __get
	 * @access 	public
	 * @param 	string 	$var 	property's name
	 * @return 	mixed 	property's value
	 */
	public function __get($var)
	{
		return isset($this->{$var}) ? $this->{$var} : NULL;
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets class properties
	 * @access 	public
	 * @param 	mixed 		$var 		property's name or associative array
	 * @param 	mixed 		$val 		property's value or NULL if $var is array
	 * @param 	boolean 	$global 	make property global or not
	 * @return 	instance of class
	 */
	public function set($var, $val = NULL, $global = FALSE)
	{
		if (is_array($val))
		{
			foreach($val as $key => $value)
			{
				$this->set($key, $value, $global);
			}
		}

		if ($global === TRUE)
		{
			$this->CI->load->vars($var, $val);
		}
		else
		{
			$this->data[$var] = $val;
		}

		return $this;
	}

	/**
	 * Returns a data store in class Config property
	 * @access 	public
	 * @param 	string 	$name
	 * @return 	mixed
	 */
	public function get($name)
	{
		return isset($this->config[$name]) ? $this->config[$name] : NULL;
	}

	// ------------------------------------------------------------------------
	// General Setters
	// ------------------------------------------------------------------------

	/**
	 * Changes page's title
	 * @access 	public
	 * @param 	mixed
	 * @return 	object
	 */
	public function title()
	{
		$title = $this->config['site_name'];
		if ( ! empty($args = func_get_args()))
		{
			is_array($args[0]) && $args = $args[0];
			$args[] = $title;
			$title = implode($this->title_sep, $args);
		}

		$this->title = $title;
		return $this;
	}

	/**
	 * Sets page description
	 * @access 	public
	 * @param 	string 	$description 	the description to user
	 * @return  instance of the class
	 */
	public function description($description = '')
	{
		$this->description = empty($description) 
							? $this->config['site_description'] 
							: $description;
		return $this;
	}

	/**
	 * Sets page keywords
	 * @access 	public
	 * @param 	string 	$keywords 	the keywords to user
	 * @return  instance of the class
	 */
	public function keywords($keywords = '')
	{
		$this->keywords = empty($keywords) 
							? $this->config['site_keywords'] 
							: $keywords;
		return $this;
	}

	// ------------------------------------------------------------------------
	// !HTML <meta> Tag
	// ------------------------------------------------------------------------

	/**
	 * Appends meta tags
	 * @access 	public
	 * @param 	mixed 	$name 	meta tag's name
	 * @param 	mixed 	$content
	 * @return 	object
	 */
    public function add_meta($name, $content = NULL)
    {
    	// In case of multiple elements
    	if (is_array($name)) {
    		foreach ($name as $key => $val) {
    			$this->add_meta($key, $val);
    		}

    		return $this;
    	}

    	$this->metatags[$name] = $content;
    	return $this;
    }

    /**
     * Display a HTML meta tag
     *
     * @access 	public
     *
     * @param   mixed   $name   string or associative array
     * @param   string  $value  value or NULL if $name is array
     * 
     * @return  string
     */
    public function meta($name, $content = NULL)
    {
        // Loop through multiple meta tags
        if (is_array($name)) {
            $meta = array();
            foreach ($name as $key => $val) {
                $meta[] = $this->meta($key, $val);
            }

            return implode("\t", $meta);
        }

        // Prepare name & content first
		$name    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        return (strpos($name, 'og:') !== FALSE)
                ? '<meta property="'.$name.'" content="'.$content.'">'."\n"
                : '<meta name="'.$name.'" content="'.$content.'">'."\n";
    }

    // ------------------------------------------------------------------------
    // !URLs: Assets and Uploads
    // ------------------------------------------------------------------------

	/**
	 * Returns the URL to assets folder
	 * @access 	public
	 * @param 	string 	$uri
	 * @param 	string 	$folder 	in case of distinct folder
	 * @return 	string
	 */
	public function assets_url($uri = '', $folder = NULL)
	{
		if (filter_var($uri, FILTER_VALIDATE_URL) !== FALSE)
		{
			return $uri;
		}

		// Does the Config class has the method method declared?
		if (method_exists($this->CI->config, 'assets_url'))
		{
			return $this->CI->config->assets_url($uri, $folder);
		}

		if (empty($folder))
		{
			$folder = 'themes/'.$this->config['theme'].'/assets/';
		}

		// $folder = ($folder ? $folder : 'assets').'/';
		return $this->CI->config->base_url('content/'.$folder.$uri);
	}

	/**
	 * Changes the folder to 'uploads' only
	 * @access 	public
	 * @param 	string 	$uri 	path to file
	 * @return 	string
	 */
	public function uploads_url($uri = '')
	{
		return $this->assets_url($uri, 'uploads');
	}

    // ------------------------------------------------------------------------

	/**
	 * pushes css files to the css_files array
	 * @access 	public
	 * @param 	mixed 	string|strings or array
	 * @return 	object
	 */
	public function add_css()
	{
		if ( ! empty($css = func_get_args()))
		{
			is_array($css[0]) && $css = $css[0];
			$this->_remove_extension($css, '.css');
			$this->css_files = array_merge($this->css_files, $css);
		}
		return $this;
	}

	/**
	 * This method removes the given css files from the loaded ones
	 * @access 	public
	 * @param 	mixed 	string|strings or array
	 * @return 	object
	 */
	public function remove_css()
	{
		if ( ! empty($css = func_get_args()))
		{
			is_array($css[0]) && $css = $css[0];
			$this->_remove_extension($css, '.css');
			$this->css_files = array_diff($this->css_files, $css);
		}
		return $this;
	}

	/**
	 * This methods uses remove() then add(), ass simple as that
	 * @access 	public
	 * @param 	string 	$old 	string
	 * @param 	string 	$new 	string
	 * @return 	object
	 */
	public function replace_css($old, $new)
	{
		foreach ($this->css_files as $index => $file)
		{
			if (strcmp($file, $old) === 0)
			{
				$this->css_files[$index] = $new;
			}
		}

		return $this;
	}

	/**
	 * Returns the array of loaded CSS files
	 * @access 	public
	 * @param 	none
	 * @return 	array
	 */
	public function get_css()
	{
		return $this->css_files;
	}

    /**
     * Returns the full url to css file
     * @param   string  $file   filename with or without .css extension
     * @return  string
     */
    public function css_url($file = NULL, $folder = NULL)
    {
    	// If a valid URL is passed, we simply return it
        if (filter_var($file, FILTER_VALIDATE_URL) !== FALSE) {

        	return $file;
        }

        if (strpos($file, '?') !== FALSE) {
            $args = explode('?', $file);
            $file = $args[0];
            $ver  = $args[1];
            unset($args);
        }
        isset($ver) OR $ver = '';

        $file = $this->_remove_extension($file).'.css';

        return $this->assets_url('css/'.$file.$ver, $folder);
    }

    /**
     * Returns the full css <link> tag
     * 
     * @param   string  $file   filename to load
     * @param   string  $cdn    to use in case of CDN user
     * @param   mixed   $attr   attributes to append to string
     * 
     * @return  string
     */
    public function css($file, $cdn = NULL, $attrs = '')
    {
    	// Only if a $file a requested
        if ($file) {

        	// Use the 2nd parameter if it's set & the CDN use is enabled.
            $this->cdn_enabled && $cdn !== NULL && $file = $cdn;

            // Return the full link tag
            return '<link rel="stylesheet" type="text/css" href="'.css_url($file).'"'._stringify_attributes($attrs).'>'."\n";
        }

        return NULL;
    }

	// ------------------------------------------------------------------------

	/**
	 * pushes js files to the js_files array
	 * @access 	public
	 * @param 	mixed 	string|strings or array
	 * @return 	object
	 */
	public function add_js()
	{
		if ( ! empty($js = func_get_args()))
		{
			is_array($js[0]) && $js = $js[0];
			$this->_remove_extension($js, '.js');
			$this->js_files = array_merge($this->js_files, $js);
		}
		return $this;
	}

	/**
	 * This method removes the given js files from the loaded ones
	 * @access 	public
	 * @param 	mixed 	string|strings or array
	 * @return 	object
	 */
	public function remove_js()
	{
		if ( ! empty($js = func_get_args()))
		{
			is_array($js[0]) && $js = $js[0];
			$this->_remove_extension($js, '.js');
			$this->js_files = array_diff($this->js_files, $js);
		}
		return $this;
	}

	/**
	 * This methods uses remove() then add(), ass simple as that
	 * @access 	public
	 * @param 	string 	$old 	string
	 * @param 	string 	$new 	string
	 * @return 	object
	 */
	public function replace_js($old, $new)
	{
		foreach ($this->js_files as $index => $file)
		{
			if (strcmp($file, $old) === 0)
			{
				$this->js_files[$index] = $new;
			}
		}

		return $this;
	}

	/**
	 * Returns the array of loaded JS files
	 * @access 	public
	 * @param 	none
	 * @return 	array
	 */
	public function get_js()
	{
		return $this->js_files;
	}

    /**
     * Returns the full url to js file
     * @param   string  $file   filename with or without .js extension
     * 
     * @return  string
     */
    public function js_url($file = NULL, $folder = NULL)
    {
    	// If a valid URL is passed, we simply return it
        if (filter_var($file, FILTER_VALIDATE_URL) !== FALSE) {

        	return $file;
        }

        if (strpos($file, '?') !== FALSE) {
            $args = explode('?', $file);
            $file = $args[0];
            $ver  = $args[1];
            unset($args);
        }
        isset($ver) OR $ver = '';
        $file = $this->_remove_extension($file, '.js').'.js';

        return $this->assets_url('js/'.$file.$ver, $folder);
    }

    /**
     * Returns the full js <link> tag
     * 
     * @param   string  $file   filename to load
     * @param   string  $cdn    to use in case of CDN user
     * @param   mixed   $attr   attributes to append to string
     * 
     * @return  string
     */
    public function js($file, $cdn = NULL, $attrs = '', $folder = NULL)
    {
    	// Only if a $file a requested
        if ($file)
        {
        	// Use the 2nd parameter if it's set & the CDN use is enabled.
            $this->cdn_enabled && $cdn !== NULL && $file = $cdn;
            return '<script type="text/javascript" src="'.$this->js_url($file, $folder).'"'._stringify_attributes($attrs).'></script>'."\n";
        }
        return NULL;
    }

    // ------------------------------------------------------------------------

    /**
     * Removes files extension
     * @access 	public
     * @param 	mixed 	string or array
     * @return 	mixed 	string or array
     */
    protected function _remove_extension($file, $ext = '.css')
    {
    	// In case of multiple items
    	if (is_array($file))
    	{
    		$file = array_map(function($f) use ($ext) {
    			$f = preg_replace('/'.$ext.'$/', '', $f);
    			return $f;
    		}, $file);
    	}
    	// In case of a single element
    	else
    	{
    		$file = preg_replace('/'.$ext.'$/', '', $file);
    	}

    	return $file;
    }

    // ------------------------------------------------------------------------

	/**
	 * Collect all additional CSS files and prepare them for output
	 * @access 	protected
	 * @param 	none
	 * @return 	string
	 */
	protected function _output_css()
	{
		$css = array();
		
		foreach ($this->css_files as $file) {
			if (is_array($file)) {
				$css[] = $this->css($file[0], $file[1]);
			}
			else {
				$css[] = $this->css($file);
			}
		}
		
		return implode("\t", $css);
	}

	/**
	 * Collect all additional JS files and prepare them for output
	 * @access 	protected
	 * @param 	none
	 * @return 	string
	 */
	protected function _output_js()
	{
		$js = array();
		
		foreach ($this->js_files as $file) {
			if (is_array($file)) {
				$js[] = $this->js($file[0], $file[1]);
			}
			else {
				$js[] = $this->js($file);
			}
		}
		
		return implode("\t", $js);
	}

	/**
	 * Collectes all additional metatags and prepare them for output
	 * 
	 * @access 	protected
	 * @param 	none
	 * 
	 * @return 	string
	 */
	protected function _output_meta()
	{
		return $this->meta($this->metatags);
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets page theme
	 * @access 	public
	 * @param 	string 	$theme 	theme's name
	 * @return 	object
	 */
	public function theme($theme = 'default')
	{
		$this->theme = $theme;
		return $this;
	}

	/**
	 * Sets page layout
	 * @access 	public
	 * @param 	string 	$layout 	layout's name
	 * @return 	object
	 */
	public function layout($layout = 'default')
	{
		$this->layout = $layout;
		return $this;
	}

	// ------------------------------------------------------------------------
	// !Partials Management
	// ------------------------------------------------------------------------

	/**
	 * Adds partial view
	 * @access 	public
	 * @param 	string 	$view 	view file to load
	 * @param 	array 	$data 	array of data to pass
	 * @param 	string 	$name 	name of the variable to use
	 */
	public function add_partial($view, $data = array(), $name = FALSE)
	{
		$name || $name = $view;
		$this->partials[$name] = $this->_load_file('partial', $view, $data, TRUE);
		return $this;
	}

	/**
	 * Removes given partial views
	 * @access 	public
	 * @param 	mixed
	 * @return 	object
	 */
	public function remove_partial()
	{
		if ( ! empty($args = func_get_args()))
		{
			is_array($args[0]) && $args = $args[0];
			$this->partials = array_diff($this->partials, $args);
		}

		return $this;
	}

	/**
	 * In case you want to replace an already-loaded partial
	 * @access 	public
	 * @param 	string 	$old 	old partial name
	 * @param 	string 	$new 	new partial name
	 * @return 	object
	 */
	public function replace_partial($old, $new)
	{
		foreach ($this->partials as $index => $file)
		{
			if (strcmp($file, $old) === 0)
			{
				$this->partials[$index] = $new;
			}
		}

		return $this;
	}

	/**
	 * Displays a partial view
	 * @access 	public
	 * @param 	string 	$view 	the partial view name
	 * @param 	array 	$data 	array of data to pass
	 * @param 	bool 	$return whether to return or output
	 * @return 	mixed
	 */
	public function partial($view, $data = array(), $return = FALSE)
	{
		return $this->_load_file('partial', $view, $data, $return);
	}

	// ------------------------------------------------------------------------
	// !Load a single view
	// ------------------------------------------------------------------------

	/**
	 * Displays a single view
	 * @access 	public
	 * @param 	string 	$view 	the view name
	 * @param 	array 	$data 	array of data to pass
	 * @param 	bool 	$return whether to return or output
	 * @return 	mixed
	 */
	public function view($view, $data = array(), $return = FALSE)
	{
		return $this->_load_file('view', $view, $data, $return);
	}

	// ------------------------------------------------------------------------

	/**
	 * Loads view file
	 * @access 	public
	 * @param 	string 	$view 		view to load
	 * @param 	array 	$data 		array of data to pass to view
	 * @param 	bool 	$return 	whether to output view or not
	 * @param 	string 	$master 	in case you use a distinct master view
	 * @return  void
	 */
	public function load($view, $data = array(), $return = FALSE, $master = 'template')
	{
		// Start beckmark
		$this->CI->benchmark->mark('theme_start');

		// Build the whole outout
		$output = $this->_build_theme_output($view, $data, $master);

		// Let CI do the caching instead of the browser
		$this->CI->output->cache($this->config['cache_lifetime']);

		// Stop benchmark
		$this->CI->benchmark->mark('theme_end');

		if ( ! $return)
		{
			$this->CI->output->set_output($output);
		}

		return $output;
	}

	/**
	 * This methods build everything and returns the final output
	 * 
	 * @access 	protected
	 * 
	 * @param 	string 	$view 	the view to load
	 * @param 	array 	$data 	array of data to pass to view
	 * @param 	string 	$master in case you want to use a distinct master view
	 *
	 * @return 	string
	 */
	protected function _build_theme_output($view, $data = array(), $master = 'template')
	{
		// Always set page title
		empty($this->title) && $this->title();

		// Always set page description HTML <meta>
		empty($this->description) && $this->description();
		$this->add_meta('description', $this->description);

		// Always set page keywords HTML <meta>
		empty($this->keywords) && $this->keywords();
		$this->add_meta('keywords', $this->keywords);

		// Put all together.
		$this->set(array(
			'title'       => $this->title,
			'meta'        => $this->_output_meta(),
			'css_files'   => $this->_output_css(),
			'js_files'    => $this->_output_js(),
		), NULL, TRUE);

		// Set page layout and put content in it
		$layout = array();

		// Add partial views only if requested
		if ( ! empty($this->partials)) {
			foreach ($this->partials as $key => $value) {
				$layout[$key] = $value;
			}
			unset($key, $value);
		}


		// Prepare view content
		$layout['content'] = $this->_load_file('view', $view, $data, TRUE);

		// Prepare layout content
		$this->set(array(
			'header' => $this->_load_file('partial', 'header', array(), TRUE),
			'footer' => $this->_load_file('partial', 'footer', array(), TRUE),
			'layout' => $this->_load_file('layout', $this->config['layout'], $layout, TRUE),
		), NULL, TRUE);
		unset($layout);

		// Prepare the output
		$output = $this->_load_file('template', $master, $this->data, TRUE);

		// Minify HTML output if set to TRE
		if (isset($this->config['minify']) && (bool) $this->config['minify'] === TRUE)
		{
			$output = $this->_minify_output($output);
		}

		return $output;
	}

	// ------------------------------------------------------------------------
	// !PROTECTED METHODS
	// ------------------------------------------------------------------------

	/**
	 * Load view files with locations depending on files types
	 * @access 	protected
	 * @param 	string 	$type 	type of view
	 * @param 	string 	$view 	the view file to load
	 * @param 	array 	$data 	array of data to pass to view file
	 * @param 	bool 	$return whether to output or simply return
	 * @return 	mixed
	 */
	protected function _load_file($type = 'view', $view = '', $data = array(), $return = FALSE)
	{
		switch ($type) {

			// In case of a view file
			case 'view':
			case 'views':

				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'modules', $this->module),
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'modules', $this->module, 'views'),
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'views'),
					build_path(APPPATH, 'modules', $this->module, 'views'),
					build_path(APPPATH, 'views'),
					build_path(VIEWPATH),
				);

				// remove uneccessary paths if $this->module is NULL
				if (empty($this->module))
				{
					unset($paths[0], $paths[1], $paths[3]);
				}

				// Remove unecessary paths if $this->config['theme'] is not set
				if ( ! isset($this->config['theme']) OR empty($this->config['theme']))
				{
					unset($paths[0], $paths[1], $paths[2]);
				}

				if ( ! empty($paths))
				{
					$found = FALSE;
					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$found = TRUE;
							$this->CI->load->vars($data);
							return $this->CI->load->file($path.$view.'.php', $return);
						}
					}

					if ($found !== TRUE) {
						show_error('The requested view file was not found in any of the following directories: <ul><li>'.implode('</li><li>', array_unique($paths)).'</li></ul>');
					}

					return NULL;
				}

				return NULL;
				break;

			// In case of a partial view
			case 'partial':
			case 'partials':
				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'modules', $this->module, 'views', 'partials'),
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'views', 'partials'),
					build_path(APPPATH, 'modules', $this->module, 'views', 'partials'),
					build_path(APPPATH, 'views', 'partials'),
					build_path(VIEWPATH, 'partials'),
				);

				// remove uneccessary paths if $this->module is NULL
				if (empty($this->module))
				{
					unset($paths[0], $paths[2]);
				}

				// Remove unecessary paths if $this->config['theme'] is not set
				if ( ! isset($this->config['theme']) OR empty($this->config['theme']))
				{
					unset($paths[0], $paths[1]);
				}

				if ( ! empty($paths))
				{
					$found = FALSE;
					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$this->CI->load->vars($data);
							return $this->CI->load->file($path.$view.'.php', $return);
						}
					}

					if ($found !== TRUE) {
						show_error('The requested partial file was not found in any of the following directories: <ul><li>'.implode('</li><li>', array_unique($paths)).'</li></ul>');
					}

					return NULL;
				}

				return NULL;
				break;

			// In case of a layout view
			case 'layout':
			case 'layouts':

				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'modules', $this->module, 'views', 'layouts'),
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'views', 'layouts'),
					build_path(APPPATH, 'modules', $this->module, 'views', 'layouts'),
					build_path(APPPATH, 'views', 'layouts'),
					build_path(VIEWPATH, 'layouts'),
				);

				// remove uneccessary paths if $this->module is NULL
				if (empty($this->module))
				{
					unset($paths[0], $paths[2]);
				}

				// Remove unecessary paths if $this->config['theme'] is not set
				if ( ! isset($this->config['theme']) OR empty($this->config['theme']))
				{
					unset($paths[0], $paths[1]);
				}

				if ( ! empty($paths))
				{
					$found = FALSE;
					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$found = TRUE;
							$this->CI->load->vars($data);
							return $this->CI->load->file($path.$view.'.php', $return);
						}
					}

					if ($found !== TRUE) {
						show_error('The requested layout file was not found in any of the following directories: <ul><li>'.implode('</li><li>', array_unique($paths)).'</li></ul>');
					}

					return NULL;
				}

				return NULL;
				break;

			// Load main theme file
			case 'main':
			case 'theme':
			case 'master':
			case 'template':
			default:

				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->config['theme'], 'modules', $this->module, 'views'),
					build_path(FCPATH, 'content', 'themes', $this->config['theme']),
					build_path(APPPATH, 'modules', $this->module, 'views'),
					build_path(APPPATH, 'views'),
					build_path(VIEWPATH),
				);

				// remove uneccessary paths if $this->module is NULL
				if (empty($this->module))
				{
					unset($paths[0], $paths[2]);
				}

				// Remove unecessary paths if $this->config['theme'] is not set
				if ( ! isset($this->config['theme']) OR empty($this->config['theme']))
				{
					unset($paths[0], $paths[1]);
				}

				if ( ! empty($paths))
				{
					$found = FALSE;
					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$found = TRUE;
							$this->CI->load->vars($data);
							return $this->CI->load->file($path.$view.'.php', $return);
						}
					}

					if ($found !== TRUE) {
						show_error('The requested master view was not found in any of the following directories: <ul><li>'.implode('</li><li>', array_unique($paths)).'</li></ul>');
					}

					return NULL;
				}

				return NULL;
				break;
		}
	}

	/**
	 * Compresses the HTML output
	 * @access 	protected
	 * @param 	string 	$output 	the html output to minify
	 * @return 	string 	the minified version of $output
	 */
	protected function _minify_output($output)
	{
		// Make sure $output is always a string
		is_string($output) OR $output = (string) $output;

		// In orders, we are searching for
		// 1. White-spaces after tags, except space.
		// 2. White-spaces before tags, except space.
		// 3. Multiple white-spaces sequences.
		// 4. HTML comments
		// 5. CDATA

		// We return the minified $output
		return preg_replace(array(
			'/\>[^\S ]+/s',
			'/[^\S ]+\</s',
			'/(\s)+/s',
			'/<!--(?!<!)[^\[>].*?-->/s',
			'#(?://)?<!\[CDATA\[(.*?)(?://)?\]\]>#s',
		), array(
			'>',
			'<',
			'\\1',
			'',
			"//&lt;![CDATA[\n".'\1'."\n//]]>"
		), $output);
	}
}

/* End of file Theme.php */
/* Location: ./application/libraries/Theme.php */