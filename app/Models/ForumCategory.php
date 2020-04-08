<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    protected $hidden = ['created_at', 'updated_at'];



    public function forums() {
        return $this->hasMany(Forum::class);
    }

}
