<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    #region Relationships

    public function imageable() {
        return $this->morphTo();
    }

    #endregion

}
