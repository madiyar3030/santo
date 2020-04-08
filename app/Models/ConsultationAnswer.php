<?php

namespace App\Models;

use App\Traits\Authorable;
use App\Traits\Detailable;
use Illuminate\Database\Eloquent\Model;

class ConsultationAnswer extends Model
{
    use Authorable, Detailable;
    protected $hidden = ['created_at', 'updated_at'];
    protected $with = ['details'];
    protected $appends = ['author'];
    #region Relations
    #endregion

    #region Mutators
    #endregion

    #region Accessors
    #endregion
}
