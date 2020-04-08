<?php

namespace App\Http\Controllers\v2\REST;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Detail;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Favourable;
use App\Models\Forum;
use App\Models\Image;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{

    public function search(Request $request) {
        $rules = [
            'text' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $text = '%'.$request['text'].'%';
        $events = Event::where('title', 'LIKE', $text)->orWhere('description', 'LIKE', $text)->paginate(20);
        return $events;
    }

    public function index(Request $request) {
        $rules = [
            'year' => '',
            'month' => '',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        if ($request['day']) {
            $date = Carbon::create($request['year'], $request['month'], $request['day'])->format('Y-m-d');
            //\DB::enableQueryLog();
            //$events = Event::where('date_to', '>=' ,$date)->where('date_from', '<=', $date)->where('moderated', 1)->get();
            //dd(\DB::getQueryLog());
            //$event = Event::find(20);
            //dd($events);
            $data = [
                'date' => $date,
                'events' => Event::where('date_to', '>=' ,$date)->where('date_from', '<=', $date)->where('moderated', 1)->get(),
            ];
            return $data;
        }

        $date = Carbon::create($request['year'], $request['month']);
        $endDate = Carbon::create($request['year'], $request['month'] + 1);
        $between = CarbonPeriod::create($date, $endDate->subDay());

        $dates = array();
        $events = Event::where('date_to', '>=' ,$date)->where('date_from', '<=', $endDate)->where('moderated', 1)->get();
        foreach ($between as $period) {
            $data = [
                'date' => $period->format('Y-m-d'),
                'events' => array(),
            ];
            foreach ($events as $event) {
                if ($event['date_from'] <= $period && $event['date_to'] >= $period) {
                    array_push($data['events'], $event->only('id', 'type', 'date_from', 'date_to'));
                }
            }
            if (count($data['events']) > 0) {
                array_push($dates, $data);
            }
        }

        return $dates;
    }

    public function show($id, Request $request) {
        $event = Event::find($id);
        if (!$event || $event->moderated == 0) return $this->Result(404, null, 'Event not found');
        $event->load([
            'images',
            'details',
        ]);
        return $event;

    }

    public function create(Request $request) {
        $rules = [
            'images' => 'array',
            'images.*' => 'image',
            'title' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'required',
            'type_id' => [
                'required',
                'exists:event_types,id'
            ],
            'description' => 'required|min:50',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $event = new Event($request->only('title', 'type_id', 'description', 'date_from', 'date_to', 'time_from', 'time_to'));
        $event->save();

        $details = $this->fillDetails($request['title'], $request['description'], $event->id);

        if ($request['images']) {
            $eventImages = $this->fillImages($request['images'], $event);
        }

        return $event;
    }

    /*public function getTypes() {
        $types = array();
        foreach (Event::types as $type) {
            $model = array(
                'type' => $type,
                'type_name' => trans('attributes.'.$type),
            );
            array_push($types, $model);
        }
        return response()->json($types, 200);
    }*/

    public function getTypes() {
        $types = EventType::all();
        return $types;
    }

    public function fillDetails($title, $description, $eventId) {
        $details = [];
        /*$detail = new Detail();
        $detail->detailable_type = 'event';
        $detail->detailable_id = $eventId;
        $detail->order = 1;
        $detail->type = Detail::TITLE;
        $detail->value = $title;
        $detail->save();
        array_push($details, $detail);*/
        $detail = new Detail();
        $detail->detailable_type = 'event';
        $detail->detailable_id = $eventId;
        $detail->order = 2;
        $detail->type = Detail::DESCRIPTION;
        $detail->value = $description;
        $detail->save();
        array_push($details, $detail);

        return $details;
    }

    public function fillImages($images, $event) {
        $imagesArr = array();
        foreach ($images as $image) {
            $imageModel = new Image();
            $imageModel->url = $this->upload($image, 'events');
            $imageModel->imageable_type = 'event';
            $imageModel->imageable_id = $event->id;
            $imageModel->save();
            array_push($imagesArr, $imageModel);
        }
        return $imagesArr;
        //$event->image = $this->upload($images[0], 'forums');
        //$event->save();
        /*foreach ($images as $image) {
            $imageModel = new Image();
            $imageModel->url = $this->upload($image, 'forums');
            $imageModel->imageable_type = 'forum';
            $imageModel->imageable_id = $forumId;
            $imageModel->save();
            array_push($imagesArr, $imageModel);

        }
        return $event;*/
    }
}
