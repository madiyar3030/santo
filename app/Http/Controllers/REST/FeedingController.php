<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\Feeding;
use App\Models\FeedingCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedingController extends Controller
{

    public function getRecipes(Request $request) {
        $rules = [
            'category_id' => 'required|numeric',
            'child_ids' => 'array',
            'child_ids.*' => 'numeric',
            //'type' => 'required|in:recipe,feeding,breastfeeding',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = $request['currentUser'];
        $feedings = Feeding::where('type', 'recipe')->orderBy('age_type')->orderBy('age_from')->where('category_id', $request['category_id'])->paginate(10);
        $collection = $feedings->getCollection();

        if ($request['child_ids']) {
            $children = $user->children()->find($request['child_ids']);
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);

                $collection = $collection->filter(function ($value, $key) use ($birthDay) {
                    return $value->isAppliedTo($birthDay);
                });
            }
        }

        //$children = $user->children;

        /*foreach ($collection as $feeding) {
            $applies_to = array();
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);
                if ($feeding->isAppliedTo($birthDay)) {
                    array_push($applies_to, $child);
                }
            }
            $feeding->applies_to = $applies_to;
        }*/
        $feedings = $feedings->setCollection($collection->values());
        return response()->json($feedings);
    }

    public function getFeedings(Request $request) {
        $rules = [
            'child_ids' => 'array',
            'child_ids.*' => 'numeric',
            'type' => 'required|in:feeding,breastfeeding',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = $request['currentUser'];
        $feedings = Feeding::where('type', $request['type'])->orderBy('age_type')->orderBy('age_from')->paginate(20);
        $collection = $feedings->getCollection();

        if ($request['child_ids']) {
            $children = $user->children()->find($request['child_ids']);
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);

                $collection = $collection->filter(function ($value, $key) use ($birthDay, $child) {
                    return $value->isAppliedTo($birthDay);
                });
            }
        }

        //$children = $user->children;

        /*foreach ($collection as $feeding) {
            $applies_to = array();
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);
                if ($feeding->isAppliedTo($birthDay)) {
                    array_push($applies_to, $child);
                }
            }
            $feeding->applies_to = $applies_to;
        }*/
        $feedings = $feedings->setCollection($collection->values());
        return response()->json($feedings);
    }

    public function getBreastFeedings(Request $request) {
        $rules = [
            'child_id' => 'numeric'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = $request['currentUser'];
        $feedings = Feeding::where('type', 'breastfeeding')
            ->where('category_id', $request['category_id'])
            ->orderBy('age_type')
            ->orderBy('age_from')
            ->paginate(10);

        $collection = $feedings->getCollection();
        if ($request['child_id']) {
            $child = $user->children()->find($request['child_id']);
            $birthDay = Carbon::make($child->birth_date);

            $collection = $collection->filter(function ($value, $key) use ($birthDay) {
                return $value->isAppliedTo($birthDay);
            });
        }
        $feedings = $feedings->setCollection($collection->values());

        return response()->json($feedings);
    }

    public function feedingCategories() {
        $categories = FeedingCategory::all();
        return response()->json($categories);
    }

    public function details($id, Request $request) {
        $children = $request['currentUser']->children;
        $feeding = Feeding::with('details')->find($id);
        $applies_to = [];
        foreach ($children as $child) {
            $birthDay = Carbon::make($child->birth_date);
            if ($feeding->isAppliedTo($birthDay)) array_push($applies_to, $child);
        }
        $feeding->applies_to = $applies_to;
        if (!$feeding) return $this->Result(404);

        return response()->json($feeding, 200);
    }
}
