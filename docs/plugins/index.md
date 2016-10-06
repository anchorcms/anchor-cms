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
You can also disable the plugin: That will completely prevent any interaction with this plugin until you enable it again. To do so, simply click on *enable* on the plugin page.  
To check whether there is a newer version of this plugin available, click on *check for updates*. If there is one in the repo, you'll see an additional *update* button. To update, click on it and wait for the process to finish.

When a plugin is installed, it is being disabled by default (in case it requires configuration before use). After you enable it, the plugin will start to work immediately. If it provides additional menu entries, you will find them within the *extend* dropdown menu.

## Creating a plugin
Plugins in anchor have access to the `events` object, which is a Symfony Event dispatcher. It holds several events that plugins can listen to and provide callbacks for.  
