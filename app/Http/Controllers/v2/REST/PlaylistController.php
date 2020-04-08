<?php

namespace App\Http\Controllers\v2\REST;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Record;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index(Request $request) {
        $playlists = Playlist::withCount('records')->paginate(20);
        return $playlists;
    }

    public function show($id, Request $request) {
        $playlist = Playlist::with(['records', 'details'])->withCount('records')->find($id);
        if (!$playlist) return $this->Result(400, null, 'Playlist not found');
        return $playlist;
    }

    public function uploadMusic(Request $request) {
        phpinfo();
        $rules = [
            'playlist_id' => 'required',
            'audio' => 'required|file',
            'title' => 'required',
            'image' => 'image',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());
        $file = $request->file('audio');
        try {
            $audio = new \wapmorgan\Mp3Info\Mp3Info($file, true);
            $duration = CarbonInterval::seconds((int)$audio->duration)->cascade()->format('%I:%S');
        }
        catch (\Exception $e) {
            return $this->Result(400, null, 'Incorrect audio format');
        }
        $record = new Record();
        $record->playlist_id = $request['playlist_id'];
        $record->url = $this->upload($file, 'records/audios');
        $record->title = $request['title'];
        $record->play_time = $duration;
        if ($request['image']) $record->image = $this->upload($request['image'], 'records/images');
        $record->save();
        return $record;
    }
}
