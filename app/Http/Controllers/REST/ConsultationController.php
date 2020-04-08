<?php

namespace App\Http\Controllers\REST;

use App\Events\ConsultationCreated;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Models\Consultation;
use App\Models\ConsultationAnswer;
use App\Models\Detail;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    public function search(Request $request) {
        $rules = [
            'text' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $text = '%'.$request['text'].'%';
        $consultations = Consultation::where('title', 'LIKE', $text)->orWhere('description', 'LIKE', $text)->where('moderated', 1)->paginate(20);
        return $consultations;
    }

    public function index() {
        $consultations = Consultation::with([
            'image_url',
            'tags'
        ])->where('moderated', 1)->paginate(20);
        return $consultations;
    }

    public function show($id, Request $request) {
        $consultation = Consultation::with([
            'details', 'tags', 'answer', 'image_url', 'images',
            'comments' => function($query) {
                $query->limit(10);
            }])->find($id);

        if (!$consultation || $consultation->moderated == 0) return $this->Result(404, null, 'Consultation not found');
        return $consultation->append('author');
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

        $consultation = $this->fillConsultation($request['title'], $request['description'], $request['currentUser']->id);

        $details = $this->fillDetails($request['title'], $request['description'], $consultation->id);

        if ($request['images']) {
            $consultationImages = $this->fillImages($request['images'], $consultation);
        }
        return $consultation;
    }

    public function fillConsultation($title, $description, $userId) {
        $author = Author::firstOrCreate(['user_id' => $userId]);
        $answer = ConsultationAnswer::create();
        $consultation = new Consultation();
        $consultation->title = $title;
        $consultation->description = $description;
        //$consultation->author_id = $author->id;
        $consultation->author_id = $author->id;
        $consultation->answer_id = $answer->id;
        $consultation->save();
        return $consultation;
    }

    /*public function createAuthor() {
    }*/

    public function fillDetails($title, $description, $consultationId) {
        $details = [];
        /*$detail = new Detail();
        $detail->detailable_type = 'consultation';
        $detail->detailable_id = $consultationId;
        $detail->order = 1;
        $detail->type = Detail::TITLE;
        $detail->value = $title;
        $detail->save();
        array_push($details, $detail);*/
        $detail = new Detail();
        $detail->detailable_type = 'consultation';
        $detail->detailable_id = $consultationId;
        $detail->order = 2;
        $detail->type = Detail::DESCRIPTION;
        $detail->value = $description;
        $detail->save();
        array_push($details, $detail);

        return $details;
    }

    public function fillImages($images, $consultation) {
        $imagesArr = array();
        foreach ($images as $image) {
            $imageModel = new Image();
            $imageModel->url = $this->upload($image, 'details');
            $imageModel->imageable_type = 'consultation';
            $imageModel->imageable_id = $consultation->id;
            $imageModel->save();
            array_push($imagesArr, $imageModel);
        }
        return $imagesArr;

        //$consultation->image = $this->upload($images[0], 'forums');
        //$consultation->save();
        /*foreach ($images as $image) {
            $imageModel = new Image();
            $imageModel->url = $this->upload($image, 'forums');
            $imageModel->imageable_type = 'forum';
            $imageModel->imageable_id = $forumId;
            $imageModel->save();
            array_push($imagesArr, $imageModel);

        }
        return $consultation;*/
    }

    public function approve(Consultation $consultation) {
        $consultation->show = 1;
        //$user = User::findOrFail($consultation->author_rs->user_id);
        $user = User::findOrFail($consultation->author_id);
        $consultation->save();
        event(new ConsultationCreated($consultation, $user));
        return response()->json(null, 200);
    }
}
