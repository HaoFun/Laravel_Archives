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
        $user->is_active = true;
        $user->confirmation_token = str_random(40);  //驗證成功將token更換
        $user->save();
        Auth::login($user);
        flash('Email驗證成功!')->success();
        return redirect('/home');
    }
}
