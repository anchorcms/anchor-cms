<?php

namespace Anchorcms\Services;

use FilesystemIterator;
use RuntimeException;
use Psr\Http\Message\UploadedFileInterface;
use League\Flysystem\FilesystemInterface;

class Media
{
    protected $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    protected function formatFileName($str, $ext)
    {
        // convert to ascii
        $str = preg_replace('/[[:^print:]]/', '', $str);

        // lower case
        $str = strtolower($str);

        // remove file extension
        $str = rtrim($str, '.'.$ext);

        // remove white space
        $str = preg_replace('#\s+#', '-', $str);

        return $str.'.'.$ext;
    }

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
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];

        $mimetypeParts = explode(';', $file->getClientMediaType());
        $mimetype = $mimetypeParts[0];

        $ext = array_search($mimetype, $accepted);

        if (false === $ext) {
            throw new RuntimeException('Unaccepted file format.');
        }

        $name = $this->formatFileName($file->getClientFilename(), $ext);
        $this->filesystem->writeStream($name, $file->getStream());

        return $name;
    }

    public function get()
    {
        $files = $this->filesystem->listPaths();

        return $files;
    }
}
