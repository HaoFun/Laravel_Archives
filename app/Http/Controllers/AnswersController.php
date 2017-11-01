<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswersRequest;
use App\Repositoies\AnswersRepository;
use Auth;

class AnswersController extends Controller
{
    protected $answersRepository;
    public function __construct(AnswersRepository $answersRepository)
    {
        $this->middleware(['auth']);
        $this->answersRepository = $answersRepository;
    }

    public function store(AnswersRequest $request,$archive)
    {
        $answer = $this->answersRepository->AnswerCreate([
            'user_id'    => Auth::user()->id,
            'archive_id' => $archive,
            'body'       => $request->get('body')
        ]);
        if($answer)
        {
            $answer->archives()->increment('answers_count');
        }
        alert()->success('回覆成功!');
        return back();
    }
}
