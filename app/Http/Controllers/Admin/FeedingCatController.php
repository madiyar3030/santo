<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feeding;
use App\Models\FeedingCategory;
use App\Models\User;
use Illuminate\Http\Request;

class FeedingCatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = FeedingCategory::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.feedingcat.index', ['cats' => $cats]);
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
        $cat = new FeedingCategory();
        $cat['title'] = $request['title'];

        $cat->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function show(FeedingCategory $cat)
    {
        $cat->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.feedingcat.show', ['cat' => $cat]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedingCategory $feedingCategory)
    {
        return view('admin.feedingcat.edit', ['cat' => $feedingCategory, 'page' => request()->get('page')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeedingCategory $feedingCategory)
    {
        $feedingCategory->fill($request->all());
        $feedingCategory->save();
        return redirect($request['redirects_to'] ?? route('feedingcat.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedingCategory $feedingCategory)
    {
        if ($feedingCategory->feedings()->count() > 0) {
            return redirect()->back()->withErrors(['delete' => 'Вы не можете удалить категорию у которой есть рецепты']);
        }
        $feedingCategory->delete();
        return redirect()->back();
    }
}
