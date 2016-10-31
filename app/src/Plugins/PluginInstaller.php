<?php
namespace Anchorcms\Plugins;

use Composer\Console\Application as Composer;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

class PluginInstaller
{
    /**
     * the main app path
     *
     * @access protected
     * @var string
     */
    protected $path;

    /**
     * the composer configuration (composer.json)
     *
     * @access protected
     * @var array
     */
    protected $config;

    /**
     * PluginInstaller constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->readComposerConfig();
    }

    /**
     * runs composer install programmatically by spawning composer
     *
     * @access public
     * @return string
     */
    public function install()
    {
        $this->writeComposerConfig();

        // composer requires home set
        putenv('COMPOSER_HOME=' . $this->path . '/vendor/bin/composer');

        // set up output stream
        $stream = fopen('php://temp', 'w+');
        $output = new StreamOutput($stream);

        // create a composer instance
        $composer = new Composer();
        $composer->setAutoExit(false);
        $composer->run(new ArrayInput(['command' => 'install'], $output));

        // rewind stream to read full contents
        rewind($stream);
        return stream_get_contents($stream);
    }

    /**
     * reads the composer.json file
     *
     * @access public
     * @return void
     */
    public function readComposerConfig()
    {
        $this->config = json_decode(file_get_contents($this->path . '/composer.json'));
    }

    /**
     * writes the composer.json file
     *
     * @access public
     * @return void
     */
    public function writeComposerConfig()
    {
        file_put_contents($this->path . '/composer.json', json_encode($this->config));
    }

    /**
     * adds a new plugin to the composer.json file
     *
     * @access public
     * @param string $pluginName the composer package name, eg. "vendor/package-name"
     * @param string $version    the composer version string, eg. "~1". defaults to dev-master
     * @return void
     */
    public function addPlugin(string $pluginName, string $version = 'dev-master')
    {
        $this->config['require'][$pluginName] = $version;
    }
}
