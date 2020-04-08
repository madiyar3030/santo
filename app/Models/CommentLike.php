<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = ['user_id', 'comment_id'];
    protected $casts = ['user_id' => 'integer', 'comment_id' => 'integer'];

    #region Relations
    #endregion

    #region Mutators
    #endregion

    #region Accessors
    #endregion
}
