<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use Dotenv\Regex\Result;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rules = [
            'type' => 'required|string',
            'detailable_id' => 'required|numeric',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $model = Relation::$morphMap[$request['type']];
        $model = $model::with('details')->find($request['detailable_id']);
        if (!$model) abort(404);
        $details = Detail::where('detailable_type', $request['type'])
            ->where('detailable_id', $request['detailable_id'])
            ->orderBy('order')
            ->get();
        return view('admin.detail.index',['details' => $details, 'model' => $model]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:title,description,image,citation',
            'text' => [
                Rule::requiredIf(in_array($request['type'], ['title', 'description', 'citation'])),
            ],
            'image' => [
                Rule::requiredIf($request['type'] == 'image'),
            ],
            'order' => 'required|numeric',
        ];
        $this->validator($request->all(), $rules)->validate();
        $detail = new Detail($request->all());
        if ($request['type'] == 'image') {
            if (!$request->file('image')) return redirect()->back()->with(['error' => 'Вы не добавили картинку']);
            if ($detail['value']) $this->deleteFile($detail['value']);
            $detail['value'] = $this->upload($request['image'], 'details');
        }
        else {
            $detail->value = $request['text'];
        }

        $detail->saveOrFail();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function show(Detail $detail)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Detail $detail)
    {
        return view('admin.detail.edit', ['detail' => $detail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detail $detail)
    {
        $rules = [
            'type' => 'required|in:title,description,image,citation',
            'text' => [
                Rule::requiredIf(in_array($request['type'], ['title', 'description', 'citation'])),
            ],
            'image' => [
                Rule::requiredIf($request['type'] == 'image'),
            ],
            'order' => 'required|numeric',
        ];
        $this->validator($request->all(), $rules)->validate();

        if ($request['type'] == 'image') {
            if (!$request->file('image')) return redirect()->back()->with(['error' => 'Вы не добавили картинку']);
            $detail->fill($request->all());
            if ($detail['value']) $this->deleteFile($detail['value']);
            $detail['value'] = $this->upload($request['image'], 'details');
        }
        else {
            $detail->fill($request->all());
            $detail->value = $request['text'];
        }

        $detail->saveOrFail();

        return redirect($request['redirects_to']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detail $detail)
    {
        $detail->delete();
        return redirect()->back();
    }
}
