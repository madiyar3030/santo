<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vaccines = Vaccine::orderBy('created_at', 'desc')
//            ->orderBy('age_type')
//            ->orderBy('age_from')
            ->paginate(5);
        return view('admin.vaccine.index', ['vaccines' => $vaccines]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vaccine.create');
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
        $vaccine = new Vaccine($request->all());
        if ($request->file('image')) {
            $vaccine['image'] = $this->upload($request['image'], 'vaccines');
        }
        if ($request->file('pdf')) {
            $vaccine['share_file_url'] = $this->upload($request['pdf'], 'vaccines');
        }
        $vaccine->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vaccine  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function show(Vaccine $vaccine)
    {
        $vaccine->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.vaccine.show', ['vaccine' => $vaccine]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vaccine  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function edit(Vaccine $vaccine)
    {
        return view('admin.vaccine.edit', ['vaccine' => $vaccine, 'page' => request()->get('page')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vaccine  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vaccine $vaccine)
    {
        $vaccine->fill($request->all());
        if ($request->file('image')) {
            $vaccine['image'] = $this->upload($request['image'], 'vaccines');
        }
        if ($request->file('pdf')) {
            $vaccine['share_file_url'] = $this->upload($request['pdf'], 'vaccines');
        }
        $vaccine->save();
        return redirect($request['redirects_to'] ?? route('vaccines.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vaccine  $vaccine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vaccine $vaccine)
    {
        $vaccine->delete();
        return redirect()->back();
    }
}
