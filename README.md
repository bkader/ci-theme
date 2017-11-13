#CodeIgniter Theme Library
There are plenty of CodeIgniter template library. I tried most of them and I must say that they rock. Though, I had to make my own that suits my needs and that may be easy to implement, easy to understand and easy to use.

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
	 - **append_x**: adds the at the beginning of files list.
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

Feel free to explore the library to know more about it and if you have any questions, I am here to answer as long as I am still alive.

#CREDITS
All credits go to their respective owners: **CodeIgniter**, **Bootstrap** and **Semantic-UI**. (and some of it for my work :D)