<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\FeedingCategory;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Author::whereNull('user_id')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        /*$authors = User::whereType(User::TYPE_AUTHOR)
            ->orderBy('created_at', 'desc')
            ->paginate(15);*/
        return view('admin.author.index', ['authors' => $authors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.author.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$author = new Author();
        $author = new User();
        $author['type'] = User::TYPE_AUTHOR;
        $author['name'] = $request['name'];
        $author['last_name'] = $request['last_name'];
        if ($request['thumb']) {
            $author->thumb = $this->upload($request['thumb'], 'authors');
        }

        $author->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //$author = $user;
        $author->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.author.show', ['author' => $author]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        return view('admin.author.edit', ['author' => $author, 'page' => request()->get('page')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, Author $author)
    {
        $author->fill($request->all());
        $author['name'] = $request['name'];
        $author['last_name'] = $request['last_name'];
        if ($request['thumb']) {
            $author->thumb = $this->upload($request['thumb'], 'authors');
        }
        $author->save();
        return redirect($request['redirects_to'] ?? route('authors.index'));
    }*/

    public function update(Request $request, Author $author)
    {
        //$author = $user;
        $author->fill($request->all());
        $author['name'] = $request['name'];
        $author['last_name'] = $request['last_name'];
        if ($request['thumb']) {
            $author->thumb = $this->upload($request['thumb'], 'authors');
        }
        $author->save();
        return redirect($request['redirects_to'] ?? route('authors.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    /*public function destroy(Author $author)
    {
        try {
            $author->delete();
        }
        catch (QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'Вы не можете удалить автора у которого есть статьи и ответы']);
        }
        return redirect()->back();
    }*/

    public function destroy(Author $author)
    {
        //$author = $user;
        try {
            $author->delete();
        }
        catch (QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'Вы не можете удалить автора у которого есть статьи и ответы']);
        }
        return redirect()->back();
    }
}
