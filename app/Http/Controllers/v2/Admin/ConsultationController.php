<?php

namespace App\Http\Controllers\Admin;

use App\Events\ConsultationCreated;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\User;
use App\Models\ConsultationAnswer;
use App\Models\Author;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultation = Consultation::join('authors','authors.id','consultations.author_id')
//            ->join('answers', 'answers.id', 'consultations.answer_id')
            ->select('consultations.*','authors.name as author')
            ->paginate(15);
        return view('admin.consultation.index', ['consultations' => $consultation]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.consultation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $consultation = new Consultation($request->all());
        if ($request->file('image')) {
            $consultation['image'] = $this->upload($request['image'], 'consultations');
        }
        $consultation->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function show(Consultation $consultation)
    {
        $consultation->load([
            'details' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.consultation.show', ['consultation' => $consultation]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function edit(Consultation $consultation)
    {
        $answers = ConsultationAnswer::where('id', $consultation['answer_id'])
            ->first();
        $authors = Author::whereNull('user_id')
            ->get();
        return view('admin.consultation.answer', ['authors' => $authors,  'answer' => $answers, 'consultation' => $consultation, 'page' => request()->get('page')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consultation $consultation)
    {
        $consultation->answer->author_id = $request['author_id'];
        $consultation->answer->save();
        return redirect($request['redirects_to'] ?? route('consultations.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();
        return redirect()->back();
    }

    public function approve(Consultation $consultation) {
        $consultation->show = 1;
        $user = User::findOrFail($consultation->author_rs->user_id);
        $consultation->save();
        event(new ConsultationCreated($consultation, $user));
        return redirect()->back();
    }
}
