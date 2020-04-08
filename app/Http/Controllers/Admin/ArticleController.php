<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::with('tags')->join('authors','authors.id','articles.author_id')
            ->select('articles.*','authors.name as author')
            ->orderByDesc('id')
            ->paginate(5);
        /*$articles = Article::with('tags')
            ->orderByDesc('id')
            ->paginate(5);*/
        $tags = Tag::all();
        $authors = Author::whereNull('user_id')->get();
        //$authors = User::whereType('author')->select('id', 'name', 'last_name')->get();
        $slides = Article::where('show_main', 1)->get();
        return view('admin.article.index', ['articles' => $articles, 'authors' => $authors, 'tags' => $tags, 'slides' => $slides]);
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
        if ($request->file('pdf')) {
            $article['share_file_url'] = $this->upload($request['pdf'], 'articles');
        }
        if($request['type_author'] == '2'){
            //$author = new Author();
            $author = new Author();
            if ($request->file('thumb')) {
                $author['thumb'] = $this->upload($request['thumb'], 'authors');
            }
            $author['type'] = User::TYPE_AUTHOR;
            $author['name'] = $request['name'];
            $author['last_name'] = $request['last_name'];
            $author->save();
            $article->author_id = $author->id;
        }
        else{
            $article['author_id'] = $request['author_id'];
        }


        $article->save();
        if ($request['tag']) {
            $this->addTag($request['tag'], $article->id);
        }
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
        $authors = Author::whereNull('user_id')->get();
        //$authors = User::whereType('author')->select('id', 'name', 'last_name')->get();
        $tags = Tag::all();
        return view('admin.article.edit', ['article' => $article, 'page' => request()->get('page'), 'authors' => $authors, 'tags' => $tags]);
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
            $article['image'] = $this->upload($request['image'], 'articles');
        }
        if ($request->file('pdf')) {
            $article['share_file_url'] = $this->upload($request['pdf'], 'articles');
        }
        if($request['type_author'] == '2'){
            //$author = new Author($request->all());
            $author = new Author();
            if ($request->file('image')) {
                $author['thumb'] = $author->upload($request['thumb'], 'authors');
            }
            $author['type'] = User::TYPE_AUTHOR;
            $author['name'] = $request['name'];
            $author['last_name'] = $request['last_name'];
            $author->save();
            $article->author_id = $author->id;
        }
        else{
            $article['author_id'] = $request['author_id'];
        }
        $article->save();
        if ($request['tag']) {
            $this->addTag($request['tag'], $article->id);
        }
        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article, Request $request)
    {
//        if($request['deletetag']){
//            $tag = Taggable::all();
//            dd(intval($request['deletetag']), $tag);
//            Taggable::findOrFail(intval($request['deletetag']))->delete();
//            return redirect()->back();
//        }
////        intval($request['deletetag'])
//        else {
            $article->delete();
//        }
        return redirect()->back();
    }



    public function addTag($tags, $id){
        foreach ($tags as $tag){
            $taggeble = new Taggable();
            $taggeble['tag_id']=$tag;
            $taggeble['taggable_type']='article';
            $taggeble['taggable_id']=$id;
            $taggeble->save();
        }
    }

    public function removeTag(Request $request) {
        $rules = [
            'tag_id' => 'required',
            'article_id' => 'required'
        ];
        Validator::make($request->all(), $rules)->validate();
        $tag = Tag::find($request['tag_id']);
        if ($tag) {
            $article = Article::find($request['article_id']);
            if ($article) {
                $taggable = Taggable::where('tag_id', $tag->id)->where('taggable_type', 'article')->where('taggable_id', $article->id)->first();
                $taggable->delete();
            }
        }

        return redirect()->back();
    }

    public function addShow(Article $article) {
        //dd($article);
        $articlesCount = Article::where('show_main', 1)->count();
        if ($articlesCount > 4) {
            return redirect()->back()->withErrors(['error' => 'Вы можете добавлять не более 5-ти статей на главную']);
        }
        $article['show_main'] = 1;
        $article->save();
        return redirect()->back();
    }

    public function removeShow(Article $article) {
        $article['show_main'] = 0;
        $article->save();
        return redirect()->back();
    }

}
