<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Detail;
use App\Models\Image;
use App\Models\Tag;
use App\Models\Author;
use App\Models\ForumCategory;
use App\Models\Taggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForumModerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forums = Forum::orderByDesc('id')->where('moderated', 0)->paginate(15);
        $tags = Tag::all();
        return view('admin.moderation.forum', ['forums' => $forums, 'tags' => $tags]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */

    public function edit(Forum $forum)
    {
        $tags = Tag::all();
        $authors = Author::whereNull('user_id')->get();
        $author = $forum->author_rs;
        $cats = ForumCategory::get();
        return view('admin.moderation.editForum', ['forum' => $forum, 'tags' => $tags, 'authors' => $authors, 'ForumAuthor' => $author, 'cats' => $cats]);
    }


    public function update(Request $request, Forum $forum)
    {
        $forum['moderated'] = 1;
        $forum['title'] = $request['title'];
        $forum['category_id'] = $request['cat_id'];
        $detail = Detail::where('detailable_id', $forum->id)->where('type', 'description')->first();
        if($detail){
            $detail['value'] = $request['description'];
            $detail->save();
        }

        if ($request->file('image')) {
            $forum['image'] = $this->upload($request['image'], 'forums');
        }
        $forum->save();
        return redirect($request['redirects_to'] ?? route('forummods.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $forum = Forum::find($id);
        if ($forum) {
            $forum->delete();
        }
        return redirect()->back();
    }


}
