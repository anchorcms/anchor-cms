<?php

namespace Anchorcms\Services;

class Themes
{
    protected $path;

    protected $engine;

    protected $current;

    public function __construct($path, $engine, $current = null)
    {
        $this->path = $path;
        $this->engine = $engine;
        $this->current = $current;
    }

    public function getThemes()
    {
        $if = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($if as $file) {
            if (false === $file->isDir()) {
                continue;
            }

            $theme = new Themes\Theme($this->engine, $file->getPathname());

            if ($theme->getName() == $this->current) {
                $theme->setActive();
            }

            yield $theme;
        }
    }
}
