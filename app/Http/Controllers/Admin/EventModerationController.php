<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Detail;
use App\Models\User;
use Illuminate\Http\Request;
use function Couchbase\defaultDecoder;

class EventModerationController extends Controller
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
            ->where('moderated', 0)
            ->orderBy('created_at' ,'desc')
            ->paginate(5);
//        dd($events);
        return view('admin.moderation.event', ['events' => $events]);
    }


    public function edit(Event $event){
        $type = EventType::get();
        return view('admin.moderation.editEvent', ['types' => $type, 'event' => $event]);
    }

    public function update(Request $request, Event $event) {
        $event->fill($request->all());
        $event['moderated'] = 1;
        $event['type_id'] = $request['type'];
        if ($request->file('image')) {
           $event['image'] = $this->upload($request['image'], 'events');
        }
        $detail = Detail::where('detailable_type', 'event')->where('detailable_id', $event->id)->where('type', 'description')->first();
        $detail['value'] = $request['description'];
        $detail->save();

        $event->save();
        return redirect($request['redirects_to'] ?? route('eventmods.index'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete();
        }
        return redirect()->back();
    }
}
