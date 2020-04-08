<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use File;
use Validator;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $errors
     * @param $rules
     * @param array $messages
     * @return \Illuminate\Validation\Validator
     */
    protected function validator ($errors, $rules, $messages=[])
    {
        return Validator::make($errors,$rules,$messages);
    }

    /**
     * @param $media
     * @param string $dir
     * @return string
     */
    protected function upload($media, $dir = 'storage')
    {
        $file_extension = File::extension($media->getClientOriginalName());
        $file_name =  uniqid().'.'.$file_extension;
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

    /**
     * @param $phone
     * @return bool|string|string[]|null
     */
    protected function validatePhone ($phone)
    {
        $phone_number = preg_replace('/[^0-9]/', '', $phone);
        $phone_number = substr($phone_number,1);
        return $phone_number;
    }

    /**
     * @param $str
     * @return string
     */
    protected function trimToLower ($str)
    {
        $str = str_replace(' ','',$str);
        return strtolower($str);
    }

    /**
     * @param $arr
     * @param $delimiter
     * @return string
     */
    protected function arrToString ($arr, $delimiter)
    {
        $new_arr = [];
        foreach ($arr as $item) {
            if($item) {
                $new_arr[] = $item;
            }
        }
        $str = implode($delimiter,$new_arr);
        return $str;
    }

    /**
     * @param $str
     * @param $delimiter
     * @return array
     */
    protected function strToArr ($str, $delimiter)
    {
        $temp = explode($delimiter,$str);
        $arr = [];
        foreach ($temp as $item) {
            if($item) {
                $arr[] = $item;
            }
        }
        return $arr;
    }

    protected function Result(int $statusCode, $data = null, string $message = null){
        switch ($statusCode){
            case 200:
                $result['statusCode'] = $statusCode;
                $result['message'] = ($message) ? $message : trans('Успешно');
                $result['result'] = $data;
                break;

            case 404:
                $result['statusCode'] = $statusCode;
                $result['message'] = ($message) ? $message : trans('Ничего не найдено');
                $result['result'] = $data;
                break;
            case 401:
                $result['statusCode'] = 401;
                $result['message'] = ($message) ? $message : trans('Не авторизован');
                $result['result'] = null;
                break;
            case 400:
                $result['statusCode'] = 400;
                $result['message'] = ($message) ? $message : trans('Неверный запрос');
                $result['result'] = $data;
                break;
            default:
                $result['statusCode'] = $statusCode;
                $result['message'] = $message;
                $result['result'] = $data;
                break;
        }

        return response()->json($result, $result['statusCode'], []);

    }
}
