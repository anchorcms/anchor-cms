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
### `admin:beforeLayout`
### `admin:buildScripts`
### `admin:buildStyles`
