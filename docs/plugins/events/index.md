# Plugin events
The list below shows all events plugins can listen to in AnchorCMS.

### `routing`
The routing event lets plugins register new routes.

The event passes a `RoutingEvent` object that exposes a `RouteCollection` object for plugins.

### `middleware`
The middleware event lets plugins add new middleware.

The event passes a `MiddlewareEvent` object that has three methods available for plugins:

 - `prepend($middleware)`: prepend a middleware to the stack
 - `append($middleware)`: append a middleware to the stack
 - `getContainer()`: get the app object

### `filters`
The filters event lets plugins add filters (or short codes).

The event passes a `FilterEvent` object that has one method available for plugins:

`addFilter($filterName, $callback)`: add a filter to the stack

#### Filter usage
To create a new filter, you need to provide two things:  
**the filter name**, which is the string users insert into their post without the surrounding braces. To let users enter `{{contactForm}}`, for example, filter name has to be `contactForm`.
**the filter callback**, which is the callback triggered when there is an instance of your filter name found within the post content. The callback receives two arguments: The `request` object and eventual `attributes` passed. To add an attribute to your filter, users may attach these to the filter name like this: `{{contactForm foo:"bar" id:10}}`. `foo` and `id` will be available as `$attributes[ 'foo' => 'bar', id: '10' ]`.

## Admin events

### `admin:beforeBodyRender`
The beforeBodyRender event lets plugins modify the template and variables used before the page body is rendered.

The event passes a `BeforeRenderEvent` that has several methods available for plugins:

 - `getTemplate()`: get the current template
 - `setTemplate()`: set a new template
 - `getVar($var)`: get a single var
 - `getVars()`: get all vars
 - `setVar($name, $value)`: set a var
 - `addVars($vars)`: set multiple vars
 - `replaceVars($newVars)`: replace all vars

### `admin:beforeLayoutRender`
The beforeLayoutRender event lets plugins modify the template and variables used before the page layout is rendered.

The event passes a `BeforeRenderEvent` that has several methods available for plugins:

 - `getTemplate()`: get the current template
 - `setTemplate()`: set a new template
 - `getVar($var)`: get a single var
 - `getVars()`: get all vars
 - `setVar($name, $value)`: set a var
 - `addVars($vars)`: set multiple vars
 - `replaceVars($newVars)`: replace all vars


### `admin:buildScripts`
The buildScripts event lets plugins append scripts to the admin interface.

The event passes a `BuildScriptsEvent` object that has two methods available for plugins:

 - `addScript(string $path)`: Adds the script at the given path to the admin header
 - `addScripts(array $paths)`: Adds multiple scripts at the given paths to the admin header

### `admin:buildStyles`
The buildStyles event lets plugins append stylesheets to the admin interface.

The event passes a `BuildStylesEvent` object that has two methods available for plugins:

 - `addStyle(string $path)`: Adds the stylesheet at the given path to the admin header
 - `addStyles(array $paths)`: Adds multiple stylesheets at the given paths to the admin header
