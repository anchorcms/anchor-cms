# About Plugins
Anchor provides a plugin API to extend Anchor's functionality. This document gives an overview about creating and using plugins.

## Installing a plugin
There are multiple ways to install a plugin: The most easy and trusted one is to use the built-in plugin repository.  
On your AnchorCMS installation, go to `/admin/plugins/install` where you'll find a list of available plugins in the repository. To install one of them, click the download button and wait for the process to finish.  

If you downloaded the plugin as a ZIP file, go to `/admin/plugins/install` and click the *upload manually* button at the top right. There, you can upload the ZIP, then wait for the process to finish.

If you want or need to install the plugin manually (eg. via `git clone` from Github), navigate to `/web/plugins` in your AnchorCMS installation directory on the server and make sure the directory name you choose reflects the namespace used within the plugin's code.  
This is necessary since `/web/plugins` equals the namespace `\Anchorcms\Plugins\` and any plugin class needs to use their directory name as their namespace for autoloading to work properly. Take the contact form plugin as an example: It lives in `/web/plugins/ContactFormPlugin` while its main class in `Plugin.php` has the namespace `\Anchorcms\Plugins\ContactFormPlugin\Plugin`.

## Using a plugin
A list of installed plugins can be viewed at `/admin/plugins`. Each of them has their version and their status (whether they are disabled or not) listed on the right of the entry.  
To view settings and additional meta data for a plugin, click on one of the entries in the list. If the plugin has settings available, they will be accessible here.  
You can also disable the plugin: That will completely prevent any interaction with this plugin until you enable it again. To do so, simply click on *enable* on the plugin page. Once a plugin is being disabled, the string `_disabled` will be appended to it's folder name, meaning you can also manually disable a malfunctioning plugin by doing this manually.  
To check whether there is a newer version of this plugin available, click on *check for updates*. If there is one in the repo, you'll see an additional *update* button. To update, click on it and wait for the process to finish.

When a plugin is installed, it is being disabled by default (in case it requires configuration before use). After you enable it, the plugin will start to work immediately. If it provides additional menu entries, you will find them within the *extend* dropdown menu.

## Creating a plugin
Anchor's plugin system allows for extending pretty much every aspect of the CMS.  The below guidelines show how to create a plugin. For further reference, there are a few example plugins available [here](examples/index.md).

### Requirements
Anchor defines a common plugin format: The plugin lives in a folder that's name is its namespace. The plugin `Foo` would therefore have the namespace `\Anchorcms\Plugins\Foo`. Inside of this folder, there's just one requirement: a file named `manifest.json` holding the plugin's meta data.  
Documentation on all supported fields can be found [here](manifest/index.md).  
There is one required manifest field: The main plugin class name for the autoloader to work properly. Inside this class, you can require more classes below your plugin's namespace or from Anchor core.

### Plugin initialization
The plugin is loaded on Anchor startup. This happens in two steps: First, the plugin is constructed, second, plugin interface methods are called. *All plugins need to extend `\Anchorcms\Plugin`.* Plugins don't need to but can implement a `__construct` method to set up. **No parameters are passed. // TODO: We could insert the container here?**  

### Event usage
Plugins in anchor have access to the `events` object, which is actually a Symfony Event dispatcher. It holds several events that plugins can listen to and provide callbacks for. You'll find documentation on events [here](events/index.md).

All plugins need to implement the `getSubscribedEvents` method that passes a dispatcher object. You can use it to attach event listeners like this:

````php
public function getSubscribedEvents(EventDispatcher $dispatcher)
{
    $dispatcher->addListener('event name', fn);
}
````

### Database access
In case your plugin requires database access, it needs to implement method `getDatabaseConnection`. It receives a `Doctrine\DBAL` Connection and the table prefix to work with, so you can work with the database as you wish to, though we recommend to create mappers and models as seen with Anchor's core.
