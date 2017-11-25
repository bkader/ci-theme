<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
		'master'           => 'default',
		'layout'           => 'default',
		'title_sep'        => '-',
		'compress'         => false,
		'cache_lifetime'   => 0,
		'cdn_enabled'      => false,
		'cdn_server'       => null,
		'site_name'       => 'CI-Theme',
		'site_description' => 'Simply makes your CI-based applications themable. Easy and fun to use.',
		'site_keywords'    => 'codeigniter, themes, libraries, bkader'
	);

	/**
	 * Page's additional CSS, JS & meta tags
	 */
	protected $css_files = array();
	protected $js_files  = array();
	protected $metadata  = array();

	/**
	 * Array of partial views to use.
	 * @var 	array
	 */
	protected $partials = array();

	/**
	 * Array of variables to pass to view
	 * @var 	array
	 */
	protected $data = array();

	/**
	 * Constructor
	 */
	public function __construct(array $config = array())
	{
		// Prepare instance of CI object
		$this->CI =& get_instance();

		$this->initialize($config);

		// Make sure URL helper is load then we load our helper
		(function_exists('base_url')) or $this->CI->load->helper('url');

		// Add site details to views.
		$this->set('lang_abbr', substr($this->CI->config->item('language'), 0, 2));
		$this->set('site_name', $this->site_name, true);
		$this->set('uri_string', uri_string(), true);

		// Prepare current module's details and pass them to view.
		// The reason we are doing this is so we can eventually set
		// our active uris using module, controller and/or method.

		if (method_exists($this->CI->router, 'fetch_module')) 
		{
			$this->module = $this->CI->router->fetch_module();
			$this->set('module', $this->module, true);
		}
		$this->controller = $this->CI->router->fetch_class();
		$this->method     = $this->CI->router->fetch_method();
		$this->set('controller', $this->controller, true);
		$this->set('method', $this->method, true);

		log_message('debug', 'Theme Class Initialized');
	}

	// ------------------------------------------------------------------------

	/**
	 * Initialize class preferences.
	 *
	 * @param 	array 	$config
	 * @return 	void
	 */
	public function initialize(array $config = array())
	{
		// Get rid of the full array.
		(is_array($config['theme'])) && $config = $config['theme'];

		// You can override the config set in theme.php by adding to
		// your custom config file.
		// 
		// UPDATE:
		// Instead of going through each of themes config, we simply
		// loop through the config and make everything overridable.

		foreach ($this->config as $key => $val)
		{
			if ($item = $this->CI->config->item($key))
			{
				$config[$key] = $item;
			}
		}
		unset($key, $val, $item);

		// Now we replace our default config.
		$this->config = array_merge($this->config, $config);

		$this->theme_path = FCPATH."content/themes/{$this->theme}/";
		
		// Is the manifest present?
		// Update: this is no longer important. If you have it
		// you have, if not, who cares? :D
		if (file_exists($this->theme_path.'/manifest.json'))
		{
			$manifest = file_get_contents($this->theme_path.'/manifest.json');
			$manifest = json_decode($manifest, true);
			if ( ! is_array($manifest))
			{
				show_error(
					'The "manifest.json" provided with your theme is not valid..',
					500,
					'Error Manifest.json'
				);
			}

			$this->config['manifest'] = $manifest;
		}

		// Does the theme have functions.php file?
		if (file_exists($this->theme_path.'functions.php'))
		{
			include_once($this->theme_path.'functions.php');
		}

		// Create class properties.
		foreach ($this->config as $key => $val)
		{
			// Just to add spaces before 
			// and after title separator.
			if ($key == 'title_sep')
			{
				$this->title_sep = ' '.trim($val).' ';
			}
			else
			{
				$this->{$key} = $val;
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic __set
	 * @access 	public
	 * @param 	string 	$var 	property's name
	 * @param 	mixed 	$val 	property's value
	 * @return 	void
	 */
	public function __set($var, $val = null)
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
		// Return a class property.
		if (isset($this->{$var}))
		{
			return $this->{$var};
		}

		// Return a $this->config array element.
		return $this->get($var);
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets variables to pass to view files.
	 * @access 	public
	 * @param 	mixed 		$var 		property's name or associative array
	 * @param 	mixed 		$val 		property's value or null if $var is array
	 * @param 	boolean 	$global 	make property global or not
	 * @return 	instance of class
	 */
	public function set($var, $val = null, $global = false)
	{
		if (is_array($var))
		{
			foreach($var as $key => $value)
			{
				$this->set($key, $value, $global);
			}

			return $this;
		}

		if ($global === true)
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
	 * @param 	string 	$index
	 * @return 	mixed
	 */
	public function get($name, $index = null)
	{
		if ($index === null)
		{
			if (isset($this->config[$name]))
			{
				return $this->config[$name];
			}

			// UPDATE
			// You now have the possiblity to even get element from
			// theme manifest, like author, description, website...

			if (isset($this->config['manifest']) && isset($this->config['manifest'][$name]))
			{
				return $this->config['manifest'][$name];
			}

			return null;
		}

		return isset($this->config[$index][$name]) ? $this->config[$index][$name] : null;
	}

	// ------------------------------------------------------------------------
	// General Setters
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
	 * Changes master view file.
	 * @access 	public
	 * @param 	string 	$master
	 * @return 	object
	 */
	public function master($master = 'default')
	{
		$this->master = $master;
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

	/**
	 * Changes page's title
	 * @access 	public
	 * @param 	mixed
	 * @return 	object
	 */
	public function title()
	{
		if ( ! empty($this->title))
		{
			return $this;
		}

		$this->title = $this->site_name;
		if ( ! empty($args = func_get_args()))
		{
			is_array($args[0]) && $args = $args[0];
			$args[] = $this->title;
			$this->title = implode($this->title_sep, $args);
		}
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
		if ( ! empty($this->description)) {
			return $this;
		}

		$this->description = $this->site_description;
		empty($description) or $this->description = $description;
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
		if ( ! empty($this->keywords)) {
			return $this;
		}

		$this->keywords = $this->site_keywords;
		empty($keywords) or $this->keywords = $keywords;
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
    public function add_meta($name, $content = null, $type = 'meta', $attrs = array())
    {
    	// In case of multiple elements
    	if (is_array($name))
    	{
    		foreach ($name as $key => $val) 
    		{
    			$this->add_meta($key, $val, $type, $attrs);
    		}

    		return $this;
    	}

    	$this->metadata[$type.'::'.$name] = array(
			'content' => $content,
			'attrs'   => $attrs,
    	);
    	return $this;
    }

    // public function append_meta($name, $content = null)

    /**
     * Display a HTML meta tag
     *
     * @access 	public
     *
     * @param   mixed   $name   string or associative array
     * @param   string  $value  value or null if $name is array
     * 
     * @return  string
     */
    public function meta($name, $content = null, $type = 'meta', $attrs = array())
    {
        // Loop through multiple meta tags
        if (is_array($name)) 
        {
            $meta = array();

            foreach ($name as $key => $val) 
            {
                $meta[] = $this->meta($key, $val, $type, $attrs);
            }

            return implode("\t", $meta);
        }

        $attributes = array();

        switch ($type)
        {
        	case 'rel':
        		$tag = 'link';
        		$attributes['rel'] = $name;
        		$attributes['href'] = $content;
        		break;
        	// In case of a meta tag.
        	case 'meta':
        	default:
		        if ($name == 'charset')
		        {
		        	return "<meta charset=\"{$content}\">\n\t";
		        }

		        if ($name == 'base')
		        {
		        	return "<base href=\"{$content}\">\n\t";
		        }

		        // The tag by default is "meta"
        		$tag = 'meta';
		        
		        // In case of using Open Graph tags, 
		        // we user 'property' instead of 'name'.
		        $type = (strpos($name, 'og:') !== false)? 'property': 'name';

        		if ($content === null)
        		{
        			$attributes[$type] = $name;
        		}
        		else
        		{
        			$attributes[$type] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        			$attributes['content'] = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        		}
        		break;
        }

        if (is_array($attrs))
        {
        	$attributes = _stringify_attributes(array_merge($attributes, $attrs));
        }
        else
        {
        	$attributes = _stringify_attributes($attributes).' '.$attrs;
        }

		return "<{$tag}{$attributes}>\n\t";
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
	public function assets_url($uri = '', $folder = null)
	{
		if (filter_var($uri, FILTER_VALIDATE_URL) !== false)
		{
			return $uri;
		}

		if (empty($folder))
		{
			$folder = "themes/{$this->theme}";
		}

		/**
		 * In case your files are hosting on another server (CDN), folder
		 * structure will be slightly different:
		 *
		 *	your_cdn.com/content/themes/_THEME_/css/file.css
		 *
		 * In case of locally hosted files, the URL would be:
		 * 	your_site.com/content/themes/_THEME_/assets/css/file.css
		 * 	
		 */
		if ($this->cdn_enabled && $this->cdn_server)
		{
			$assets_url = "{$this->cdn_server}content/{$folder}/{$uri}";
		}
		else
		{
			/**
			 * The reason I am using this if statement is to append or not
			 * 'assets' folder to the given $folder.
			 * Example:
			 * 	Using css('file') without the 4th argument will result in:
			 * 	<link ... src=".../content/themes/_THEME_/assets/css/file.css"
			 *
			 * But if you specify the 4th argument (distinct folder), you get:
			 * 	<link ... src=".../content/_FOLDER_/css/file.css"
			 * (without 'assets' being appended)
			 */
			if (strpos($folder, $this->theme) !== false)
			{
				$folder .= "/assets";
			}

			$assets_url = $this->CI->config->base_url("content/{$folder}/{$uri}");
		}

		return $assets_url;
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
			$css = $this->_remove_extension($css, '.css');
			$this->css_files = array_merge($this->css_files, $css);
		}

		return $this;
	}

	/**
	 * Does like the method above but instead of adding
	 * css files to the end, it adds them to the start.
	 * @access 	public
	 * @param 	mixed 	string|strings or array.
	 * @return 	object
	 */
	public function append_css()
	{
		if ( ! empty($css = func_get_args()))
		{
			is_array($css[0]) && $css = $css[0];
			$css = $this->_remove_extension($css, '.css');
			$this->css_files = array_merge($css, $this->css_files);
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
			$css = $this->_remove_extension($css, '.css');
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
		// Always remove extension:
		$old = $this->_remove_extension($old);
		$new = $this->_remove_extension($new);

		foreach ($this->css_files as $i => $css)
		{
			if ($old == $css)
			{
				$this->css_files[$i] = $new;
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
    public function css_url($file = null, $folder = null)
    {
    	// If a valid URL is passed, we simply return it
        if (filter_var($file, FILTER_VALIDATE_URL) !== false) 
        {
        	return $this->_remove_extension($file, '.css').'.css';
        }

        $ver = '';
        if (strpos($file, '?') !== false) 
        {
            $args = explode('?', $file);
            $file = $args[0];
            $ver  = '?'.$args[1];
        }

        $file = $this->_remove_extension($file).'.css';
        return $this->assets_url("css/{$file}{$ver}", $folder);
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
    public function css($file, $cdn = null, $attrs = '', $folder = null)
    {
    	// Only if a $file a requested
        if ($file) 
        {
        	// Use the 2nd parameter if it's set & the CDN use is enabled.
            $this->cdn_enabled && $cdn !== null && $file = $cdn;

            // Return the full link tag
            return '<link rel="stylesheet" type="text/css" href="'.$this->css_url($file, $folder).'"'._stringify_attributes($attrs).'>'."\n";
        }

        return null;
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
			$js = $this->_remove_extension($js, '.js');
			$this->js_files = array_merge($this->js_files, $js);
		}

		return $this;
	}

	/**
	 * Does like the method above but instead of adding
	 * js files to the end, it adds them to the start.
	 * @access 	public
	 * @param 	mixed 	string|strings or array.
	 * @return 	object
	 */
	public function append_js()
	{
		if ( ! empty($js = func_get_args()))
		{
			is_array($js[0]) && $js = $js[0];
			$js = $this->_remove_extension($js, '.js');
			$this->js_files = array_merge($js, $this->js_files);
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
			$js = $this->_remove_extension($js, '.js');
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
		// Always remove extension:
		$old = $this->_remove_extension($old);
		$new = $this->_remove_extension($new);

		foreach ($this->js_files as $i => $js)
		{
			if ($old == $js)
			{
				$this->js_files[$i] = $new;
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
    public function js_url($file = null, $folder = null)
    {
    	// If a valid URL is passed, we simply return it
        if (filter_var($file, FILTER_VALIDATE_URL) !== false) 
        {

        	return $this->_remove_extension($file, '.js').'.js';
        }

        $ver = '';
        if (strpos($file, '?') !== false) 
        {
            $args = explode('?', $file);
            $file = $args[0];
            $ver  = '?'.$args[1];
        }
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
    public function js($file, $cdn = null, $attrs = '', $folder = null)
    {
    	// Only if a $file a requested
        if ($file)
        {
        	// Use the 2nd parameter if it's set & the CDN use is enabled.
            $this->cdn_enabled && $cdn !== null && $file = $cdn;
            return '<script type="text/javascript" src="'.$this->js_url($file, $folder).'"'._stringify_attributes($attrs).'></script>'."\n";
        }
        return null;
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
	public function add_partial($view, $data = array(), $name = null)
	{
		// If $name is not set, we take the last string.
		empty($name) && $name = basename($view);
		$this->partials[$name] = $this->_load_file('partial', rtrim($view, '/'), $data, true);
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
			foreach ($args as $partial)
			{
				unset($this->partials[$partial]);
			}
		}

		return $this;
	}

	/**
	 * In case you want to replace an already-loaded partial.
	 * If the partial does not exist, it will simply add it.
	 * @access 	public
	 * @param 	string 	$old 	old partial name
	 * @param 	string 	$new 	new partial name
	 * @param 	array 	$data 	data to pass to the new view
	 * @return 	object
	 */
	public function replace_partial($old, $new, $data = array())
	{
		return $this->add_partial($new, $data, $old);
	}

	/**
	 * Displays a partial view alone.
	 * @access 	public
	 * @param 	string 	$view 	the partial view name
	 * @param 	array 	$data 	array of data to pass
	 * @param 	bool 	$return whether to return or output
	 * @return 	mixed
	 */
	public function partial($view, $data = array(), $return = false)
	{
		return $this->_load_file('partial', $view, $data, $return);
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
		
		foreach ($this->css_files as $file) 
		{
			// In case of an array, the first element is the local file
			// while the second shoud be the CDN served file.
			if (is_array($file)) 
			{
				$css[] = $this->css($file[0], $file[1]);
			}
			else 
			{
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
		
		foreach ($this->js_files as $file) 
		{
			// In case of an array, the first element is the local file
			// while the second shoud be the CDN served file.
			if (is_array($file)) 
			{
				$js[] = $this->js($file[0], $file[1]);
			}
			else 
			{
				$js[] = $this->js($file);
			}
		}
		
		return implode("\t", $js);
	}

	/**
	 * Collectes all additional metadata and prepare them for output
	 * 
	 * @access 	protected
	 * @param 	none
	 * 
	 * @return 	string
	 */
	protected function _output_meta()
	{
		$output = '';

		if ( ! empty($this->metadata))
		{
			foreach($this->metadata as $key => $val)
			{
				list($type, $name) = explode('::', $key);
				$content = isset($val['content'])? $val['content']: null;
				$attrs = isset($val['attrs'])? $val['attrs']: null;
				$output .= $this->meta($name, $content, $type, $attrs);
			}
		}

		return $output;
	}

	// ------------------------------------------------------------------------
	// !Load a single view
	// ------------------------------------------------------------------------

	/**
	 * Displays a single view alone.
	 * 
	 * @access 	public
	 * @param 	string 	$view 	the view name
	 * @param 	array 	$data 	array of data to pass
	 * @param 	bool 	$return whether to return or output
	 * @return 	mixed
	 */
	public function view($view, $data = array(), $return = false)
	{
		return $this->_load_file('view', $view, $data, $return);
	}

	/**
	 * Loads view file
	 * @access 	public
	 * @param 	string 	$view 		view to load
	 * @param 	array 	$data 		array of data to pass to view
	 * @param 	bool 	$return 	whether to output view or not
	 * @param 	string 	$master 	in case you use a distinct master view
	 * @return  void
	 */
	public function load($view, $data = array(), $return = false, $master = 'default')
	{
		// Start beckmark
		$this->CI->benchmark->mark('theme_start');

		// Build the whole outout
		$output = $this->_build_theme_output($view, $data, $master);

		// Let CI do the caching instead of the browser
		$this->CI->output->cache($this->config['cache_lifetime']);

		// Stop benchmark
		$this->CI->benchmark->mark('theme_end');

		// Pass elapsed time to views.
		if ($this->CI->output->parse_exec_vars === true)
		{
			$output = str_replace(
				'{theme_time}',
				$this->CI->benchmark->elapsed_time('theme_start', 'theme_end'),
				$output
			);
		}

		if ($return)
		{
			return $output;
		}

		$this->CI->output->set_output($output);
	}

	/**
	 * Instead of chaining this class methods or calling them one by one,
	 * this method is a shortcut to do anything you want in a single call.
	 * @access 	public
	 * @param 	string 	$view 		the view file to load
	 * @param 	array 	$data 		array of data to pass to view
	 * @param 	string 	$title 		page's title
	 * @param 	string 	$options 	associative array of options to apply first
	 * @param 	bool 	$return 	whether to output or simply build
	 * NOTE: you can pass $options instead of $title like so:
	 * 		$this->theme->render('view', $data, $options, $return);
	 */
	public function render($view, $data = array(), $title = null, $options = array(), $return = false)
	{
		// In case $title is an array, it will be used as $options.
		// If then $options is a boolean, it will be used for $return.
		if (is_array($title))
		{
			$return  = (bool) $options;
			$options = $title;
			$title   = null;
		}

		// If $title is not empty we add it to $options.
		empty($title) or $options['title'] = $title;

		// Loop through all options now.
		foreach ($options as $key => $val)
		{
			// add_css and add_js are the only distinct methods.
			if (in_array($key, array('css', 'js')))
			{
				$this->{'add_'.$key}($val);
			}

			// We call the method only if it exists.
			elseif (method_exists($this, $key))
			{
				call_user_func_array(array($this, $key), (array) $val);
			}

			// Otherwise we set variables to views.
			else
			{
				$this->set($key, $val, true);
			}
		}

		// Now we render the final output.
		return $this->load($view, $data, $return);
	}

	/**
	 * Unlike the method above it, this one builts the output and does not
	 * display it. You would have to echo it.
	 * @access 	public
	 * @param 	string 	$view 		the view file to load
	 * @param 	array 	$data 		array of data to pass to view
	 * @param 	string 	$title 		page's title
	 * @param 	string 	$options 	associative array of options to apply first
	 * NOTE: you can pass $options instead of $title like so:
	 * 		$this->theme->render('view', $data, $options);
	 */
	public function build($view, $data = array(), $title = null, $options = array())
	{
		return $this->render($view, $data, $title, $options, true);
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
	protected function _build_theme_output($view, $data = array())
	{
		// Always set page title
		empty($this->title) && $this->title();

		// Always set page description and keywords HTML <meta>
		empty($this->description) && $this->description();
		empty($this->keywords) && $this->keywords();

		// Update new metadata
		$this->add_meta(array(
			'description' => $this->description,
			'keywords' => $this->keywords,
		));

		// Put all together.
		$this->set(array(
			'title'     => $this->title,
			'metadata'  => $this->_output_meta(),
			'css_files' => $this->_output_css(),
			'js_files'  => $this->_output_js(),
		));

		// Set page layout and put content in it
		$layout = array();

		// Add partial views only if requested
		if ( ! empty($this->partials)) 
		{
			foreach ($this->partials as $key => $value) 
			{
				$layout[$key] = $value;
			}
			unset($key, $value);
		}

		// Prepare view content
		$layout['content'] = $this->_load_file('view', $view, $data, true);

		// Prepare layout content
		$this->set('layout', $this->_load_file('layout', $this->layout, $layout, true));

		// Prepare the output
		$output = $this->_load_file('default', $this->master, $this->data, true);

		// Minify HTML output if set to TRE
		if ($this->compress === true)
		{
			$output = $this->_compress_output($output);
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
	protected function _load_file($type = 'view', $view = '', $data = array(), $return = false)
	{
		switch ($type) {

			// In case of a view file
			case 'view':
			case 'views':

				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views', '_modules', $this->module),
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views'),
					build_path(APPPATH, 'modules', $this->module, 'views'),
					build_path(APPPATH, 'views'),
					build_path(VIEWPATH),
				);

				// remove uneccessary paths if $this->module is null
				if (empty($this->module))
				{
					unset($paths[0], $paths[2]);
				}

				// Remove unecessary paths if $this->theme is not set
				if ( ! isset($this->theme) or empty($this->theme))
				{
					unset($paths[0], $paths[1]);
				}

				if ( ! empty($paths))
				{
					$found  = false;
					$output = '';

					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$found = true;
							$this->CI->load->vars($data);
							$output = $this->CI->load->file($path.$view.'.php', $return);
							break;
						}
					}

					if ($found !== true)
					{
						show_error("The requested view file '{$view}' could not be found in any of these folders: <ul><li>".implode("</li><li>", array_unique($paths))."</li></ul>");
					}

					return $output;
				}

			break;

			// In case of a partial view
			case 'partial':
			case 'partials':
				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views', '_modules', $this->module, '_partials'),
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views', '_partials'),
					build_path(APPPATH, 'modules', $this->module, 'views', '_partials'),
					build_path(APPPATH, 'views', '_partials'),
					build_path(VIEWPATH, '_partials'),
				);

				// remove uneccessary paths if $this->module is null
				if (empty($this->module))
				{
					unset($paths[0], $paths[2]);
				}

				// Remove unecessary paths if $this->theme is not set
				if ( ! isset($this->theme) or empty($this->theme))
				{
					unset($paths[0], $paths[1]);
				}

				if ( ! empty($paths))
				{
					$found  = false;
					$output = '';

					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$found = true;
							$this->CI->load->vars($data);
							$output = $this->CI->load->file($path.$view.'.php', $return);
							break;
						}
					}

					if ($found !== true)
					{
						show_error("The requested partial file '{$view}' could not be found in any of these folders: <ul><li>".implode("</li><li>", array_unique($paths))."</li></ul>");
					}

					return $output;
				}

			break;

			// In case of a layout view
			case 'layout':
			case 'layouts':

				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views', '_modules', $this->module, '_layouts'),
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views', '_layouts'),
					build_path(APPPATH, 'modules', $this->module, 'views', '_layouts'),
					build_path(APPPATH, 'views', '_layouts'),
					build_path(VIEWPATH, '_layouts'),
				);

				// remove uneccessary paths if $this->module is null
				if (empty($this->module))
				{
					unset($paths[0], $paths[2]);
				}

				// Remove unecessary paths if $this->theme is not set
				if ( ! isset($this->theme) or empty($this->theme))
				{
					unset($paths[0], $paths[1]);
				}

				if ( ! empty($paths))
				{
					$found  = false;
					$output = '';

					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$found = true;
							$this->CI->load->vars($data);
							$output = $this->CI->load->file($path.$view.'.php', $return);
							break;
						}
					}

					if ($found !== true)
					{
						show_error("The requested layout file '{$view}' could not be found in any of these folders: <ul><li>".implode("</li><li>", array_unique($paths))."</li></ul>");
					}

					return $output;
				}
			
			break;

			// Load main theme file
			case 'main':
			case 'theme':
			case 'master':
			case 'template':
			default:

				// prepare all path
				$paths = array(
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views', '_modules', $this->module, '_master'),
					build_path(FCPATH, 'content', 'themes', $this->theme, 'views', '_master'),
					build_path(APPPATH, 'modules', $this->module, 'views', '_master'),
					build_path(APPPATH, 'views', '_master'),
					build_path(VIEWPATH, '_master'),
				);

				// remove uneccessary paths if $this->module is null
				if (empty($this->module))
				{
					unset($paths[0], $paths[2]);
				}

				// Remove unecessary paths if $this->theme is not set
				if ( ! isset($this->theme) or empty($this->theme))
				{
					unset($paths[0], $paths[1]);
				}

				if ( ! empty($paths))
				{
					$found  = false;
					$output = '';

					foreach (array_unique($paths) as $path)
					{
						if (file_exists($path.$view.'.php'))
						{
							$found = true;
							$this->CI->load->vars($data);
							$output = $this->CI->load->file($path.$view.'.php', $return);
							break;
						}
					}

					if ($found !== true)
					{
						show_error("The requested master view '{$view}' could not be found in any of these folders: <ul><li>".implode("</li><li>", array_unique($paths))."</li></ul>");
					}

					return $output;
				}

			break;
		}
	}

	/**
	 * Compresses the HTML output
	 * @access 	protected
	 * @param 	string 	$output 	the html output to compress
	 * @return 	string 	the minified version of $output
	 */
	protected function _compress_output($output)
	{
		// Make sure $output is always a string
		is_string($output) or $output = (string) $output;

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

// ------------------------------------------------------------------------
// Remove from theme_helper.
// ------------------------------------------------------------------------

if ( ! function_exists('build_path'))
{
    /**
     * This function smartly builds a path using DIRECTORY_SEPARATOR
     *
     * @param   mixed   strings or array
     * @return  string  the full path built
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    http://www.bkader.com/
     */
    function build_path()
    {
        // We build the path only if arguments are passed
        if ( ! empty($args = func_get_args()))
        {
            // Make sure arguments are an array but not a mutidimensional one
            isset($args[0]) && is_array($args[0]) && $args = $args[0];

            return implode(DIRECTORY_SEPARATOR, array_map('rtrim', $args, array(DIRECTORY_SEPARATOR))).DIRECTORY_SEPARATOR;
        }

        return null;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('assets_url'))
{
    /**
     * Returns Assets URL
     * @param   string  $uri        URI to append to URL
     * @param   string  $folder     In case of a distinct folder
     * @return  string
     */
    function assets_url($uri = '', $folder = null)
    {
        return get_instance()->theme->assets_url($uri, $folder);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('css_url'))
{
    /**
     * Returns the full url to css file
     * @param   string  $file   filename with or without .css extension
     * @return  string
     */
    function css_url($file = null, $folder = null)
    {
        return get_instance()->theme->css_url($file, $folder);
    }
}

if ( ! function_exists('css'))
{
    /**
     * Returns the full css <link> tag
     * @param   string  $file   filename to load
     * @param   string  $cdn    to use in case of CDN user
     * @param   mixed   $attr   attributes to append to string
     * @return  string
     */
    function css($file = null, $cdn = null, $attrs = '', $folder = null)
    {
        return get_instance()->theme->css($file, $cdn, $attrs, $folder);
    }
}

// ----------------------------------------------------------------------------

if ( ! function_exists('js_url'))
{
    /**
     * Returns the full url to js file
     * @param   string  $file   filename with or without .js extension
     * @return  string
     */
    function js_url($file, $folder = null)
    {
        return get_instance()->theme->js_url($file, $folder);
    }
}

if ( ! function_exists('js'))
{
    /**
     * Returns the full JS <script> tag
     * @param   string  $file   filename to load
     * @param   string  $cdn    to use in case of CDN user
     * @param   mixed   $attr   attributes to append to string
     * @return  string
     */
    function js($file = null, $cdn = null, $attrs = '', $folder = null)
    {
        return get_instance()->theme->js($file, $cdn, $attrs, $folder);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('meta'))
{
    /**
     * Display a HTML meta tag
     *
     * @param   mixed   $name   string or associative array
     * @param   string  $content  content or null if $name is array
     * @return  string
     */
    function meta($name, $content = null, $type = 'meta', $attrs = array())
    {
        return get_instance()->theme->meta($name, $content, $type, $attrs);
    }
}

// ----------------------------------------------------------------------------

if ( ! function_exists('img_url'))
{
    /**
     * Returns the full url to image file
     * @param   string  $file       image name
     * @param   string  $folder     in case of a distinct folder
     * @return  string
     */
    function img_url($file = null, $folder = null)
    {
        if (filter_var($file, FILTER_VALIDATE_URL) !== false)
        {
            return $file;
        }
        $file = ($folder !== null) ? $file : 'img/'.$file;
        return assets_url($file, $folder);
    }
}

if ( ! function_exists('img'))
{
    /**
     * Returns a full tag to image
     * @param   string  $file       image name
     * @param   mixed   $attrs      attributes to append
     * @param   string  $folder     in case of a distinct folder
     * @return  string
     */
    function img($file = null, $attrs = '', $folder = null)
    {
        if (strlen($file) > 0)
        {
            return '<img src="'.img_url($file, $folder).'"'._stringify_attributes($attrs).' />';
        }
        return null;
    }
}

if ( ! function_exists('img_alt')) 
{
    /**
     * Displays an alternative image using placehold.it website.
     *
     * @return  string
     */
    function img_alt($width, $height = null, $text = null, $background = null, $foreground = null)
    {
        $params = array();

        if (is_array($width))
        {
            $params = $width;
        }
        else
        {
            $params['width']        = $width;
            $params['height']       = $height;
            $params['text']         = $text;
            $params['background']   = $background;
            $params['foreground']   = $foreground;
        }

        $params['height']       = (empty($params['height'])) ? $params['width'] : $params['height'];
        $params['text']         = (empty($params['text'])) ? $params['width'].' x '. $params['height'] : $params['text'];
        $params['background']   = (empty($params['background'])) ? 'CCCCCC' : $params['height'];
        $params['foreground']   = (empty($params['foreground'])) ? '969696' : $params['foreground'];

        return '<img src="http://placehold.it/'. $params['width'].'x'. $params['height'].'/'.$params['background'].'/'.$params['foreground'].'&text='. $params['text'].'" alt="Placeholder">';
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('theme_load'))
{
    /**
     * Quick access to Theme::load() method.
     * 
     * @param   string  $view       view to load
     * @param   array   $data       array of data to pass to view
     * @param   bool    $return     whether to output view or not
     * @param   string  $master     in case you use a distinct master view
     * @return  void
     */
    function theme_load($view, $data = array(), $return = false, $master = 'default')
    {
        return get_instance()->theme->load($view, $data, $return, $master);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('theme_render'))
{
	/**
	 * Instead of chaining this class methods or calling them one by one,
	 * this method is a shortcut to do anything you want in a single call.
	 * @access 	public
	 * @param 	string 	$view 		the view file to load
	 * @param 	array 	$data 		array of data to pass to view
	 * @param 	string 	$title 		page's title
	 * @param 	string 	$options 	associative array of options to apply first
	 * @param 	bool 	$return 	whether to output or simply build
	 * NOTE: you can pass $options instead of $title like so:
	 * 		$this->theme->render('view', $data, $options, $return);
	 */
	function theme_render($view, $data = array(), $title = null, $options = array(), $return = false)
	{
        return get_instance()->theme->render($view, $data, $title, $options, $return);
	}
}

if ( ! function_exists('render'))
{
	// alias of the function above.
	function render($view, $data = array(), $title = null, $options = array(), $return = false)
	{
        return get_instance()->theme->render($view, $data, $title, $options, $return);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('theme_view'))
{
    /**
     * Displays a single view alone.
     * 
     * @param   string  $view   the view name
     * @param   array   $data   array of data to pass
     * @param   bool    $return whether to return or output
     * @return  mixed
     */
    function theme_view($view, $data = array(), $return = false)
    {
        return get_instance()->theme->view($view, $data, $return);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('theme_partial'))
{
    /**
     * Insead of using Theme::partial() method, your can use this helper
     * function to quick load a partial view.
     *
     * @param   string  $view       the view file to load.
     * @param   array   $data       array of data to pass to view.
     * @param   bool    $return     whether to return it or not.
     * @return  mixed
     */
    function theme_partial($view, $data = array(), $return = false)
    {
        return get_instance()->theme->partial($view, $data, $return);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('theme_header'))
{
    /**
     * Loads partial view named 'header'. This can also been done using
     * theme_partial() function: theme_partial('header', $data, $return)
     *
     * @param   array   $data       array of data to pass to the view.
     * @param   bool    $return     whether to return it or not.
     * @return  mixed
     */
    function theme_header($data = array(), $return = false)
    {
        return theme_partial('header', $data, $return);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('theme_footer'))
{
    /**
     * Loads partial view named 'footer'. This can also been done using
     * theme_partial() function: theme_partial('footer', $data, $return)
     *
     * @param   array   $data   array of data to pass to the view.
     * @param   bool    $return     whether to return it or not.
     * @return  mixed
     */
    function theme_footer($data = array(), $return = false)
    {
        return theme_partial('footer', $data, $return);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('print_alert'))
{
    /**
     * Prints an alert.
     *
     * @param   string  $message    the message to print.
     * @param   string  $type       type of the message.
     * @param   string  $view       by default 'alert' but can be overriden.
     * @return  string
     */
    function print_alert($message = '', $type = 'info', $view = 'alert')
    {
        if (empty($message))
        {
            return null;
        }

        return theme_partial($view, array(
            'type' => $type, 
            'message' => $message
        ), true);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_alert'))
{
    /**
     * Sets a session's flash message.
     *
     * Multiple messages can be set if $message is an array like so:
     * array(
     *     'success' => '...',
     *     'danger'  => '...',
     *     ...
     * );
     * 
     * @param   mixed  $message    message to store
     * @param   string  $type       type of the message
     * @return  void
     */
    function set_alert($message = '', $type = 'info')
    {
        // If not message is set, nothing to do.
        if (empty($message))
        {
            return false;
        }

        if (is_array($message))
        {
            foreach ($message as $_type => $_message)
            {
                $messages[] = array('type' => $_type, 'message' => $_message);
            }
        }
        else
        {
            $messages[] = array('type' => $type, 'message' => $message);
        }

        // Make sure the session library is loaded
        if ( ! class_exists('CI_Session', false))
        {
            get_instance()->load->library('session');
        }
        
        // Set flashdata.
        get_instance()->session->set_flashdata('__ci_flash', $messages);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('print_flash_alert'))
{
    /**
     * Prints already stored flashdata messages.
     *
     * @param   string  $view   overrides alert.php view file.
     * @return  string
     */
    function print_flash_alert($view = 'alert')
    {
        if (isset($_SESSION['__ci_flash']) && is_array($_SESSION['__ci_flash']))
        {
            $output = '';

            foreach ($_SESSION['__ci_flash'] as $message)
            {
                $output .= print_alert($message['message'], $message['type'], $view);
            }

            return $output;
        }

        return null;
    }
}

/* End of file Theme.php */
/* Location: ./application/libraries/Theme.php */
