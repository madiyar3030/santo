<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class User extends Model
{
    public static $currentUser;
    const TYPE_USER = 'user';
    const TYPE_AUTHOR = 'author';
    protected $fillable = [
        'email',
        'thumb',
        'name',
        'last_name',
        'password',
        'parent',
        'pregnant',
        'birth_date',
        'access_token',
        'info',
        'show_children',
        'device_token',
        'device_type',
        'push',
    ];

    protected $hidden = [
        'password', 'remember_token', 'promocode',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'promocode' => 'string'
    ];

    protected $appends = ['badge'];

    #region Relations

    public function children() {
        return $this->hasMany(Children::class, 'parent_id', 'id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'user_tags');
    }

    public function articles() {
        return $this->morphedByMany(Article::class, 'favourable')->select('articles.*', 'favourables.id as favourite_id', 'favourable_type as favourite_type');
    }

    public function forums() {
        return $this->morphedByMany(Forum::class, 'favourable')->select('forums.*', 'favourables.id as favourite_id', 'favourable_type as favourite_type');
    }

    public function events() {
        return $this->morphedByMany(Event::class, 'favourable')->select('events.*', 'favourables.id as favourite_id', 'favourable_type as favourite_type');
    }

    public function playlists() {
        return $this->morphedByMany(Playlist::class, 'favourable')->select('playlists.*', 'favourables.id as favourite_id', 'favourable_type as favourite_type');
    }

    public function favourites($type, $model) {
        $table = Str::plural($type);
        return $this->morphedByMany($model, 'favourable')->select($table.'.*', 'favourables.id as favourite_id', 'favourable_type as favourite_type');
    }

    public function favourables() {
        return $this->hasMany(Favourable::class);
    }

    public function notifications() {
        return $this->hasMany(Notification::class);
    }

    #endregion

    #region Mutators
    #endregion

    #region Accessors

    public function getDiscountAttribute() {
        $verifiedDate = $this->attributes['email_verified_at'];
        if ($verifiedDate) {
            $now = Carbon::now();
            $diffInMonths = $now->diffInMonths($verifiedDate);
            return Discount::where('months', '<=', $diffInMonths)->orderByDesc('months')->value('discount');
        }
        return null;
    }

    public function getBadgeAttribute() {
        return $this->notifications()->where('is_read', 0)->exists();
    }

    #endregion





    /*    public function favourites($type) {
            $table = Str::plural($type);
            return Favourable::join($table, $table.'.id', 'favourables.favourable_id')->where('favourable_type', $type)->where('user_id', $this->id)->select($table.'.*', 'favourables.id as favourite_id', 'favourable_type as favourite_type');
        }*/


}
