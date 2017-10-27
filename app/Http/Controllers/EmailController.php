<?php

namespace App\Http\Controllers;

use App\Repositoies\EmailRepository;
use Auth;

class EmailController extends Controller
{
    protected $emailRepository;

    /**
     * EmailController constructor.
     * @param EmailRepository $emailRepository
     */
    public function __construct(EmailRepository $emailRepository)
    {
        $this->middleware('guest');
        $this->emailRepository = $emailRepository;
    }

    /**
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verify($token)
    {
        $user = $this->emailRepository->getTokenByEmail($token);
        if(is_null($user)){
            flash('Email驗證失敗')->error();
            return redirect('/');
        }
        $user->update([
            'is_active' => true,
            'confirmation_token' => str_random(40)
        ]);
        Auth::login($user);
        flash('Email驗證成功!')->success();
        return redirect('/home');
    }
}
