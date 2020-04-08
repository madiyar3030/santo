<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\User;
use Illuminate\Http\Request;

class ForumCatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forumCats = ForumCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.forumcat.index', ['forumCats' => $forumCats]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.note.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $forumCat = new ForumCategory();
        $forumCat['title'] = $request['title'];
        if ($request->file('image')) {
            $forumCat['image'] = $this->upload($request['image'], 'forums');
        }
        $forumCat->save();
        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(ForumCategory $forumCat)
    {
        return view('admin.forumcat.edit', ['forumCat' => $forumCat, 'page' => request()->get('page')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ForumCategory $forumCat)
    {
        $forumCat['title'] = $request['title'];
        if ($request->file('image')) {
            $forumCat['image'] = $this->upload($request['image'], 'forums');
        }
        $forumCat->save();
        return redirect($request['redirects_to'] ?? route('forumCats.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(ForumCategory $forumCat)
    {
        $forumCat->delete();
        return redirect()->back();
    }
}
