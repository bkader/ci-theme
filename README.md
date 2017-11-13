# CI-Theme - CodeIgniter Themes
I tried a lot of templates and themes libraries and I was satisfy with them but I decided to make my own, as simple as it can get. So here is my library, free to use, like **CodeIgniter**.
**NOTE**: Sublime text auto completions has been added to the repo.
## What's CI-Theme?
CI-Theme is a library that helps you make your CodeIgniter application theme-able :D. Just download it, drop files into their respective folders (__content__ folder is public). Voilà.

## How To Install?
As I said above, simply drop all given files, configure and voilà, tout est prêt :), everything is set.

## What to do after?
You are given a folder names **content** inside which you have **theme** and **uploads**. All your themes files go under _content/themes_ (simply follow the example of the **default** theme)

This library works as well when in **HMVC**. It will look for views in the following order:
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

This was made when using **CodeIgniter version 3.1.6**. Feel free to report anything and suggest any enhancements.

All credits go to owners of any external files used here.