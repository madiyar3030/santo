<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Detailable;
use App\Traits\FavourableTrait;
use App\Traits\Shareable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Vaccine extends Model
{
    use Commentable, Detailable, Shareable, FavourableTrait;
    protected $fillable = ['title', 'description', 'age_from', 'age_to', 'age_type'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['model_type', 'type_name', 'applies_to', 'date_name', 'in_favourite'];
    protected $casts = [
        'age_from' => 'string',
        'age_to' => 'string',
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::deleting(function(Vaccine $vaccine) {
            $details = $vaccine->details()->get();
            foreach ($details as $detail) {
                $detail->delete();
            }
        });
    }

    #region Relations

   /* public function getChildrenAttribute() {

    }*/

    #endregion

    #region Mutators
    #endregion

    #region Accessors

    public function getModelTypeAttribute() {
        return array_search(self::class, Relation::$morphMap);
    }


    public function getTypeNameAttribute() {
        $model_name = array_search(self::class, Relation::$morphMap);
        if (isset($this->attributes['type'])) {
            return trans('attributes.'.$this->type);
        }
        else if (isset($model_name)) {
            return trans('attributes.'.$model_name);
        }
        return '';
    }

    #endregion

    public function isAppliedTo2($birthDay) {
        switch ($this->age_type) {
            case 'day':
                $childAgeDays = $birthDay->diffInDays(Carbon::now());
                if (!is_null($this->age_from)) {
                    return $childAgeDays >= $this->age_from && $childAgeDays < $this->age_to;
                }
                else {
                    return $childAgeDays < $this->age_to;
                }
                break;
            case 'week':
                $childAgeWeeks = $birthDay->diffInWeeks(Carbon::now());
                if (!is_null($this->age_from)) {
                    return $childAgeWeeks >= $this->age_from && $childAgeWeeks < $this->age_to;
                }
                else {
                    return $childAgeWeeks < $this->age_to;
                }
                break;
            case 'month':
                $childAgeMonths = $birthDay->diffInMonths(Carbon::now());
                if (!is_null($this->age_from)) {
                    return $childAgeMonths >= $this->age_from && $childAgeMonths < $this->age_to;
                }
                else {
                    return $childAgeMonths < $this->age_to;
                }
                break;
            case 'year':
                $childAgeYears = $birthDay->diffInYears(Carbon::now());
                if (!is_null($this->age_from)) {
                    return $childAgeYears >= $this->age_from && $childAgeYears < $this->age_to;
                }
                else {
                    return $childAgeYears < $this->age_to;
                }
                break;
        }
        return false;
    }

    public function isAppliedTo($birthDay) {
        $age = null;
        $birthDay = Carbon::make($birthDay);
        switch ($this->age_type) {
            case 'day':
                $age = $birthDay->floatDiffInDays(Carbon::now());
                $age_from = $this->age_from;
                $age_to = $this->age_to;
                break;
            case 'week':
                $age = $birthDay->diffInWeeks(Carbon::now());
                $age_from = $this->age_from;
                $age_to = $this->age_to;
                break;
            case 'month':
                $age = $birthDay->floatDiffInMonths(Carbon::now());
                $age_from = $this->age_from;
                $age_to = $this->age_to;
                break;
            case 'year':
                //$age = round($birthDay->floatDiffInYears(Carbon::now()), 1, PHP_ROUND_HALF_DOWN);
                $age = $birthDay->floatDiffInYears(Carbon::now());
                $age_from = $this->age_from;
                $age_to = $this->age_to;
                break;
        }
        if (!is_null($age)) $age = round($age, 1);
        //dump($birthDay->longRelativeDiffForHumans(Carbon::now(), 6).' '.$this->age_type.' '.' '.$this->title.' '.$this->age_from.' '.$this->age_to);

        if (!is_null($age)) {
            //dump('age: '.' '.$age.' '.$this->age_type.'s '.' '.$this->title.' '.$this->age_from.' '.$this->age_to);
            if ($this->age_type == 'month') {
                if (!is_null($age_to)) {
                    return ($age >= $age_from || $age >= $age_from - 1) && $age < $age_to;
                }
                else {
                    $age = $this->numberOfDecimals($age_from) ? $age : floor($age);
                    return $age == $age_from || $age == $age_from - 1;
                }
            }
            if (!is_null($age_to)) {
                /*dump($age.' '.$age_from.' '.$age_to.' '.$this->title);
                dump($age >= $age_from && $age < $age_to);*/
                return $age >= $age_from && $age < $age_to;
            }
            else {
                $age = $this->numberOfDecimals($age_from) ? $age : floor($age);
                return $age == $age_from;
            }
        }
        return false;
    }

    public function getAppliesToAttribute() {
        $user = User::$currentUser;
        $children = $user->children;
        $arr = array();
        foreach ($children as $child) {
            $birthDate = Carbon::make($child->birth_date);
            if ($this->isAppliedTo($birthDate)) {
                array_push($arr, $child);
            }
        }
        return $arr;
    }

    public function getRecommendationsAttribute() {
        $vaccines = Vaccine::where('age_type', '>', $this->age_type)
            ->orWhere(function($query) {
                $query->where('age_type', '=', $this->age_type)
                    ->where('age_from', '>=', $this->age_from);
            })->where('id', '<>', $this->id)
            ->orderBy('age_type')->orderBy('age_from')
            ->limit(2)->get();
        return $vaccines;
    }

    public function getDateNameAttribute() {
        if (is_null($this->age_to)) {
            $date_name = trans_choice('attributes.date_single_choice.' . $this->age_type, $this->age_from);
        }
        else {
            $date_name = trans('attributes.date_from_to_choice.'.$this->age_type);
        }
        return $date_name;
    }

    public function recommendations() {
        $attributes = $this->attributes;
        $vaccines = Vaccine::where('age_type', '>', $attributes['age_type'])
            ->orWhere(function($query) use ($attributes) {
                $query->where('age_type', '=', $attributes['age_type'])
                    ->where('age_from', '>=', $attributes['age_from']);
            })->where('id', '<>',$attributes['id'])
            ->orderBy('age_type')->orderBy('age_from')
            ->limit(2)->get();
        return $vaccines;
    }

    function numberOfDecimals($value)
    {
        if ((int)$value == $value)
        {
            return 0;
        }
        else if (! is_numeric($value))
        {
            // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
            return false;
        }

        return strlen($value) - strrpos($value, '.') - 1;
    }

}
