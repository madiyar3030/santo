<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderByDesc('id')
            ->paginate(10);
        $users[0]->childrens = (object)[
            'id' => 1
        ];
//        dd($request);
        if ($request['sort'] === 'name') {
            $users = User::orderBy('name', 'asc')
                ->paginate(10);
        }
        if ($request['sort'] === 'antname') {
            $users = User::orderBy('name', 'desc')
                ->paginate(10);
        }
        if ($request['sort'] === 'created_at') {
            $users = User::orderBy('created_at')
                ->paginate(10);
        }
        if ($request['sort'] === 'ant_created_at') {
            $users = User::orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $users = User::orderBy('email_verified_at', 'desc')
            ->paginate(10);
        return view('admin.user.index', compact('users'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = $user->blocked == 1 ? 0 : 1;
        $user->save();
        return back()->withMessage($user->blocked == 1 ? 'Успешно заблокировано' : 'Успешно разблокировано');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return back()->withMessage('Успешно удалено');
    }
}
