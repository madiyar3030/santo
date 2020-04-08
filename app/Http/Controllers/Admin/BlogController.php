<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Author;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::orderByDesc('id')
            ->paginate(5);
        $authors = Author::whereNull('user_id')->get();
        //$authors = User::whereType(User::TYPE_AUTHOR)->get();
        return view('admin.blog.index', ['blogs' => $blogs, 'authors' => $authors,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $blog = new Blog();
        $blog['title'] = $request['title'];
        $blog['online_until'] = $request['online_until'];
        $blog['online_from'] = $request['online_from'];
        $blog['online_to'] = $request['online_to'];
        $blog['author_id'] = $request['author_id'];
        if ($request->file('image')) {
            $blog['image'] = $this->upload($request['image'], 'blogs');
        }
        if ($request->file('pdf')) {
            $blog['share_file_url'] = $this->upload($request['pdf'], 'blogs');
        }
        $blog->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $article->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.blog.show', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $authors = Author::whereNull('user_id')->get();
        //$authors = User::whereType(User::TYPE_AUTHOR)->get();
        $author = $blog->author;
        //$author = $blog->author_rs;
        return view('admin.blog.edit', ['blog' => $blog, 'authors' => $authors, 'ForumAuthor' => $author]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $blog['title'] = $request['title'];
        $blog['description'] = $request['description'];
        $blog['author_id'] = $request['author_id'];
        $blog['online_until'] = $request['online_until'];
        $blog['online_from'] = $request['online_from'];
        $blog['online_to'] = $request['online_to'];
        if ($request->file('image')) {
            $blog['image'] = $this->upload($request['image'], 'blogs');
        }
        if ($request->file('pdf')) {
            $blog['share_file_url'] = $this->upload($request['pdf'], 'blogs');
        }
        $blog->save();
        return redirect($request['redirects_to'] ?? route('blogs.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->back();
    }

}
