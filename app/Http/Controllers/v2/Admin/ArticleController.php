<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Models\Tag;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::join('authors','authors.id','articles.author_id')
            ->select('articles.*','authors.name as author')
            ->paginate(5);
        $tags = Tag::all();
        $authors = Author::whereNull('user_id')->get();
        return view('admin.article.index', ['articles' => $articles, 'authors' => $authors, 'tags' => $tags]);
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
        $article = new Article($request->all());
        if ($request->file('image')) {
            $article['image'] = $this->upload($request['image'], 'articles');
        }
        if ($request['tags']) {
            
        }
        if($request['type_author']){
            $author = new Author($request->all());
            if ($request->file('image')) {
                $author['thumb'] = $this->upload($request['thumb'], 'authors');
            }
            $author['name'] = $request['name'];
            $author['last_name'] = $request['last_name'];
            $author->save();
            $article->author_id = $author->id;
        }
        $article->save();
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
        return view('admin.article.show', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $authors = Author::all();
        return view('admin.article.edit', ['article' => $article, 'page' => request()->get('page'), 'authors' => $authors]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $article->fill($request->all());
        if ($request->file('image')) {
            //if ($article['image']) $this->deleteFile($article['image']);
            $article['image'] = $this->upload($request['image'], 'articles');
        }
        $article->save();
        return redirect($request['redirects_to'] ?? route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->back();
    }
}
