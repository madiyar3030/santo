<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $developments = Development::orderBy('created_at', 'desc')
            ->paginate(5);
        return view('admin.development.index', ['developments' => $developments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.development.create');
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
            'age_from' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        $development = new Development($request->all());
        if ($request->file('image')) {
            $development['thumb'] = $this->upload($request['image'], 'developments');
        }
        if ($request->file('pdf')) {
            $development['share_file_url'] = $this->upload($request['pdf'], 'developments');
        }
        $development->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Development  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function show(Development $development)
    {
        $development->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.development.show', ['development' => $development]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Development  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function edit(Development $development)
    {
        return view('admin.development.edit', ['development' => $development, 'page' => request()->get('page')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Development  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Development $development)
    {
        $development->fill($request->all());
        if ($request->file('image')) {
            $development['thumb'] = $this->upload($request['image'], 'developments');
        }
        if ($request->file('pdf')) {
            $development['share_file_url'] = $this->upload($request['pdf'], 'developments');
        }
        $development->save();
        return redirect($request['redirects_to'] ?? route('developments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Development  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Development $development)
    {
        $development->delete();
        return redirect()->back();
    }
}
