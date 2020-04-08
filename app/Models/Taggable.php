<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    protected $fillable = ['tag_id', 'taggable_type', 'taggable_id'];
    protected $hidden = ['created_at'];


}
