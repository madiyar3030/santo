<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::leftJoin('event_types', 'event_types.id', 'events.type_id')
            ->select('events.*', 'event_types.title as event_title', 'event_types.color as event_color')
            ->orderByDesc('created_at')->where('moderated', 1)
            ->paginate(5);
        $type = EventType::get();
        return view('admin.event.index', ['types' => $type, 'events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event($request->all());
        if ($request->file('image')) {
            $event['image'] = $this->upload($request['image'], 'events');
        }
        if ($request->file('pdf')) {
            $event['share_file_url'] = $this->upload($request['pdf'], 'events');
        }
        if(is_null($request['date_to'])){
            $event['date_to'] = $event['date_from'];
        }
        $event['type_id'] = $request['type'];
        $event['moderated'] = 1;
        $event->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.event.show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $type = EventType::get();
        return view('admin.event.edit', ['types' => $type, 'event' => $event, 'page' => request()->get('page')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $event->fill($request->all());
        $event['type_id'] = $request['type'];
        if ($request->file('image')) {
            //if ($event['image']) $this->deleteFile($event['image']);
            $event['image'] = $this->upload($request['image'], 'events');
        }
        if ($request->file('pdf')) {
            $event['share_file_url'] = $this->upload($request['pdf'], 'events');
        }
        $event->save();
        return redirect($request['redirects_to'] ?? route('events.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back();
    }
}
