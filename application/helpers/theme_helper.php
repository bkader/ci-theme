<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Theme Helper
 *
 * This helper goes with Theme library provided as a package for CI-Base
 *
 * @package     CodeIgniter
 * @category    Helpers
 * @author  Kader Bouyakoub <bkader@mail.com>
 * @link    http://www.bkader.com/
 */

if ( ! function_exists('build_path')) {
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

        return NULL;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('assets_url')) {
    /**
     * Returns Assets URL
     * @param   string  $uri        URI to append to URL
     * @param   string  $folder     In case of a distinct folder
     * @return  string
     */
    function assets_url($uri = '', $folder = NULL)
    {
        return get_instance()->theme->assets_url($uri, $folder);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('css_url')) {
    /**
     * Returns the full url to css file
     * @param   string  $file   filename with or without .css extension
     * @return  string
     */
    function css_url($file = NULL, $folder = NULL)
    {
        return get_instance()->theme->css_url($file, $folder);
    }
}

if ( ! function_exists('css')) {
    /**
     * Returns the full css <link> tag
     * @param   string  $file   filename to load
     * @param   string  $cdn    to use in case of CDN user
     * @param   mixed   $attr   attributes to append to string
     * @return  string
     */
    function css($file = NULL, $cdn = NULL, $attrs = '')
    {
        return get_instance()->theme->css($file, $cdn, $attrs);
    }
}

// ----------------------------------------------------------------------------

if ( ! function_exists('js_url')) {
    /**
     * Returns the full url to js file
     * @param   string  $file   filename with or without .js extension
     * @return  string
     */
    function js_url($file, $folder = NULL)
    {
        return get_instance()->theme->js_url($file, $folder);
    }
}

if ( ! function_exists('js')) {
    /**
     * Returns the full JS <script> tag
     * @param   string  $file   filename to load
     * @param   string  $cdn    to use in case of CDN user
     * @param   mixed   $attr   attributes to append to string
     * @return  string
     */
    function js($file = NULL, $cdn = NULL, $attrs = '', $folder = NULL)
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
     * @param   string  $value  value or NULL if $name is array
     * @return  string
     */
    function meta($name, $value = NULL)
    {
        return get_instance()->theme->meta($name, $value);
    }
}

// ----------------------------------------------------------------------------

if ( ! function_exists('img_url')) {
    /**
     * Returns the full url to image file
     * @param   string  $file       image name
     * @param   string  $folder     in case of a distinct folder
     * @return  string
     */
    function img_url($file = NULL, $folder = NULL)
    {
        if (filter_var($file, FILTER_VALIDATE_URL) !== FALSE) {
            return $file;
        }
        $file = ($folder !== NULL) ? $file : 'img/'.$file;
        return assets_url($file, $folder);
    }
}

if ( ! function_exists('img')) {
    /**
     * Returns a full tag to image
     * @param   string  $file       image name
     * @param   mixed   $attrs      attributes to append
     * @param   string  $folder     in case of a distinct folder
     * @return  string
     */
    function img($file = NULL, $attrs = '', $folder = NULL)
    {
        if (strlen($file) > 0) {
            return '<img src="'.img_url($file, $folder).'"'._stringify_attributes($attrs).' />';
        }
        return NULL;
    }
}

if ( ! function_exists('img_alt')) {
    function img_alt($width, $height = NULL, $text = NULL, $background = NULL, $foreground = NULL)
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

if ( ! function_exists('set_alert'))
{
    /**
     * Sets a session's flash message
     * @param   string  $msg    message to store
     * @param   string  $type   type of the message
     * @return  void
     */
    function set_alert($msg = '', $type = 'info')
    {
        $CI =& get_instance();
        // Make sure the session library is loaded
        class_exists('CI_Session') OR $CI->load->library('session');
        // Make sure alert type is not unknown
        $type === 'error' && $type = 'danger';
        in_array($type,array('info','warning','success','danger')) OR $type = 'info';
        $CI->session->set_flashdata('__ci_flash', array(
            'type'    => $type,
            'message' => $msg
        ));
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('print_alert'))
{
    function print_alert($msg = '', $type = 'info')
    {
        if ($msg OR $msg !== '')
        {
            $CI =& get_instance();
            $type === 'error' && $type = 'danger';
            in_array($type,array('info','warning','success','danger')) OR $type = 'info';
            $data = array(
                'type'    => $type,
                'message' => $msg
            );
            return get_instance()->theme->partial('alert', $data, TRUE);
        }

        return NULL;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('print_flash_alert'))
{
    function print_flash_alert()
    {
        if (isset($_SESSION['__ci_flash']) && is_array($_SESSION['__ci_flash']))
        {
            return print_alert($_SESSION['__ci_flash']['message'], $_SESSION['__ci_flash']['type']);
        }

        return NULL;
    }
}

/* End of file theme_helper.php */
/* Location: ./application/helpers/theme_helper.php */