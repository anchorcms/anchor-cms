<?php

namespace Anchorcms\Plugins;

class PluginUploader
{
    /**
     * uploads a new plugin as zip file
     *
     * @access public
     * @param UploadedFileInterface $file
     * @return string
     */
    public function upload(UploadedFileInterface $file): string
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
        $zip = new \ZipArchive;
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
                throw new \RuntimeException('the uploaded plugin has no valid manifest file');
            }

            $zip->extractTo($this->path . '/' . $pluginName);
            $zip->close();
        } else {
            throw new \RuntimeException('the uploaded file could not be extracted: ' . json_encode($zip->getStatusString()));
        }

        // remove the temporary file
        unlink($this->path . '/' . $file->getClientFilename());
        return $pluginName;
    }
}
