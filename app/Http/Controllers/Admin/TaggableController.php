<?php

namespace App\Http\Controllers\Admin;

use App\Models\Taggable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaggableController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Taggable::findOrFail($id)->delete();
        return back()->withMessage('Успешно удалено');
    }
}
