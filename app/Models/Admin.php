<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    const TYPE_ADMIN = 'admin';
    const TYPE_BLOGGER = 'blogger';
    protected $fillable = ['name', 'username', 'password'];

    #region Relations
    #endregion

    #region Mutators
    #endregion

    #region Accessors
    #endregion
}
