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


## Admin events

### `admin:beforeBodyRender`
The beforeBodyRender event lets plugins modify the template and variables used before the page body is rendered.

### `admin:beforeLayoutRender`
The beforeLayoutRender event lets plugins modify the template and variables used before the page layout is rendered.


### `admin:buildScripts`
The buildScripts event lets plugins append scripts to the admin interface.

### `admin:buildStyles`
The buildStyles event lets plugins append stylesheets to the admin interface.

