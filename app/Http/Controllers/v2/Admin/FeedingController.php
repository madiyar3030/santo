<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feeding;
use App\Models\FeedingCategory;
use App\Models\User;
use Illuminate\Http\Request;

class FeedingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedings = Feeding::with('category')->paginate(5);
        $categories = FeedingCategory::all();
        return view('admin.feeding.index', ['feedings' => $feedings, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.feeding.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feeding = new Feeding($request->all());
        if ($request->file('image')) {
            $feeding['image'] = $this->upload($request['image'], '$feedings');
        }
        $feeding->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function show(Feeding $feeding)
    {
        $feeding->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.feeding.show', ['feeding' => feeding]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function edit(Feeding $feeding)
    {
        $categories = FeedingCategory::all();
        return view('admin.feeding.edit', ['feeding' => $feeding, 'page' => request()->get('page'), 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feeding $feeding)
    {
        $feeding->fill($request->all());
        if ($request->file('image')) {
            //if ($feeding['image']) $this->deleteFile($feeding['image']);
            $feeding['image'] = $this->upload($request['image'], 'feedings');
        }
        $feeding->save();
        return redirect($request['redirects_to'] ?? route('feedings.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feeding $feeding)
    {
        $feeding->delete();
        return redirect()->back();
    }
}
