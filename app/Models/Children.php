<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    protected $table = 'children';
    protected $fillable = [
        'birth_date',
        'parent_id',
        'thumb',
        'name',
        'gender',
    ];
    protected $hidden = ['updated_at', 'created_at', 'parent_id'];
    protected $appends = ['age', 'age_name'];
    //protected $casts = ['birth_date' => 'date:Y-m-d'];
    #region Relations
    #endregion

    #region Mutators

    public function setBirthDateAttribute($value) {
        try {
            $this->attributes['birth_date'] = Carbon::make($value);
        }
        catch (\Exception $e) {
            $this->attributes['birth_date'] = Carbon::now();
        }
    }

    #endregion

    #region Accessors
    public function getAgeAttribute() {
        $now = Carbon::now();
        $created = Carbon::make($this->birth_date ?? Carbon::now());
        $created->locale('ru');
        /*dd($created->diffForHumans([
            'parts' => 1,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE
        ]));*/
        $time = $created->diffInYears($now);
        if ($time == 0) $time = $created->diffInMonths($now);
        if ($time == 0) $time = $created->diffInWeeks($now);
        if ($time == 0) $time = $created->diffInDays($now);
        return $time;
    }

    public function getAgeTypeAttribute() {
        $now = Carbon::now();
        $created = Carbon::make($this->birth_date ?? Carbon::now());
        $created->locale('ru');
        $time = $created->diffInYears($now);
        $type = 'year';
        if ($time == 0) {
            $time = $created->diffInMonths($now);
            $type = 'month';
        }
        if ($time == 0) {
            $time = $created->diffInWeeks($now);
            $type = 'week';
        }
        if ($time == 0) {
            $time = $created->diffInDays($now);
            $type = 'day';
        }
        return $type;
    }

    public function getAgeNameAttribute() {
        $now = Carbon::now();
        $created = Carbon::make($this->birth_date ?? Carbon::now());
        $created->locale('ru');
        $time = $created->diffInYears($now);
        $type = 'year';
        $name = trans_choice('attributes.date_single_choice.'.$type, $time);
        if ($time == 0) {
            $time = $created->diffInMonths($now);
            $type = 'month';
            $name = trans_choice('attributes.date_single_choice.'.$type, $time);
        }
        if ($time == 0) {
            $time = $created->diffInWeeks($now);
            $type = 'week';
            $name = trans_choice('attributes.date_single_choice.'.$type, $time);
        }
        if ($time == 0) {
            $time = $created->diffInDays($now);
            $type = 'day';
            $name = trans_choice('attributes.date_single_choice.'.$type, $time);
        }
        return $name;
    }
    #endregion
}
