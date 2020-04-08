<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    public function index(Request $request) {
        $rules = [
            'child_ids' => 'array',
            'child_ids.*' => 'numeric',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $user = $request['currentUser'];
        $vaccine = Vaccine::orderBy('age_type')->orderBy('age_from');
        $collection = $vaccine->get();
        if ($request['child_ids']) {
            $children = $user->children()->find($request['child_ids']);
            $collection = $collection->filter(function($value, $key) use ($children) {
                return count($children->intersect($value->applies_to));
            });
        }
        $collection = $collection->values();
        return $this->paginatedResult($request['page'] ?? 1, $collection);
    }


    public function show($id, Request $request) {
        $vaccine = Vaccine::with('details')->find($id);
        return $vaccine->append('recommendations');
    }



    //not used
    public function indexOld(Request $request) {
        $rules = [
            'child_ids' => 'array',
            'child_ids.*' => 'numeric',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $user = $request['currentUser'];
        $vaccine = Vaccine::orderBy('age_type')->orderBy('age_from')->paginate(100);
        $collection = $vaccine->getCollection();

        if ($request['child_ids']) {
            $children = $user->children()->find($request['child_ids']);
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);

                /*$collection = $collection->filter(function ($value, $key) use ($birthDay) {
                    return $value->isAppliedTo($birthDay);
                });*/
                /*$collection = $collection->filter(function($value, $key) use ($child) {
                    return in_array($child, $value->applies_to);
                });*/
            }
            $collection = $collection->filter(function($value, $key) use ($children) {
                return count($children->intersect($value->applies_to));
            });
        }
        $collection = $collection->values();
        $vaccine->setCollection($collection);
        return $vaccine;
    }

    public function paginatedResult(int $page, $data) {
        if ($page > 1) $data = [];
        $res = array(
            'current_page' => $page,
            'data' => $data,
            'first_page_url' => null,
            'from' => 0,
            'last_page' => 1,
            'last_page_url' => null,
            'next_page_url' => null,
            'per_page' => count($data),
            'prev_page_url' => null,
            'to' => count($data),
            'total' => count($data),
        );

        return $res;
    }

}
