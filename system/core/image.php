<?php defined('IN_CMS') or die('No direct access allowed.');

class Image {

    private $path;
    
    public function __construct($path) {
        $this->path = $path;
        $this->dir = dirname($this->path);
        
        if($path) {
            $this->image = $this->create($this->path);
        }
    }
    
    public function resize($width, $height) {
        if($this->image === false) {
            return $this;
        }
        
        //  Get the new dimensions and create the new image
        $this->dimensions = $this->_dimensions($width, $height);
        $this->newImg = imagecreatetruecolor($this->dimensions[0], $this->dimensions[1]);
        
        //  Copy the image
        imagecopyresampled(
            $this->newImg,
            $this->image,
            0, 0, 0, 0,
            $this->dimensions[0], $this->dimensions[1],
            $this->dimensions[0], $this->dimensions[1]
        );   
        
        return $this;
    }
    
    public function save($quality = 100) {
        if($this->image === false) {
            return false;
        }
        
        $save = 'image' . $this->extension;
        $filename = $this->explodedFilename[0] . '-' . $this->dimensions[0] . 'x' . $this->dimensions[1] . '.' . str_replace('jpeg', 'jpg', $this->explodedFilename[1]);
        $path = $this->dir . '/' . $filename;
        
        if(!file_exists($path) and function_exists($save)) {
            call_user_func($save, $this->newImg, $path);
        }
        
        return (object) array('src' => base_url('uploads/' . $filename), 'width' => $this->dimensions[0], 'height' => $this->dimensions[1]);
    }
    
    public function create($file) {
        //  Get the file's extension
        $this->filename = basename($file);
        $this->explodedFilename = $extension = explode('.', $this->filename);
        $extension = $extension[count($extension) - 1];
        
        //  Fix for imagecreatefromjpeg
        $this->extension = $extension = str_replace('jpg', 'jpeg', $extension);
        
        $fn = 'imagecreatefrom' . $extension;
        
        if(function_exists($fn)) {
            return call_user_func($fn, $file);
        }
        
        return false;
    }
    
    private function _dimensions($width, $height) {
    
        $oldWidth = imagesx($this->image);
        $oldHeight = imagesy($this->image);
    
        //  If they're both auto, return the height back
        if($width === $height and $height === 'auto') {
            return array($oldWidth, $oldHeight);
        }
        
        //  Calculate "auto" values
        if($width === 'auto') $width = ($height / $oldHeight) * $oldWidth;
        if($height === 'auto') $height = ($width / $oldWidth) * $oldHeight;
        
        return array(round($width), round($height));
    }
}