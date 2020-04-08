<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Tag;
use App\Models\ForumCategory;
use App\Models\Author;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request['id']){
            $forums = Forum::where('category_id', $request['id'])->where('moderated', 1)
//            ->join('authors','authors.id','forums.author_id')
//            ->select('forums.*','authors.name as author')
                ->orderBy('created_at', 'desc')
                ->paginate(5);
            $authors = Author::whereNull('user_id')->get();
            $tags = Tag::all();
            return view('admin.forum.index', ['forums' => $forums->appends($request->except('page')), 'tags' => $tags, 'authors' => $authors, 'cat_id' => $request['id']]);
        }
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
        $forum = new Forum($request->all());
        if ($request['image']) {
            $forum->image = $this->upload($request['image'], 'forums');
        }
        if ($request->file('pdf')) {
            $forum['share_file_url'] = $this->upload($request['pdf'], 'forums');
        }
        $forum['moderated'] = 1;
        $forum['author_id'] = $request['author_id'];
        $forum['category_id'] = $request['category_id'];
        $forum->save();
        if ($request['tag']) {
            foreach ($request['tag'] as $tag) {
                $forum->tags()->attach($tag);
            }
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function show(Forum $forum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function edit(Forum $forum)
    {
        $tags = Tag::all();
        $authors = Author::whereNull('user_id')->get();
        $author = $forum->author_rs;
        $cats = ForumCategory::get();
        return view('admin.forum.edit', ['forum' => $forum, 'tags' => $tags, 'authors' => $authors, 'ForumAuthor' => $author, 'cats' => $cats]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Forum $forum)
    {
        $forum->fill($request->all());
        $forum['author_id'] = $request['author_id'];
        $forum['category_id'] = $request['cat_id'];
        if ($request['image']) {
            if ($forum['image']) $this->deleteFile($forum['image']);
            $forum['image'] = $this->upload($request['image'], 'forums');
        }
        if ($request->file('pdf')) {
            $forum['share_file_url'] = $this->upload($request['pdf'], 'forums');
        }
        if ($request['tag']) {
            foreach ($request['tag'] as $tag) {
                $forum->tags()->attach($tag);
            }
        }
        $forum->save();
        return redirect($request['redirects_to'] ?? route('forums.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forum $forum)
    {
        $forum->delete();
        return redirect()->back();
    }

    public function removeTag(Request $request) {
        $rules = [
            'tag_id' => 'required',
            'forum_id' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        $forum = Forum::find($request['forum_id']);
        $forum->tags()->detach($request['tag_id']);
        return redirect()->back();
    }
}
