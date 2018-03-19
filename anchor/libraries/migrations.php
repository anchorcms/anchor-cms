<?php

/**
 * migrations class
 * Handles all database migrations
 */
class migrations
{
    /**
     * Holds the current migration version
     *
     * @var int
     */
    private $current;

    /**
     * migrations constructor
     *
     * @param int|string $current current migration version
     */
    public function __construct($current)
    {
        $this->current = intval($current);
    }

    /**
     * Migrates up
     *
     * @param int|null $to (optional) target migration version
     *
     * @return int|string
     */
    public function up($to = null)
    {
        $num = -1;

        // sorted migration files
        $files = $this->files();

        if (is_null($to)) {
            $to = end(array_keys($files));
        }

        // run migrations
        foreach ($files as $num => $item) {

            // up to
            if ($num > $to) {
                break;
            }

            // starting from
            if ($num < $this->current) {
                continue;
            }

            // run
            /** @noinspection PhpIncludeInspection */
            require $item['path'];

            /** @var \migration $m */
            $m = new $item['class']();

            $m->up();
        }

        return $num;
    }

    /**
     * Retrieves all migration files
     *
     * @param bool $reverse whether to retrieve the files in reverse order
     *
     * @return array
     */
    public function files($reverse = false)
    {
        $iterator = new FilesystemIterator(APP . 'migrations', FilesystemIterator::SKIP_DOTS);
        $files    = [];

        foreach ($iterator as $file) {
            $parts = explode('_', $file->getBasename(EXT));
            $num   = array_shift($parts);

            $files[$num] = [
                'path'  => $file->getPathname(),
                'class' => 'Migration_' . implode('_', $parts)
            ];
        }

        if ($reverse) {
            krsort($files, SORT_NUMERIC);
        } else {
            ksort($files, SORT_NUMERIC);
        }

        return $files;
    }

    /**
     * Migrates down to a specific version
     *
     * @param int $to target migration version
     *
     * @return int|string
     */
    public function down($to)
    {
        $num = -1;

        // reverse sorted migration files
        $files = $this->files(true);

        if (is_null($to)) {
            $to = current(array_keys($files));
        }

        // run migrations
        foreach ($files as $num => $item) {

            // down to
            if ($num < $to) {
                break;
            }

            // starting from
            if ($num > $this->current) {
                continue;
            }

            // run
            /** @noinspection PhpIncludeInspection */
            require $item['path'];

            /** @var \migration $m */
            $m = new $item['class']();

            $m->down();
        }

        return $num;
    }
}
