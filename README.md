# CI-Theme - CodeIgniter Themes
I tried a lot of templates and themes libraries and I was satisfy with them but I decided to make my own, as simple as it can get. So here is my library, free to use, like **CodeIgniter**.
**NOTE**: Sublime text auto completions has been added to the repo.
## What's CI-Theme?
CI-Theme is a library that helps you make your CodeIgniter application theme-able :D. Just download it, drop files into their respective folders (__content__ folder is public). Voilà.

## How To Install?
As I said above, simply drop all given files, configure and voilà, tout est prêt :), everything is set.

## What to do after?
You are given a folder names **content** inside which you haves **theme** and **uploads**. All your themes files go under _content/themes__ (simply follow the example of the **default** theme)

This library works as well when in **HMVC**. It will look for views in the following order:
#### Views:
1. themes/**theme_name**/modules/**module_name**/
2. themes/**theme_name**/modules/**module_name**/views/
3. themes/**theme_name**/views/
4. **module_location**/**module_name**/views/
5. **views_path** (which is by default **APPPATH/views/**)

#### Partial Views:
1. themes/**theme_name**/modules/**module_name**/views/partials/
3. themes/**theme_name**/views/partials/
4. **module_location**/**module_name**/views/partials/
5. **views_path**/partials/

#### Master View:
The master view is named **template.php** by default but it can be overridden (4th parameter of Theme::load()).
The library will search inside these folders in the following order:
1. themes/**theme_name**/modules/**module_name**/views/
2. themes/**theme_name**/
3. **module_location**/**module_name**/views/
4. **views_path**/

This was made when using **CodeIgniter version 3.1.3**. Feel free to report anything and suggest any enhancements.

All credits go to owners of any external files used here.