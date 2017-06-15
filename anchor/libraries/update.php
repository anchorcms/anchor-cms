<?php
class update
{
    public static function version()
    {
        // first time
        if (! $last = Config::meta('last_update_check')) {
            $last = static::setup();
        }
        static::renew();
    }
    public static function setup()
    {
        $version = static::touch();
        $today = date('Y-m-d H:i:s');
        $table = Base::table('meta');
        Query::table($table)->insert(array('key' => 'last_update_check', 'value' => $today));
        Query::table($table)->insert(array('key' => 'update_version', 'value' => $version));
        // reload database metadata
        foreach (Query::table($table)->get() as $item) {
            $meta[$item->key] = $item->value;
        }
        Config::set('meta', $meta);
    }
    public static function renew()
    {
        $version = static::touch();
        $today = date('Y-m-d H:i:s');
        $table = Base::table('meta');
        Query::table($table)->where('key', '=', 'last_update_check')->update(array('value' => $today));
        Query::table($table)->where('key', '=', 'update_version')->update(array('value' => $version));
        // reload database metadata
        foreach (Query::table($table)->get() as $item) {
            $meta[$item->key] = $item->value;
        }
        Config::set('meta', $meta);
    }
    public static function touch()
    {
        $url = 'https://anchorcms.com/version';
        $result = false;
        
        if (in_array(ini_get('allow_url_fopen'), array('true', '1', 'On'))) {
            $context = stream_context_create(array('http' => array('timeout' => 2)));
            $result = file_get_contents($url, false, $context);
        } elseif (function_exists('curl_init')) {
            try {
                $session = curl_init();
                curl_setopt_array($session, array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER => true
                ));
                $result = curl_exec($session);
                curl_close($session);
            } catch(Exception $e) {
                Error::log("Unable to check for update... Exception:\n$e");
            }
        }

        return $result;
    }
    public static function upgrade($url, $version)
    {
        $result = 'false';
        
        $result .= '|-|Creating \'anchor_update\' folder.';
        $output_folder = PATH . "anchor_update" . DS;
        $output_file = $output_folder . "anchor_$version.zip";
        @mkdir(dirname($output_file));
        $result .= '|-|Folder created.';
        
        if(in_array(ini_get('allow_url_fopen'), array('true', '1', 'On'))) {
            $result .= '|-|Using copy() function.';
            try {
                $result .= '|-|Starting copy().';
                copy($url, $output_file);
                $result .= '|-|Finished copy().';
            } catch(Excpetion $e) {
                $result .= '|-|' . $e->getMessage() . '|-|ERROR';
            }
        } else {
            $result .= '|-|Using curl functions.';
            try {
                $result .= '|-|Initialising curl.';
                $session = curl_init();
                curl_setopt_array($session, array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER => true
                ));
                $result .= '|-|Executing curl.';
                $d = curl_exec($session);
                curl_close($session);
                
                $result .= '|-|Writing data to file.';
                $f = fopen($output_file, 'w+');
                fputs($f, $d);
                fclose($f);
                $result .= '|-|Data written and saved.';
            } catch(Excpetion $e) {
                $result .= '|-|' . $e->getMessage() . '|-|ERROR';
            }
        }
        
        try {
            $result .= '|-|Testing if ZipArchive is a valid PHP class.';
            $zip = new ZipArchive;
            $result .= '|-|Attempting to open zip file.';
            if(($zipCode = $zip->open($output_file)) === true) {
                $result .= '|-|Extracting zip file.';
                $zip->extractTo($output_folder);
                $output_folder .= $zip->getNameIndex(0);
                $zip->close();
                $result .= '|-|Zip extracted.';
                
                $result .= '|-|Recursive copy of \'/anchor/\', \'/system/\', and \'/index.php\'.';
                recurse_copy($output_folder . "anchor", APP);
                recurse_copy($output_folder . "system", SYS);
                copy($output_folder . "index.php", PATH . "index.php");
                $result .= '|-|Recursive copy complete.';
                $result = substr_replace($result, 'true', 0, strlen('false'));
            } else throw new Exception("Cannot open the downloaded archive (CODE: $zipCode) - you may need to extract the contents manually! See https://anchorcms.com/docs/getting-started/upgrading.");
            
            $result .= '|-|Deleting temporary upgrade files.';
            delTree($output_folder);
            $result .= '|-|Done.';
        } catch(Exception $e) {
            $result .= '|-|' . $e->getMessage() . '|-|ERROR';
        }
        
        return $result;
    }
}
