<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static function get($value) {
        return Setting::where('key', $value)->value('value');
    }
}
