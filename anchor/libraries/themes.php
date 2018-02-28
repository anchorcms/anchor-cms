<?php

/**
 * themes class
 * Manages theme meta data
 */
class themes
{
    /**
     * Retrieves all theme metadata by iterating over the file system directory
     *
     * @return array list of theme metadata
     */
    public static function all()
    {
        $themes = [];
        $fi     = new FilesystemIterator(PATH . 'themes', FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            if ($file->isDir()) {
                $theme = $file->getFilename();

                if ($about = static::parse($theme)) {
                    $themes[$theme] = $about;
                }
            }
        }

        ksort($themes);

        return $themes;
    }

    /**
     * Parses theme meta data from about.txt
     *
     * @param string $theme theme name
     *
     * @return array|bool false if not readable, theme meta otherwise
     */
    public static function parse($theme)
    {
        $file = PATH . 'themes/' . $theme . '/about.txt';

        if ( ! is_readable($file)) {
            return false;
        }

        // read file into a array
        $contents = explode("\n", trim(file_get_contents($file)));
        $about    = [];

        foreach (['name', 'description', 'author', 'site', 'license'] as $index => $key) {
            // temp value
            $about[$key] = '';

            // find line if exists
            if ( ! isset($contents[$index])) {
                continue;
            }

            $line = $contents[$index];

            // skip if not separated by a colon character
            if (strpos($line, ":") === false) {
                continue;
            }

            $parts = explode(":", $line);

            // remove the key part
            array_shift($parts);

            // in case there was a colon in our value part glue it back together
            $value = implode('', $parts);

            $about[$key] = trim($value);
        }

        return $about;
    }

    /**
     * Retrieves all templates for a file
     *
     * @param string $theme theme to retrieve templates for
     *
     * @return array theme templates
     */
    public static function templates($theme)
    {
        $templates = [];
        $fi        = new FilesystemIterator(PATH . 'themes/' . $theme, FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            $ext  = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            $base = $file->getBasename('.' . $ext);

            if ($file->isFile() and $ext == 'php') {
                $templates[$base] = $base;
            }
        }

        return $templates;
    }
}
