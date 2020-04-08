<?php

namespace App\Models;

use App\Http\Controllers\FileUploader;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const IMAGE = 'image';
    const CITATION = 'citation';


    protected $fillable = ['detailable_type', 'detailable_id', 'order', 'type', 'value'];
    protected $hidden = ['created_at', 'updated_at', 'detailable_type', 'detailable_id'];



    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::deleting(function(Detail $detail) {
            FileUploader::deleteFile($detail['value']);
        });
    }

    #region Relations

    public function detailable() {
        return $this->morphTo();
    }

    #endregion

    #region Mutators
    #endregion

    #region Accessors
    #endregion
}
