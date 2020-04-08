<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedingCategory extends Model
{
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['title'];

    public function feedings() {
        return $this->hasMany(Feeding::class, 'category_id');
    }
}
