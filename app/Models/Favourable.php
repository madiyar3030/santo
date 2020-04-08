<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourable extends Model
{
    const FAVOURABLE_TYPES = [
        'article',
        'forum',
        'event',
        'playlist',
        'feeding',
        'consultation',
        'vaccine',
        'development',
        'blog',
        'record',
    ];

    protected $fillable = ['user_id', 'favourable_type', 'favourable_id'];
    protected $casts = ['favourable_id' => 'integer', 'id' => 'integer', 'favourite_id' => 'integer'];

    #region Relations

    public function forums()
    {
        return $this->morphedByMany(Forum::class, 'favourable');
    }

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'favourable')->whereType('article');
    }

    public function news()
    {
        return $this->morphedByMany(Article::class, 'favourable')->whereType('news');
    }

    public function playlists()
    {
        return $this->morphedByMany(Playlist::class, 'favourable');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'favourable');
    }

    #endregion

    #region Accessors
    #endregion

    #region Mutators
    #endregion

}
