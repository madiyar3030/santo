<?php

namespace App\Http\Controllers\v2\REST;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function show($id, Request $request) {
        $record = Record::find($id);
        if (!$record) return $this->Result(400, null, 'Аудио не найдено');
        return $record;
    }
}
