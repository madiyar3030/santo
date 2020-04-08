<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['title', 'pivot_taggable_id'];
    protected $hidden = ['pivot', 'created_at', 'updated_at', 'forums', 'articles', 'news', 'playlists', 'events'];

    #region Relations

    public function forums() {
        return $this->morphedByMany(Forum::class, 'taggable');
    }

    public function articles() {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    public function news() {
        return $this->morphedByMany(Article::class, 'taggable')->whereType('news');
    }

    public function playlists() {
        return $this->morphedByMany(Playlist::class, 'taggable');
    }

    public function events() {
        return $this->morphedByMany(Event::class, 'taggable');
    }

    public function taggables($model) {
        return $this->morphedByMany($model, 'taggable');
    }

    public function object()
    {
        return $this->morphedByMany('taggable_type', 'taggable');
    }

    #endregion

    #region Mutators
    #endregion

    #region Accessors
    #endregion

}
