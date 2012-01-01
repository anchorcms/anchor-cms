<?php

//  Delete all the files in a directory
//  Should only be used when done with the installer.
function delete($path) {

    $realPath = realpath($path);
    
    if (!file_exists($realPath)) 
        return false;
    $DirIter = new RecursiveDirectoryIterator($realPath);       
    $fileObjects = new RecursiveIteratorIterator($DirIter, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($fileObjects as $name => $fileObj) {
        if ($fileObj->isDir()) 
                rmdir($name);
        else
                unlink($name);
    }
    rmdir($realPath);
    return true;
    
}