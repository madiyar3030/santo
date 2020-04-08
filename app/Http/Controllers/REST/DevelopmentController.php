<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    public function index(Request $request) {
        $rules = [
            'child_ids' => 'array',
            'child_ids.*' => 'numeric',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $user = $request['currentUser'];
        $development = Development::orderBy('age_type')->orderBy('age_from')->paginate(20);
        $collection = $development->getCollection();
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
        $development->setCollection($collection);
        return $development;
    }

    public function show($id, Request $request) {
        $development = Development::with('details')->find($id);
        return $development->append('recommendations');
    }
}
