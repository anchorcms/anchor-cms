<?php

namespace Anchorcms\Session;

class FileStorage
{
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function read(string $id): array
    {
        $path = sprintf('%s/%s.sess', $this->path, $id);

        if (false === is_file($path)) {
            return [];
        }

        $contents = file_get_contents($path);

        return json_decode($contents, true);
    }

    public function write(string $id, array $data): bool
    {
        $path = sprintf('%s/%s.sess', $this->path, $id);

		$jsonString = json_encode($data);

        if (false === file_put_contents($path, $jsonString, LOCK_EX)) {
            throw new \RuntimeException('Failed to write session file');
        }

        return true;
    }
}
