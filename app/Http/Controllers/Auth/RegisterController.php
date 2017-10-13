<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'avatar'   => '/images/avatar/default.jpg',  //設置默認註冊頭像
            'confirmation_token'  => str_random(40),
            'password' => bcrypt($data['password']),
        ]);
        $this->sendVerifyEmailTo($user);
        return $user;
    }

    protected function sendVerifyEmailTo($user)
    {
        $data= [
            'name'=>$user->name,
            'email'=>$user->email,
            'confirmation_token'=>route('email.verify',$user->confirmation_token),
        ];
        Mail::send('email.verify',$data, function ($message)
        {
            $message->from('howhow926@gmail.com', 'HaoFUN.app');
            $message->to('howhow926@gmail.com')->subject('HaoFUN.app'); //先寫成這樣，mailgun 僅提供測試而已，不能外發Mail
        });
    }
}
