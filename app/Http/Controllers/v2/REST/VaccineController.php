<?php

namespace App\Http\Controllers\v2\REST;

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
        $vaccine = Vaccine::paginate(20);
        $collection = $vaccine->getCollection();
        if ($request['child_ids']) {
            $children = $user->children()->find($request['child_ids']);
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);

                $collection = $collection->filter(function ($value, $key) use ($birthDay) {
                    return $value->isAppliedTo($birthDay);
                });
            }
        }
        $collection = $collection->values();
        $vaccine->setCollection($collection);
        return $vaccine;
    }

    public function show($id, Request $request) {
        $vaccine = Vaccine::with('details')->find($id);
        return $vaccine->append('recommendations');
    }

}
