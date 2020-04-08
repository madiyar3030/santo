<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Detail;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function search(Request $request) {
        $rules = [
            //'model_type' =>
            'text' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $text = '%'.$request['text'].'%';
        $articles = Article::where('title', 'LIKE', $text)->orWhere('description', 'LIKE', $text)->paginate(20);
        return $articles;
    }

    public function getArticles(Request $request) {
        //\DB::enableQueryLog();
        $rules = [
            'tag_id' => 'numeric|exists:tags,id'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        //$tags = $request['currentUser']->tags()->with(['articles'])->get()->pluck('articles')->flatten()->unique();
        $tags = $request['currentUser']->tags()->get();
        if (count($tags)) {
            $articles = Article::join('taggables', 'taggables.taggable_id', 'articles.id')
                ->where('taggable_type', 'article')
                ->whereIn('tag_id', $tags->pluck('id'))
                ->select('articles.*')
                ->groupBy('taggable_id');
        }
        else {
            $articles = Article::join('taggables', 'taggables.taggable_id', 'articles.id')
                ->where('taggable_type', 'article')
                ->select('articles.*')
                ->groupBy('taggable_id');
        }
        if ($request['tag_id']) {
            $articles->where('tag_id', $request['tag_id']);
        }
        $articles->with([
            'details' => function($query) {
                $query->where('type', Detail::DESCRIPTION);
            }
        ]);
        //$articles = $tags->values();
        /*foreach ($tags as $tag) {
            $articles = $tag->articles->merge($articles);
        }*/
        /*if ($request['tag_id']) {
            $articles = $articles->filter(function($value, $key) use ($request) {
                return $value->pivot->tag_id == $request['tag_id'];
            });
        }*/
       return response()->json($articles->paginate(20), 200);
       //dd(\DB::getQueryLog());
    }

    //not used
    public function getArticlesv2(Request $request) {
        $rules = [
            'tag_id' => 'numeric|exists:tags,id'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $tags = $request['currentUser']->tags()->with(['articles'])->get();
        $articles = [];
        foreach ($tags as $tag) {
            $articles = $tag->articles->merge($articles);
        }
        if ($request['tag_id']) {
            $articles = $articles->filter(function($value, $key) use ($request) {
                return $value->pivot->tag_id == $request['tag_id'];
            });
        }
        return response()->json(['tags' => $tags, 'articles' => $articles], 200);
    }

    public function getArticle($id, Request $request) {
        $article = Article::with( 'tags', 'details')->find($id);
        return response()->json($article->append('author'), 200);
    }

}