<?php

namespace App\Http\Controllers\v2\REST;

use App\Events\ForumCreated;
use App\Events\UserRegistered;
use App\FirebasePush;
use App\Mail\Authorization;
use App\Mail\ResetPassword;
use App\Models\Article;
use App\Models\Children;
use App\Models\Development;
use App\Models\Event;
use App\Models\Forum;
use App\Models\Notification;
use App\Models\Playlist;
use App\Models\Tag;
use App\Models\UserTag;
use App\Models\Favourable;
use App\Models\Vaccine;
use App\Models\Webview;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use http\Env\Response;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    #region Authorization

    public function signIn(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
            'device_token' => 'string',
            'device_type' => 'string|in:android,ios'
        ];

        $validator = $this->validator($request->only('email', 'password', 'device_type', 'device_token'), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $user = User::where('email', $request['email'])->where('password', $request['password'])->first();

        if ($user) {
            if ($user->blocked == 1) {
                $data['statusCode'] = 401;
                $data['message'] = 'User is blocked';
                $data['result'] = null;
                return response()->json($data, $data['statusCode']);
            }
            if ($user->email_verified_at == null) {
                $data['statusCode'] = 402;
                $data['message'] = 'Email not verified';
                $data['result'] = null;
                return response()->json($data, $data['statusCode']);
            }
            $user->device_token = $request['device_token'] ?? $user->device_token;
            $user->device_type = $request['device_type'] ?? $user->device_type;
            $user->save();
            $data = $user;
            $data['statusCode'] = 200;
        } else {
            $data['statusCode'] = 404;
            $data['message'] = 'invalid credentials';
            $data['result'] = null;
        }

        return response()->json($data, $data['statusCode']);
    }

    public function signUp(Request $request)
    {
        $rules = [
//            'phone' => 'required|string|min:10|max:10|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'image' => 'file|mimes:jpeg,png,jpg|max:16384',
            'name' => 'required|string|max:200',
            'last_name' => 'string|max:200',
            'password' => 'required|string|min:6',
//            'parent' => 'required|in:father,mother,none',
//            'pregnant' => 'required|boolean',
            'birth_date' => 'date_format:"Y-m-d"',
            'device_token' => 'string',
            'device_type' => 'string|in:android,ios'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $user = User::create([
            'email' => $request['email'],
            'thumb' => isset($request['image']) ? $this->upload($request['image']) : null,
            'name' => $request['name'],
            'last_name' => $request['lastName'],
            'password' => $request['password'],
            'parent' => 'none',
            'pregnant' => 0,
            'birth_date' => $request['birthDate'],
            'access_token' => Str::random(50),
            'device_token' => $request['device_token'],
            'device_type' => $request['device_type'],
            'email_verified_at' => Carbon::now()->toDateTimeString(),
        ]);

        Mail::to($user)->send(new Authorization($user));

        return response()->json($user, 200);
    }

    public function resendVerification(Request $request) {
        $rules = [
            'email' => 'required|email|exists:users,email'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = User::where('email', $request['email'])->first();
        if (!$user) return $this->Result(404, null, 'User not found');

        Mail::to($user)->send(new Authorization($user));

        return \response()->json(null, 200);
    }

    public function sendResetMail(Request $request) {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = User::where('email', $request['email'])->first();
        if (!$user) return $this->Result(404, null, 'Пользователь не найден');
        Mail::to($user)->send(new ResetPassword($user));
        return $this->Result(200, null, 'Success');
    }

    public function verifyEmail($token, Request $request) {
        $user = User::where('access_token', $token)->first();
        if (!$user) return 'Пользователь не найден';
        if ($user->email_verified_at) return view('authorization.resetPassword', ['success' => 'Вы уже подтвердили свой email! Можете закрыть страницу']);
        $user->email_verified_at = Carbon::now();
        $promocode = mt_rand(pow(10, 15), pow(10, 16)-1);
        $user->promocode = $promocode;
        $user->save();

        event(new UserRegistered($user));
        return view('authorization.resetPassword', ['success' => 'Верификация прошла успешно! Можете закрыть страницу']);
    }

    public function showResetPage($token, Request $request) {
        return view('authorization.resetPassword', ['token' => $request['token']]);
    }

    public function resetPassword(Request $request) {
        $rules = [
            'token' => 'required',
            'new_password' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return abort(400);

        $user = User::where('access_token', $request['token'])->first();
        if (!$user) return $this->Result(404, null, 'User not found');
        $user->password = $request['new_password'];
        $user->save();
        return view('authorization.resetPassword', ['success' => 'Пароль успешно изменен!']);
    }

    public function authenticate(Request $request)
    {

        $user = User::where('id', $request['currentUser']->id)
                    ->with('children')
                    ->with('tags')
                    ->first();

        return response()->json($user, 200);
    }

    public function forgotPassword(Request $request)
    {
        $rules = [
            'email' => 'required|exists:users,email'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }


    }

    public function changePassword(Request $request)
    {
        $rules = [
            'token' => 'required|exists:users,access_token',
            'password' => 'required|min:6'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $user = User::where('access_token', $request['token'])->first();
        $user->password = $request['password'];
        $user->save();

        return response()->json($user, 200);
    }

    /* public function verifyEmail(Request $request)
    {
        $rules = [
            'token' => 'required|exists:users,access_token'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $user = User::where('access_token', $request['token'])->first();
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->save();

        return response()->json($user, 200);
    }*/

    public function updateProfile(Request $request)
    {
        $uniqueEmail = $request->currentUser->email == $request['email'] ? '' : '|unique:users,email';
        $rules = [
            'email' => 'email'.$uniqueEmail,
            'image' => 'file|mimes:jpeg,png,jpg|max:16384',
            'name' => 'string|max:200',
            'last_name' => 'string|max:200',
            'password' => 'string|min:6',
            'parent' => 'in:father,mother,none',
            'pregnant' => 'boolean',
            'birth_date' => 'date_format:"Y-m-d"',
            'info' => 'string',
            'vk' => 'string|url',
            'instagram' => 'string|url',
            'facebook' => 'string|url',
            'show_children' => 'boolean'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $user = User::find($request['currentUser']->id);

        User::where('id', $request['currentUser']->id)->update([
            'email' => $request['email'] ?? $user->email,
            'thumb' => isset($request['image']) ? $this->upload($request['image']) : $user->thumb,
            'name' => $request['name'] ?? $user->name,
            'last_name' => $request['last_name'] ?? $user->last_name,
            'password' => $request['password'] ?? $user->password,
            'parent' => $request['parent'] ?? $user->parent,
            'pregnant' => $request['pregnant'] ?? $user->pregnant,
            'birth_date' => $request['birth_date'] ?? $user->birth_date,
            'info' => $request['info'] ?? $user->info,
            'vk' => $request['vk'] ?? $user->vk,
            'instagram' => $request['instagram'] ?? $user->instagram,
            'facebook' => $request['facebook'] ?? $user->facebook,
            'show_children' => $request['show_children'] ?? $user->show_children,
        ]);

        $data = User::find($request['currentUser']->id);

        return response()->json($data, 200);
    }

    #endregion

    #region Children

    public function getChildren(Request $request)
    {
        $data = Children::where('parent_id', $request['currentUser']->id)->orderByDesc('created_at')->get();
        return response()->json($data,200);
    }

    public function addChildren(Request $request)
    {
        $rules = [
            'image' => 'file|mimes:jpeg,png,jpg|max:16384',
            'name' => 'required|string|max:200',
            'gender' => 'required|in:male,female',
            'birthDate' => 'date',
            'parentDescription' => 'string'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        //dd($request->all());
        $children = Children::create([
            'parent_id' => $request['currentUser']->id,
            'thumb' => isset($request['image']) ? $this->upload($request['image'], 'children') : null,
            'name' => $request['name'],
            'gender' => $request['gender'],
            'birth_date' => $request['birthDate'],
        ]);

        return response()->json($children, 200);
    }

    public function editChildren($id, Request $request) {
        $rules = [
            'image' => 'file|mimes:jpeg,png,jpg|max:16384',
            'name' => 'string|max:200',
            'gender' => 'in:male,female',
            'birthDate' => 'date',
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $child = $request['currentUser']->children()->find($id);
        if (!$child) return $this->Result(404);


        $child->fill([
            'name' => $request['name'] ?? $child->name,
            'gender' => $request['gender'] ?? $child->gender,
            'birth_date' => Carbon::make($request['birthDate']) ?? $child->birth_date,
        ]);
        if ($request['image']) {
            if ($child['thumb']) {
                self::deleteFile($child->thumb);
            }
            $child->thumb = $this->upload($request['image'], 'children');
        }
        $child->save();
        return $child;
    }

    public function deleteChildren($id, Request $request)
    {
        $children = Children::find($id);
        if ($children) {
            if ($children->parent_id == $request['currentUser']->id) {
                $children->thumb == null ?: $this->deleteFile($children->thumb);
                $children->delete();
                return response()->json(null, 200);
            } else {
                return $this->Result(400, null, 'Ребенок не ваш');
            }
        } else {
            return $this->Result(404, null, 'Ребенок не найден');
        }
    }

    #endregion

    #region Tags

    public function getTags(Request $request) {
        return $request['currentUser']->tags;
    }

    public function addTags(Request $request)
    {
        $rules = [
            'tags' => 'array|exists:tags,id',
            'tags.*.id' => 'distinct',
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors()->first();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        UserTag::where('user_id', $request['currentUser']->id)->delete();
        $tags = [];
        foreach ($request['tags'] as $tag) {
            $tags[] = (array) [
                'tag_id' => $tag,
                'user_id' => $request['currentUser']->id
            ];
        }

        UserTag::insert($tags);

        $userTags = UserTag::join('tags', 'tags.id', 'user_tags.tag_id')
            ->where('user_tags.user_id', $request['currentUser']->id)
            ->select('tags.title')
            ->get();
        return response()->json($userTags, 200);
    }

    public function deleteTags($id, Request $request)
    {
        $tag = UserTag::where('tag_id', $id)->where('user_id', $request['currentUser']->id)->first();
        if (!$tag) return $this->Result(404, null, 'Тег не найден');
        $tag->delete();
        return response()->json($tag, 200);
    }

    #endregion

    public function addImageToArticle($id, Request $request)
    {
        $article = Article::find($id);
        $file = $request->file('image');
        if ($file) {
            $article->image = $this->upload($file);
            $article->save();
        }
        return $this->Result(200, $article);
    }

    public function addImageToForum($id, Request $request)
    {
        $forum = Forum::find($id);
        $file = $request->file('image');
        if ($file) {
            $forum->image = $this->upload($file);
        }
        return $this->Result(200, $forum);
    }

    public function createArticle()
    {
        $article = new Article();
        $article->type = 'article';
        $article->title = 'Статья';
        $article->published_at = null;
        $article->save();
    }

    #region Favourites
    //not used
    public function getFavourites(Request $request) {
        $rules = [
            'type' => [
                'required',
                Rule::in(Favourable::FAVOURABLE_TYPES),
            ],
        ];

        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = $request['currentUser'];
        $data = array();
        switch ($request['type']) {
            case 'article':
                $data = $user->articles()->orderByDesc('published_at')->paginate(20);
                break;
            case 'forum':
                $data = $user->forums()->orderByDesc('created_at')->paginate(20);
                break;
            case 'event':
                $data = $user->events()->orderByDesc('created_at')->paginate(20);
                break;
            case 'playlist':
                $data = $user->playlists()->orderByDesc('created_at')->paginate(20);
                break;
        }

        return response()->json($data,200);
    }
    //anymore
    public function addFavourites(Request $request) {
        $rules = [
            'id' => 'required|numeric',
            'type' => [
                'required',
                Rule::in(Favourable::FAVOURABLE_TYPES),
            ],
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $favourable = Favourable::firstOrCreate([
            'favourable_type' => $request['type'],
            'favourable_id' => $request['id'],
            'user_id' => $request['currentUser']->id,
        ]);
        return response()->json($favourable, 200);
    }

    public function deleteFavourites($id, Request $request) {
        $user = $request['currentUser'];
        $favourable = $user->favourables()->find($id);
        if (!$favourable) return $this->Result(404);
        $favourable->delete();
        return response()->json($favourable, 200);
    }

    #endregion

    public function Recommendation(Request $request) {
        $rules = [
            'model_id' => 'required',
            'model_type' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $model_type = $request['model_type'];
        $model = Relation::getMorphedModel($model_type);
        if (!$model) return $this->Result(400, null, 'Incorrect model');
        $post = $model::find($request['model_id']);
        $userId = $request['currentUser']->id;

        if ($post) {
            try {
                $recommendations = $post->recommendations;
            } catch(\Exception $e) {
                return $this->Result(404, null, 'There is no recommendations for this model');
            }
            if (is_null($recommendations)) return $this->Result(404, null, 'There is no recommendations for this model');
            return $recommendations;
        }
        return $this->Result(404, null, 'Post not found');
        /*if ($post) {
            if ($post->category_id) {

                $modelResults = $model::where('category_id', $post->category_id)->where('id', '<>', $post->id);
                if ($post->type) $modelResults = $modelResults->where('type', $post->type);

                $modelResults = $modelResults->inRandomOrder()->limit(3)->get();
            }
            else if ($request['model_type'] == 'vaccine') {
                $modelResults = Vaccine::where('age_type', '>', $post->age_type)
                    ->orWhere(function($query) use ($post) {
                        $query->where('age_type', '=', $post->age_type)
                            ->where('age_from', '>=', $post->age_from);
                    })->where('id', '<>', $post->id)->orderBy('age_type')->orderBy('age_from')->limit(2)->get();
            }
            else {
                $model_ids = UserTag::join('taggables', 'taggables.tag_id', 'user_tags.tag_id')
                    ->where('user_id', $userId)
                    ->where('taggable_type', $model_type)
                    ->where('taggable_id', '<>', $request['model_id'])
                    ->pluck('taggable_id');
                $modelResults = $model::whereIn('id', $model_ids)->inRandomOrder()->limit(3);

                if ($post->type) $modelResults = $modelResults->where('type', $post->type);

                $modelResults = $modelResults->get();
            }
            return $modelResults;
        }*/
        /*if ($post) {
            $results = [];
            try {
                $results = $post->recommendations();
            } catch (\Exception $e) {
                return $this->Result(404, null, 'Not found');
            }
            return $results;
        }*/
    }

    public function getUserById($id, Request $request) {
        $user = User::select(
            'id', 'show_children',
            'thumb', 'name', 'last_name',
            'email', 'parent', 'pregnant',
            'birth_date', 'info', 'vk',
            'instagram', 'facebook')
            ->find($id);
        $user->load('children');
        if (!$user->show_children) {
            $children = Children::whereRaw(\DB::raw('0 = 1'))->get();
            $user = $user->setRelation('children', $children);
        }
        $user->setHidden(['badge']);

        return $user;
    }

    public function getChildrenById($id, Request $request) {
        $user = User::find($id);
        $children = $user->show_children ? $user->children()->get() : [];
        return $children;
    }

    public function temporary() {
        $articles = Article::whereType('article')->limit(4)->get();
        $news = Article::whereType('news')->limit(4)->get();
        $events = Event::limit(4)->get();
        $forums = Forum::limit(4)->get();
        $playlists = Playlist::limit(4)->get();
    }

    public function sendMail(Request $request) {
        dd(Mail::to($request['currentUser'])->send(new ResetPassword($request['currentUser'])));

    }

    public function sendUserNotification(Request $request) {
        $user = $request['currentUser'];
        event(new UserRegistered($user));
    }

    public function createNotificationForVaccines(Request $request) {
        $count = 5;
        //$name = trans_choice('attributes.month_from', $count);
        //return $name;
        $users = User::where('blocked', 0)->with('children')->get();
        $vaccines = Vaccine::all();
        $developments = Development::all();
        foreach ($users as $user) {
            foreach ($user->children as $child) {
                $birthDate = Carbon::make($child->birth_date);
                foreach ($vaccines as $vaccine) {
                    $age = null;
                    switch ($vaccine->age_type) {
                        case 'day':
                            $age = $birthDate->diffInDays(Carbon::now());
                            break;
                        case 'week':
                            $age = $birthDate->diffInWeeks(Carbon::now());
                            break;
                        case 'month':
                            $age = $birthDate->diffInMonths(Carbon::now());
                            break;
                        case 'year':
                            $age = $birthDate->diffInYears(Carbon::now());
                            break;
                    }

                    if ($age && $age == $vaccine->age_from) {
                        $notification = Notification::firstOrNew([
                            'user_id' => $user->id,
                            'notificatable_type' => 'vaccine',
                            'notificatable_id' => $vaccine->id,
                            'unique_id' => $child->id,
                        ]);
                        $notification->title = $child->name.' нужно сделать прививку \''.$vaccine->title.'\'';
                        $notification->body = $child->name.' нужно сделать прививку \''.$vaccine->title.'\'';
                        $notification->save();
                    }
                }
                foreach ($developments as $development) {
                    $age = null;
                    switch ($development->age_type) {
                        case 'day':
                            $age = $birthDate->diffInDays(Carbon::now());
                            break;
                        case 'week':
                            $age = $birthDate->diffInWeeks(Carbon::now());
                            break;
                        case 'month':
                            $age = $birthDate->diffInMonths(Carbon::now());
                            break;
                        case 'year':
                            $age = $birthDate->diffInYears(Carbon::now());
                    }
                    if ($age && $age == $development->age_from) {
                        $notification = Notification::firstOrNew([
                            'user_id' => $user->id,
                            'notificatable_type' => 'development',
                            'notificatable_id' => $development->id,
                            'unique_id' => $child->id,
                        ]);
                        $notification->title = $child->name . ' нужно пройти осмотр \'' . $development->title . '\'';
                        $notification->body = $child->name . ' нужно пройти осмотр \'' . $development->title . '\'';
                        $notification->save();
                    }
                }
            }
        }



        return $this->Result(200, 'ok');



        //$user = $request['currentUser'];
        /*return response()->json('ok', 200);
        $users = User::where('blocked', 0)->with('children')->get();
        $vaccines = Vaccine::all();
        foreach ($users as $user)
        foreach ($user->children as $child) {
            $birthDate = Carbon::make($child->birth_date);
            foreach ($vaccines as $vaccine) {
                $notificationA = null;
                switch ($vaccine->age_type) {
                    case 'day':
                        $ageInDays = $birthDate->diffInDays(Carbon::now());
                        if ($ageInDays == $vaccine->age_from) {
                            $notification = Notification::firstOrNew([
                                'user_id' => $user->id,
                                'notificatable_type' => 'vaccine',
                                'notificatable_id' => $vaccine->id,
                            ]);
                            $notification->title = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notification->body = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notificationA = $notification;
                            //dd($notification);
                            //$notification->save();
                        }
                        break;
                    case 'week':
                        $ageInWeeks = $birthDate->diffInWeeks(Carbon::now());
                        if ($ageInWeeks == $vaccine->age_from) {
                            $notification = Notification::firstOrNew([
                                'user_id' => $user->id,
                                'notificatable_type' => 'vaccine',
                                'notificatable_id' => $vaccine->id,
                            ]);
                            $notification->title = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notification->body = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notificationA = $notification;
                            //dd($notification);
                            //$notification->save();
                        }
                        break;
                    case 'month':
                        $ageInMonths = $birthDate->diffInMonths(Carbon::now());
                        if ($ageInMonths == $vaccine->age_from) {
                            $notification = Notification::firstOrNew([
                                'user_id' => $user->id,
                                'notificatable_type' => 'vaccine',
                                'notificatable_id' => $vaccine->id,
                            ]);
                            $notification->title = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notification->body = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notificationA = $notification;
                            //dd($notification);
                            //$notification->save();
                        }
                        break;
                    case 'year':
                        $ageInYears = $birthDate->diffInYears(Carbon::now());
                        if ($ageInYears == $vaccine->age_from) {
                            $notification = Notification::firstOrNew([
                                'user_id' => $user->id,
                                'notificatable_type' => 'vaccine',
                                'notificatable_id' => $vaccine->id,
                            ]);
                            $notification->title = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notification->body = $child->name.' нужно пройти осмотр \''.$vaccine->title.'\'';
                            $notificationA = $notification;
                            //dd($notification);
                            //$notification->save();
                        }
                        break;
                }
                dump($notificationA);
            }
        }*/
    }

    public function sendFirebase(Request $request) {
        $user = $request['currentUser'];
        $rules = [
            'title' => 'required',
            'body' => 'required'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $result = FirebasePush::sendMessage($request['title'], $request['body'], $user);
        $res = json_decode($result);
        return $this->Result(200, $res);
    }
}
