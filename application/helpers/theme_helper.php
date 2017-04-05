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

        return NULL;
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
    function assets_url($uri = '', $folder = NULL)
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
    function css_url($file = NULL, $folder = NULL)
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
    function css($file = NULL, $cdn = NULL, $attrs = '', $folder = NULL)
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
    function js_url($file, $folder = NULL)
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
     * @param   string  $content  content or NULL if $name is array
     * @return  string
     */
    function meta($name, $content = NULL, array $attrs = array(), $type = 'name')
    {
        return get_instance()->theme->meta($name, $content, $attrs, $type);
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
    function img_url($file = NULL, $folder = NULL)
    {
        if (filter_var($file, FILTER_VALIDATE_URL) !== FALSE)
        {
            return $file;
        }
        $file = ($folder !== NULL) ? $file : 'img/'.$file;
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
    function img($file = NULL, $attrs = '', $folder = NULL)
    {
        if (strlen($file) > 0)
        {
            return '<img src="'.img_url($file, $folder).'"'._stringify_attributes($attrs).' />';
        }
        return NULL;
    }
}

if ( ! function_exists('img_alt')) 
{
    /**
     * Displays an alternative image using placehold.it website.
     *
     * @return  string
     */
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

if ( ! function_exists('theme_view'))
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
    function theme_view($view, $data = array(), $return = FALSE, $master = 'template')
    {
        return get_instance()->theme->load($view, $data, $return, $master);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('load_view'))
{
    /**
     * Displays a single view alone.
     * 
     * @param   string  $view   the view name
     * @param   array   $data   array of data to pass
     * @param   bool    $return whether to return or output
     * @return  mixed
     */
    function load_view($view, $data = array(), $return = FALSE)
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
    function theme_partial($view, $data = array(), $return = FALSE)
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
    function theme_header($data = array(), $return = FALSE)
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
    function theme_footer($data = array(), $return = FALSE)
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
            return NULL;
        }

        // Make sure the Theme library is loaded.
        if ( ! class_exists('Theme', FALSE))
        {
            get_instance()->load->library('theme');
        }

        return theme_partial($view, array(
            'type' => $type, 
            'message' => $message
        ), TRUE);
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
            return FALSE;
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
        if ( ! class_exists('CI_Session', FALSE))
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

        return NULL;
    }
}

/* End of file theme_helper.php */
/* Location: ./application/helpers/theme_helper.php */