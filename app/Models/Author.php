<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['user_id'];
    #region Relations

    public function User() {
        return $this->belongsTo(User::class);
    }

    #endregion

    #region Mutators
    #endregion

    #region Accessors
    public function getFullnameAttribute() {
        if (is_null($this->user_id)) {
            return $this->name . ' ' . $this->last_name;
        }
        else {
            return $this->user->name . ' ' . $this->user->last_name;
        }
    }
    #endregion
}
