<?php

namespace App\Http\Controllers\v2\REST;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Consultation;
use App\Models\Detail;
use App\Models\FeedingCategory;
use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Image;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function search(Request $request) {
        $rules = [
            //'model_type' =>
            'text' => 'required',
            'category_id' => 'numeric|exists:forum_categories,id'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $text = '%'.$request['text'].'%';
        $forums = Forum::where('moderated', 1);
        $forums = $forums->where(function($query) use ($text) {
            $query->where('title', 'LIKE', $text)->orWhere('description', 'LIKE', $text);
        });
        if ($request['category_id']) $forums = $forums->where('category_id', $request['category_id']);

        $forums = $forums->paginate(20);
        return $forums;
    }

    public function index(Request $request) {
        //\DB::enableQueryLog();
        $rules = [
            'category_id' => 'numeric|exists:forum_categories,id'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $forums = Forum::with('tags')->where('moderated', 1);
        if ($request['category_id']) {
            $forums = $forums->where('category_id', $request['category_id']);
        }
        //$forums->with('details');
        $forums = $forums->paginate(20);
        return response()->json($forums);
        dd(\DB::getQueryLog());
    }

    public function details($id, Request $request) {
        $forum = Forum::find($id);
        if (!$forum || !$forum->moderated) return $this->Result(404, null, 'Форум не найден');
        $forum->append('author');
        $forum->load([
            'images',
            'tags',
            'details',
            'comments' => function ($query) {
                $query->limit(5);
            }
        ]);
        return response()->json($forum);
    }

    public function create(Request $request) {
        $rules = [
            'images' => 'array',
            'images.*' => 'image',
            'title' => 'required',
            'description' => 'required|min:50',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $forum = $this->fillForum($request['title'], $request['description'], $request['currentUser']->id);

        $details = $this->fillDetails($request['title'], $request['description'], $forum->id);


        if ($request['images']) {
            $forumImages = $this->fillImages($request['images'], $forum);
        }

        return $forum;
    }

    public function fillForum($title, $description, $userId) {
        $author = Author::firstOrCreate(['user_id' => $userId]);
        $forum = new Forum();
        $forum->title = $title;
        $forum->description = $description;
        $forum->author_id = $author->id;
        //$forum->author_id = $userId;
        $forum->save();
        return $forum;
    }

    public function fillDetails($title, $description, $forumId) {
        $details = [];
        $detail = new Detail();
        $detail->detailable_type = 'forum';
        $detail->detailable_id = $forumId;
        $detail->order = 1;
        $detail->type = Detail::TITLE;
        $detail->value = $title;
        $detail->save();
        array_push($details, $detail);
        $detail = new Detail();
        $detail->detailable_type = 'forum';
        $detail->detailable_id = $forumId;
        $detail->order = 2;
        $detail->type = Detail::DESCRIPTION;
        $detail->value = $description;
        $detail->save();
        array_push($details, $detail);

        return $details;
    }

    public function fillImages($images, $forum) {
        /*$forum->image = $this->upload($images[0], 'forums');
        $forum->save();*/
        $imagesArr = [];
        foreach ($images as $image) {
            $imageModel = new Image();
            $imageModel->url = $this->upload($image, 'forums');
            $imageModel->imageable_type = 'forum';
            $imageModel->imageable_id = $forum->id;
            $imageModel->save();
            array_push($imagesArr, $imageModel);
        }
        return $forum;
    }

    public function categories() {
        $forumCategories = ForumCategory::all();
        return $forumCategories;
    }
}
