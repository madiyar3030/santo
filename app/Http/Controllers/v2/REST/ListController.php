<?php

namespace App\Http\Controllers\v2\REST;

use App\FirebasePush;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Models\AboutUs;
use App\Models\Article;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Event;
use App\Models\Favourable;
use App\Models\Feeding;
use App\Models\FeedingCategory;
use App\Models\Forum;
use App\Models\Note;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Vaccine;
use App\Models\Webview;
use App\Traits\Commentable;
use Carbon\Carbon;
use Dotenv\Regex\Result;
use http\Env\Response;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class ListController extends Controller
{
    public function getFavouritesDynamic(Request $request)
    {
        $rules = [
            'type' => [
                'required',
                Rule::in(Favourable::FAVOURABLE_TYPES),
            ],
        ];

        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = $request['currentUser'];

        $model = Relation::$morphMap[$request['type']];

        if (!$model) return $this->Result(500, null, 'Server error');

        $data = $user->favourites($request['type'], $model)->paginate(20);
        $data->getCollection()->makeHidden('pivot');
        return response()->json($data,200);
    }

    public function getFavourableTypes() {
        $types = array();
        foreach (Favourable::FAVOURABLE_TYPES as $favourable_type) {
            $model = array(
                'type' => $favourable_type,
                'type_name' => trans('attributes.favourites.'.$favourable_type),
            );
            array_push($types, $model);
        }
        return response()->json($types, 200);
    }

    public function getNotifications(Request $request)
    {
        $data = Notification::where('user_id', $request['currentUser']->id)->paginate(10);
        return response()->json($data,200);
    }

    public function getTags()
    {
        return response()->json(Tag::all(), 200);
    }

    public function getAll(Request $request) {
        $user = $request['currentUser'];
        $children = $request['currentUser']->children()->get();
        $tags = $user->tags()->with([
                'articles' => function($query) {
                    $query->with('tags');
                },
                'news' => function($query) {
                    $query->with('tags');
                },
                'events',
                'forums',
                'playlists' => function($query) {
                    $query->orderByDesc('created_at');
                },
            ])->get();
        $vaccines = Vaccine::limit(2)->get();
        //$articles = $news = $events = $forums = $playlists = [];
        /*foreach ($tags as $tag) {
            $articles = $tag->articles->merge($articles);
            $news = $tag->news->merge($news);
            $events = $tag->events->merge($events);
            $forums = $tag->forums->merge($forums);
            $playlists = $tag->playlists->merge($playlists);
        }*/
        $articles = Article::with('tags')->where('show_main', 1)->orderByDesc('created_at')->limit(5)->get();
        $news = Article::with('tags')->orderByDesc('created_at')->limit(4)->get();
        $events = Event::where('moderated', 1)->where('date_to', '>=', Carbon::now())->orderBy('date_from')->limit(4)->get();
        $forums = Forum::limit(4)->where('moderated', 1)->get();
        $playlists = Playlist::limit(4)->get();
        /*foreach ($vaccines as $vaccine) {
            $applies_to = [];
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);
                switch ($vaccine->age_type) {
                    case 'day':
                        $childAgeDays = $birthDay->diffInDays(Carbon::now());
                        if ($childAgeDays >= $vaccine->age_from && $childAgeDays < $vaccine->age_to) {
                            array_push($applies_to, $child);
                        }
                        break;
                    case 'week':
                        $childAgeWeeks = $birthDay->diffInWeeks(Carbon::now());
                        if ($childAgeWeeks >= $vaccine->age_from && $childAgeWeeks < $vaccine->age_to) {
                            array_push($applies_to, $child);
                        }
                        break;
                    case 'month':
                        $childAgeMonths = $birthDay->diffInMonths(Carbon::now());
                        if ($childAgeMonths >= $vaccine->age_from && $childAgeMonths < $vaccine->age_to) {
                            array_push($applies_to, $child);
                        }
                        break;
                    case 'year':
                        $childAgeYears = $birthDay->diffInYears(Carbon::now());
                        if ($childAgeYears >= $vaccine->age_from && $childAgeYears < $vaccine->age_to) {
                            array_push($applies_to, $child);
                        }
                        break;
                }
            }
            $vaccine->applies_to = $applies_to;
        }*/
        /*foreach ($vaccines as $vaccine) {
            $applies_to = [];
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);
                if ($vaccine->isAppliedTo($birthDay)) {
                    array_push($applies_to, $child);
                }
                switch ($vaccine->age_type) {
                    case 'day':
                        $childAgeDays = $birthDay->diffInDays(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeDays >= $vaccine->age_from && $childAgeDays < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeDays == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                    case 'week':
                        $childAgeWeeks = $birthDay->diffInWeeks(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeWeeks >= $vaccine->age_from && $childAgeWeeks < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeWeeks == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                    case 'month':
                        $childAgeMonths = $birthDay->diffInMonths(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeMonths >= $vaccine->age_from && $childAgeMonths < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeMonths == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                    case 'year':
                        $childAgeYears = $birthDay->diffInYears(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeYears >= $vaccine->age_from && $childAgeYears < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeYears == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                }
            }
            $vaccine->applies_to = $applies_to;
        }*/
        $data = array(
            'tags' => $tags,
            'articles' => $articles,
            'news' => $news,
            'events' => $events,
            'forums' => $forums,
            'playlists' => $playlists,
            'vaccines' => $vaccines,
        );
        return response()->json($data, 200);
    }

    public function getAllv2(Request $request) {
        $user = $request['currentUser'];
        $children = $request['currentUser']->children()->get();
        $tags = $user->tags()->with([
            'articles' => function($query) {
                $query->with('tags');
            },
            'news' => function($query) {
                $query->with('tags');
            },
            'events',
            'forums',
            'playlists' => function($query) {
                $query->orderByDesc('created_at');
            },
        ])->get();
        $vaccines = Vaccine::limit(2)->get();
        $articles = $news = $events = $forums = $playlists = [];
        foreach ($tags as $tag) {
            $articles = $tag->articles->merge($articles);
            $news = $tag->news->merge($news);
            $events = $tag->events->merge($events);
            $forums = $tag->forums->merge($forums);
            $playlists = $tag->playlists->merge($playlists);
        }
        foreach ($vaccines as $vaccine) {
            $applies_to = [];
            foreach ($children as $child) {
                $birthDay = Carbon::make($child->birth_date);
                switch ($vaccine->age_type) {
                    case 'day':
                        $childAgeDays = $birthDay->diffInDays(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeDays >= $vaccine->age_from && $childAgeDays < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeDays == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                    case 'week':
                        $childAgeWeeks = $birthDay->diffInWeeks(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeWeeks >= $vaccine->age_from && $childAgeWeeks < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeWeeks == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                    case 'month':
                        $childAgeMonths = $birthDay->diffInMonths(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeMonths >= $vaccine->age_from && $childAgeMonths < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeMonths == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                    case 'year':
                        $childAgeYears = $birthDay->diffInYears(Carbon::now());
                        if (!is_null($vaccine->age_from)) {
                            if ($childAgeYears >= $vaccine->age_from && $childAgeYears < $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        else {
                            if ($childAgeYears == $vaccine->age_to) {
                                array_push($applies_to, $child);
                            }
                        }
                        break;
                }
            }
            $vaccine->applies_to = $applies_to;
        }
        $data = array(
            'tags' => $tags,
            'articles' => $articles,
            'news' => $news,
            'events' => $events,
            'forums' => $forums,
            'playlists' => $playlists,
            'vaccines' => $vaccines,
        );
        return response()->json($data,200);
    }

    public function getNote(Request $request) {
        $rules = [
            'model_type' => 'required'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $note = Note::where('noteable', $request['model_type'])->first();
        if (!$note) return $this->Result(404, null, 'Note not found');
        return $note;
    }

    public function getAboutUs(Request $request) {
        $about_us = AboutUs::first();
        return $about_us;
    }

    public function getDiscount(Request $request) {
        $user = $request['currentUser'];
        if (is_null($user->promocode_created_at) || is_null($user->promocode)) return $this->Result(404, null, 'Промокод не найден');
        if (Carbon::now() > Carbon::make($user->promocode_created_at)->addDay()) {
            /*$user->promocode = null;
            $user->promocode_expires_at = null;
            $user->save();*/
            return $this->Result(400, null, 'Срок промокода истёк');
        }
        //dd($user);
        return response()->json(new DiscountResource($user));
    }

    public function createDiscount(Request $request) {
        $user = $request['currentUser'];
        $max_month_count = 5;
        if (!is_null($user->promocode_created_at)) {
            $promocode_expires_at = Carbon::make($user->promocode_created_at)->addDay();
            if (Carbon::now() < $promocode_expires_at) return $this->Result(400, null, 'Текущий промокод все еще действителен');
        }
        if ($user->promocode_used_count >= $max_month_count) return $this->Result(400, null, 'Вы исчерпали месячный лимит промокодов');

        $user->promocode = mt_rand(pow(10, 15), pow(10, 16)-1);
        $user->promocode_created_at = Carbon::now();
        $user->promocode_used_count += 1;
        $user->save();
        return response()->json(new DiscountResource($user));
    }

    public function getPolicy() {
        $policy = Webview::whereKeyword('policy')->first();
        return $policy->only('html');
    }

}
