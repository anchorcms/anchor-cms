<?php

namespace Anchorcms\Services;

use Anchorcms\Services\Plugins\Plugin;
use Pimple\Container;
use RuntimeException;
use ZipArchive;
use Psr\Http\Message\UploadedFileInterface;

class Plugins
{
    /**
     * the plugin base path
     *
     * @access protected
     * @var
     */
    protected $path;

    /**
     * the loaded plugins.
     *
     * @access protected
     * @var array
     */
    protected $plugins = [];

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * retrieves all plugins as an array sorted by name
     *
     * @access public
     * @return array
     */
    public function getPlugins()
    {
        $plugins = [];

        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            if ($file->isDir()) {
                if (!file_exists($file . DIRECTORY_SEPARATOR . 'manifest.json')) {
                    continue;
                }

                $plugin = new Plugins\Plugin($file->getPathname());

                $plugins[$plugin->getName()] = $plugin;
            }
        }

        // sort the plugins by name
        ksort($plugins);

        return array_values($plugins);
    }

    /**
     * retrieves the number of installed plugins
     *
     * @access public
     * @return int
     */
    public function countPlugins()
    {
        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        return iterator_count($fi);
    }

    /**
     * retrieves a specific plugin by name
     *
     * @access public
     * @param string $name
     * @return Plugin|bool
     */
    public function getPlugin(string $name)
    {
        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            if ($file->isDir()) {
                if (!file_exists($file . DIRECTORY_SEPARATOR . 'manifest.json')) {
                    continue;
                }

                $plugin = new Plugins\Plugin($file->getPathname());

                if (strtolower($plugin->getName()) === $name) {
                    return $plugin;
                }
            }
        }

        return false;
    }

    /**
     * uploads a new plugin as zip file
     *
     * @access public
     * @param UploadedFileInterface $file
     * @return string
     */
    public function upload(UploadedFileInterface $file)
    {
        switch ($file->getError()) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        $accepted = [
            'zip' => 'application/zip'
        ];

        $mimetypeParts = explode(';', $file->getClientMediaType());
        $mimetype = $mimetypeParts[0];

        $extension = array_search($mimetype, $accepted);

        if (!$extension) {
            throw new RuntimeException('Unaccepted file format.');
        }

        // put the zip temporarily in the plugin path
        file_put_contents($this->path . '/' . $file->getClientFilename(), $file->getStream());

        // extract the zip file
        $zip = new ZipArchive;
        $res = $zip->open($this->path . '/' . $file->getClientFilename());

        if ($res === true) {

            /**
             * use the plugin name as the file name
             * TODO: Sort out whether this makes sense. If the name contains spaces, the namespace resolution won't work so we'd need to restrict plugin names. Or add another "Display Name" property to the manifest?
             */
            try {
                $pluginManifest = json_decode(file_get_contents('zip://' . $this->path . '/' . $file->getClientFilename() . '#manifest.json'));

                $pluginName = $pluginManifest->name;
            } catch (\Exception $exception) {
                throw new RuntimeException('the uploaded plugin has no valid manifest file');
            }

            $zip->extractTo($this->path . '/' . $pluginName);
            $zip->close();
        } else {
            throw new RuntimeException('the uploaded file could not be extracted: ' . json_encode($zip->getStatusString()));
        }

        // remove the temporary file
        unlink($this->path . '/' . $file->getClientFilename());

        return $pluginName;
    }

    /**
     * loads all plugin instances
     *
     * @access public
     * @param Container $app
     * @return void
     */
    public function init(Container $app)
    {
        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            if ($file->isDir()) {
                if (!file_exists($file . DIRECTORY_SEPARATOR . 'manifest.json')) {
                    continue;
                }

                try {
                    $pluginInstance = $this->load($file);

                    if ($pluginInstance) {
                        $pluginInstance->getSubscribedEvents($app['events']);

                        /**
                         * check to see if the plugin uses the database
                         */
                        if (in_array('Anchorcms\PluginDatabaseInterface', class_implements($pluginInstance))) {
                            $pluginInstance->getDatabaseConnection($app['db'], $app['config']->get('db.table_prefix'));
                        }

                        array_push($this->plugins, $pluginInstance);
                    }
                } catch (\Error $error) {
                    $app['messages']->error([$error->getMessage()]);
                }
            }
        }
    }

    /**
     * load a plugin
     *
     * @access public
     * @param \SplFileInfo $file
     * @return \Anchorcms\Plugin|bool
     * @throws \Error
     */
    public function load(\SplFileInfo $file)
    {
        $plugin = new Plugins\Plugin($file->getPathname());

        if ($plugin->isActive()) {
            return $plugin->load();
        }

        return false;
    }
}
