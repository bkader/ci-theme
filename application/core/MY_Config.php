<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extending CI_Config
 *
 * The reason behind this is to add a new method assets_url in order
 * to use the theme library and the assets handler controller.
 *
 * @package     CodeIgniter
 * @category    Core
 * @author  Kader Bouyakoub <bkader@mail.com>
 * @link    https://github.com/bkader
 * @link    https://twitter.com/KaderBouyakoub
 */

class MY_Config extends CI_Config
{
    /**
     * Constructor
     * @access  public
     * @param   none
     * @return  void
     */
    public function __construct()
    {
        // Prepare an instance of config array
        $this->config =& get_config();

        $_config = array();
        $config  = array();

        /* Load Theme Configration File */

        if (file_exists(APPPATH.'config/theme.php')) {
            require APPPATH.'config/theme.php';
            $_config = array_replace_recursive($_config, $config);
            $config = array();
        }

        if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/theme.php')) {
            require APPPATH.'config/'.ENVIRONMENT.'/theme.php';
            $_config = array_replace_recursive($_config, $config);
            $config = array();
        }

        $this->config = array_replace_recursive($this->config, $_config);
        unset($_config, $config);

        parent::__construct();
        log_message('info', 'MY_Config Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Replace CodeIgniter current_url() to include GET parameters as well
     *
     * @param   none
     * @return  string
     */
    public function current_url()
    {
        $url = $this->site_url(get_instance()->uri->uri_string());
        return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns Assets URL
     * @param   string  $uri        URI to append to URL
     * @param   string  $folder     In case of a distinct folder
     * @return  string
     */
    public function assets_url($uri = '', $folder = NULL)
    {
        // We build our assets_url. If it's a full URL, we return it as it is.
        // Otherwise, we build our URL.
        if (filter_var($uri, FILTER_VALIDATE_URL) !== FALSE)
        {
            return $uri;
        }

        // Using CDN?
        if ( ! empty($this->config['theme']['cdn_server']) 
            && $this->config['theme']['cdn_enabled'] === TRUE)
        {
            $assets_url = $this->config['theme']['cdn_server'];
        }
        else
        {
            $assets_url = $this->base_url();
        }

        // No folder is set? Use default
        if (empty($folder))
        {
            $folder = 'themes/'.$this->item('theme', 'theme').'/assets';
        }

        return rtrim($assets_url, '/').'/content/'.$folder.'/'.rtrim($uri, '/');
    }
}

/* End of file MY_Config.php */
/* Location: ./application/core/MY_Config.php */