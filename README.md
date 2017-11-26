# CodeIgniter Theme Library
There are plenty of CodeIgniter template library. I tried most of them and I must say that they rock. Though, I had to make my own that suits my needs and that may be easy to implement, easy to understand and easy to use.

### UPDATED  
**assets_url()** removed because it was kind of useless but the following methods were added:  
* get_theme_url()
* theme_url()
* get_theme_path()
* theme_path()
* get_upload_url()
* upload_url()
* get_upload_path()
* upload_path()
* get_common_url()
* common_url()
* get_common_path()
* common_path()

All methods with **get_** will simply return the string while those without it will echo it.  
Example:  
    theme_url('css/style.css'); // Output: ...com/content/themes/THEME/css/style.css


## What is this library about?
It offers you the possibility to implement theming feature to your CodeIgniter applications with simple folders structure and ease of use (It works even when using **HMVC**).

## How to install?
All you have to do is to download provided files into your CodeIgniter install and you are done. Of course, some configuration need to be done and THEN you are really done.

## Files provided
This library comes in two (**2**) parts, the first goes into your application folder and the other in your public accessible folder (_public_html_, _www_...)

#### Folders structure

    - application
	    - config/theme.php
	    - helpers/theme_helper.php (_NOT NEEDED_)
	    - libraries/Theme.php

    - content
	    - common
	    - themes
		    - default
			    - assets/
			    - views/
				    - _layouts/
					    - default.php
				    - _master/
					    - default.php
				    - _modules/
				    - _partials/
					    - alert.php
					    - footer.php
					    - header.php
					    - sidebar.php
		    - semantic (same as above)
	    - uploads
Other files are simply either **css**, **js** or **images**.

##Library Methods

 - **set**: sets variables to pass to view files.
 - **get**: get something from theme config property.
 - **theme**: changes the current theme.
 - **master**: changes the master view file to use.
 - **layout**: use a different layout.
 - **title**, **description**, **keywords**: set page's meta tags content.
 - **add_meta**, **meta**: the first one appends a meta tag and the other one displays the full html &lt;meta&gt; tag.
 - **assets_url** and **uploads_url**: both return the full URL to their respective folders. (_content/assets_ and _content/uploads_)
 - Methods on **CSS** and **JS** (I use **x** to represente them both):
	 - **add_x**: adds the file to files list.
	 - **prepend_x**: adds the at the beginning of files list.
	 - **remove_x**: remove a file from files list.
	 - **replace_x**: replaces a file by a new one.
	 - **get_x**: returns an array of loaded **X** files.
	 - **x_url**: returns the full url to **X** file.
	 - **x**: css() or js() display full html tag with the given file.

## Where are files located?
CI-Theme library looks for views in a particular order so that everything can be overridden. Here is in order where files should be:

#### Views:
2. themes/**theme_name**/views/_modules/**module_name**/
3. themes/**theme_name**/views/
4. **module_location**/**module_name**/views/
5. **views_path** (which is by default **APPPATH/views/**)

#### Partials:
1. themes/**theme_name**/views/_modules/**module_name**/_partials/
3. themes/**theme_name**/views/_partials/
4. **module_location**/**module_name**/views/_partials/
5. **views_path**/_partials/

#### Layouts:
1. themes/**theme_name**/views/_modules/**module_name**/_layouts/
3. themes/**theme_name**/views/_layouts/
4. **module_location**/**module_name**/views/_layouts/
5. **views_path**/_layouts/

#### Master View:
The master view is named **default.php** by default but it can be overridden (4th parameter of Theme::load()).
The library will search inside these folders in the following order:
1. themes/**theme_name**/views/_modules/**module_name**/_master/
3. themes/**theme_name**/views/_master/
4. **module_location**/**module_name**/views/_master/
5. **views_path**/_master/

## How to use?
Load the library where you want to use or you can autoload it inside **autoload.php** file.

`$autoload['libraries'] = array('theme');`

In your controller, simple use library's method or chain them (ones that can be chained). Example (see: *controllers/Example.php*)

    $this->theme
        ->title('Title Goes Here')
        ->add_css('added_css1', 'added_css2')
        ->prepend_css('prepended_css1'),
        ->add_js('added_js1'),
        ->prepend_js('prepended_js1')
        ->add_partial('header')
        ->load('view_file', $data);

There is a short version of all this but in case you want to add partial views you have to use `$this->theme->add_partial()` before using the function.

    $this->theme
	    ->add_partial('header')
	    ->add_partial('footer');
	
	render('view_file', $data, 'Title Goes Here', array(
	    // Available options.
	    'css'        => array('added_css1', 'added_css2'),
	    'prepend_css' => 'prepended_css1',
	    'js'         => 'added_js1',
	    'prepend_js'  => 'prepended_js1',
	));

Feel free to explore the library to know more about it and if you have any questions, I am here to answer as long as I am still alive.

#CREDITS
All credits go to their respective owners: **CodeIgniter**, **Bootstrap** and **Semantic-UI**. (and some of it for my work :D)