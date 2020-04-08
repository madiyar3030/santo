<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Detailable;
use App\Traits\EnumValue;
use App\Traits\FavourableTrait;
use App\Traits\Imageable;
use App\Traits\Shareable;
use App\Traits\Taggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Traits\EnumeratesValues;

class Event extends Model
{
    const types = [
        'forum','concert','conference','master-class','theater','fair','holiday','entertainment'
    ];
    use Commentable, Detailable, Taggable, Imageable, Shareable, FavourableTrait;
    protected $fillable = ['type_id', 'title', 'description', 'image', 'location', 'description', 'date_from', 'date_to', 'time_from', 'time_to'];
    protected $hidden = ['created_at', 'updated_at', 'pivot'];
    protected $with = ['type'];
    protected $appends = ['model_type', 'in_favourite'];
    protected $dates = ['date_from', 'date_to'];
    protected $casts = [
        'date_from' => 'date:Y-m-d',
        'date_to' => 'date:Y-m-d',
        'type_id' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub


        self::creating(function(Event $event) {
            if (!isset($event->attributes['published_at'])) {
                $event->published_at = Carbon::now();
            }
        });

        self::deleting(function(Event $event) {
            $details = $event->details()->get();
            foreach ($details as $detail) {
                $detail->delete();
            }
        });

    }

    #region Relations

    public function comment() {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('root_id')->orderBy('parent_id');
    }

    public function type() {
        return $this->belongsTo(EventType::class, 'type_id');
    }

    #endregion

    #region Accessors and Mutators

    public function setDateFromAttribute($value) {
        try {
            $date = Carbon::make($value);
            $this->attributes['date_from'] = $date;
            return $date;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function setDateToAttribute($value) {
        try {
            $date = Carbon::make($value);
            $this->attributes['date_to'] = $date;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /*public function getTypeAttribute($value) {
        return 'concert';
    }*/

    /*public function getTypeAttribute() {
        return $this->type()->first();
    }*/

    public function getTypeNameAttribute() {
        if (isset($this->attributes['type'])) {
            return trans('attributes.'.$this->type);
        }
        return '';
    }

    public function getModelTypeAttribute() {
        return array_search(self::class, Relation::$morphMap);
    }

    #endregion

    public function getRecommendationsAttribute() {
        $attributes = $this->attributes;
        $events = Event::where('date_from',  '>=', $attributes['date_from'])
            ->where('id', '<>', $attributes['id'])
            ->where('moderated', 1)
            ->orderBy('date_from')
            ->limit(4)
            ->get();
        return $events;
    }

    public function recommendations() {
        $attributes = $this->attributes;
        $events = Event::where('date_from',  '>=', $attributes['date_from'])
            ->where('id', '<>', $attributes['id'])
            ->where('moderated', 1)
            ->orderBy('date_from')
            ->limit(4)
            ->get();
        return $events;
    }

}
