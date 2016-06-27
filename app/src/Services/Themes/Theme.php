<?php

namespace Anchorcms\Services\Themes;

class Theme
{
    /**
     * Folder where the theme lives.
     *
     * @var string
     */
    protected $path;

    /**
     * Instance of mustache.
     *
     * @var object
     */
    protected $mustache;

    /**
     * The basename of the theme.
     *
     * @var string
     */
    protected $name;

    /**
     * The json decoded contents of the manifest file.
     *
     * @var object
     */
    protected $manifest;

    /**
     * The file extension of the templates.
     *
     * @var string
     */
    protected $ext = '.mustache';

    /**
     * The theme constructorrr.
     *
     * @param object Mustache_Engine
     * @param string Theme path
     */
    public function __construct(\Mustache_Engine $mustache, string $path)
    {
        $this->path = realpath($path);
        $this->mustache = $mustache;
        $this->name = basename($path);
        $this->loadManifest();

        $loader = new \Mustache_Loader_FilesystemLoader($this->path, ['extension' => $this->ext]);
        $this->mustache->setLoader($loader);
    }

    /*
     *
     */
    public function loadManifest()
    {
        if ($this->hasManifest()) {
            $json = file_get_contents($this->getManifestFilepath());
            $this->manifest = json_decode($json);
            $this->ext = $this->manifest->extension;
        }
    }

    /*
     *
     */
    public function getManifestFilepath()
    {
        return $this->path.'/manifest.json';
    }

    /*
     *
     */
    public function hasManifest()
    {
        return is_file($this->getManifestFilepath());
    }

    /*
     *
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /*
     *
     */
    public function setActive()
    {
        $this->active = true;
    }

    /*
     *
     */
    public function isActive()
    {
        return $this->active;
    }

    /*
     *
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the first template that exists.
     *
     * @param array
     *
     * @return string
     */
    public function getTemplate(array $names)
    {
        foreach ($names as $name) {
            if ($this->templateExists($name)) {
                return $name;
            }
        }

        throw new \InvalidArgumentException(sprintf('Template files not found: %s', json_encode($names)));
    }

    /**
     * Checks if a template file exists.
     *
     * @param string
     *
     * @return bool
     */
    public function templateExists(string $name): bool
    {
        return is_file(sprintf('%s/%s%s', $this->path, $name, $this->ext));
    }

    /*
     *
     */
    public function render(array $templates, array $vars = [])
    {
        if ($this->templateExists('layout')) {
            $template = $this->getTemplate($templates);
            $body = $this->mustache->loadTemplate($template);

            $this->mustache->setPartials([
                'body' => $body->render($vars),
            ]);

            $layout = $this->mustache->loadTemplate('layout');

            return $layout->render($vars);
        }

        $template = $this->getTemplate($templates);

        return $this->mustache->loadTemplate($template)->render($vars);
    }
}
