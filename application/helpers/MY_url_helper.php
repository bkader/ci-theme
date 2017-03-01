<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('current_url'))
{
    /**
     * Overrides CI current_url
     * @param   none
     * @return  string
     */
    function current_url()
    {
        return get_instance()->config->current_url();
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
		return get_instance()->config->assets_url($uri, $folder);
	}
}

/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */