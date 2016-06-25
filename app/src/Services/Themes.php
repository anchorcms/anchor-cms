<?php

namespace Anchorcms\Services;

class Themes
{

    protected $path;

    protected $current;

    public function __construct($path, $current = null)
    {
        $this->path = $path;
        $this->current = $current;
    }

    public function getThemes()
    {
        $if = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($if as $file) {
            if (false === $file->isDir()) {
                continue;
            }

            $theme = new Themes\Theme($file->getPathname());

            if ($theme->getName() == $this->current) {
                $theme->setActive();
            }

            yield $theme;
        }
    }
}
