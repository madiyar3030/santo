<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Carbon\CarbonInterval;
use Dotenv\Regex\Result;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $record = Record::where('playlist_id', $request['record_id'])->get();
        return view('admin.record.index',['playlist_id' => $request['record_id'], 'records' => $record]);
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
        $record = new Record();
        $record['title'] = $request['title'];
        $record['playlist_id'] = $request['playlist_id'];
        if ($request->file('image')) {
            $record['image'] = $this->upload($request['image'], 'records');
        }
        if ($request->file('url')) {
            $file = $request->file('url');
            try {
                $audio = new \wapmorgan\Mp3Info\Mp3Info($file, true);
                $duration = CarbonInterval::seconds((int)$audio->duration)->cascade()->format('%H:%I:%S');
            }
            catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Incorrect audio format']);
            }
            $record['url'] = $this->upload($request['url'], 'records');
            $record['play_time'] = $duration;
        }

        $record->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function show(Record  $record)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $record)
    {
        return view('admin.record.edit', ['record' => $record]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record  $record)
    {
        $record['title'] = $request['title'];
        if ($request->file('image')) {
            $record['image'] = $this->upload($request['image'], 'records');
        }
        if ($request->file('url')) {
            $file = $request->file('url');
            try {
                $audio = new \wapmorgan\Mp3Info\Mp3Info($file, true);
                $duration = CarbonInterval::seconds((int)$audio->duration)->cascade()->format('%H:%I:%S');
            }
            catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Incorrect audio format']);
            }
            $record['url'] = $this->upload($request['url'], 'records');
            $record['play_time'] = $duration;
        }

        $record->save();

        return redirect($request['redirects_to']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detail  $detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record  $record)
    {
        $record->delete();
        return redirect()->back();
    }
}
