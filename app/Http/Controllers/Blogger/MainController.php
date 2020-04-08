<?php

namespace App\Http\Controllers\Blogger;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public function viewSignIn()
    {
        return view('blogger.sign_in');
    }

    public function signIn(Request $request)
    {
        $rules = [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return back()->withErrors($validator->errors());

        $blogger = User::where('email', $request['email'])
            ->where('password', $request['password'])
            ->first();

        if (!isset($blogger)) return back()->withErrors('Неправильный пароль или логин');

        session()->put('SlbtHR0pAqkGe0CK2JvO', 1);
        session()->put('admin', $blogger);
        session()->save();

        return redirect()->route('bloggers.index');
    }

    public function signOut(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('blogger.sign-in');
    }

    public function viewIndex()
    {
        return view('admin.index');
    }
}
