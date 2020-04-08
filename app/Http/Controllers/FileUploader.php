<?php


namespace App\Http\Controllers;


use File;

class FileUploader
{

    /**
     * @param $media
     * @param string $dir
     * @return string
     */
    public static function upload($media, $dir = 'storage')
    {
        $file_name =  uniqid().'.'.$media->getClientOriginalExtension();
        $new_dir = $dir.'/'.date('Y/m/d');
        $path = $media->move($new_dir, str_replace(' ','_',$file_name));
        return '/'.$path;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function deleteFile(string $path)
    {
        if (File::exists(ltrim($path, $path[0]))) {
            File::delete(ltrim($path, $path[0]));
            return true;
        }
        return false;
    }
}