<?php defined('IN_CMS') or die('No direct access allowed.');

class Thumbnail {
    public static $dir = 'uploads',
                  $mimes = array('jpg', 'jpeg', 'png', 'gif', 'bmp'),
                  $error = false,
                  
              //  Set editing mode
                  $file = false;
    
    /**
        Restrict to a field called thumbnail of type JPG
        Thumbnail::create('thumbnail', 'jpg');
    */
    public static function create($key, $type = '') {
    
        //  Make sure the file exists
        if(!isset($_FILES[$key])) {
            return self::error('Form key does not exist. You should never see this.');
        }
        
        //  Store the file's key
        $file = $_FILES[$key];
        
        //  Make sure it's a file
        if(!in_array(str_replace('image/', '', $file['type']), self::$mimes)) {
            return self::error('Invalid file type &ldquo;' . $file['type'] . '&rdquo;. We only want images, please.');
        }
        
        //  Make sure we can write to the destination
        $path = PATH . 'uploads/';
        
        //  If we cant' access the directory, try to make it
        if(!is_writable($path)) {
            if(!file_exists($path) and !@mkdir($path, 0755)) {
                return self::error('Couldn&rsquo;t write to the <code>' . $path . '</code> folder. Check it exists and has the right permissions.');
            }
        }
        
        $filename = $file['name'];
        
        //  Set another filename if it already exists
        if(file_exists($path . $filename)) {
            $pathinfo = pathinfo($filename);
            $filename = str_replace($pathinfo['extension'], '-' . time(), $filename);
        }
        
        if(!move_uploaded_file($file['tmp_name'], PATH . 'uploads/' . $filename)) {
            return self::error('Could not upload file. Not sure why, sorry.');
        }
    
        return $filename;
    }
    
    /**
        Set an image for retiring
    */
    public static function set($name) {
        if(file_exists($name)) {
            self::$file = $name;
            return true;
        }
        
        return false;
    }
    
    /**
        Resizing an image
    */
    public static function resize($width, $height = 'auto') {
    
        //  Are we in edit mode?
        if(self::$file === false) {
            return self::error('Set a file to edit first!');
        }
            
        $image = new Image(self::$file);
        return $image->resize($width, $height)->save();
    }
    
    /**
        Private error handling
    */
    private static function error($str) {
        self::$error = $str;
        return false;
    }
}