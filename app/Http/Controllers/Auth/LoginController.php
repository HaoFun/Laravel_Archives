<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            flash('登入成功!')->success();
            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    //重寫登入認證，添加Users table is_active欄位，須為true
    protected function attemptLogin(Request $request)
    {
        $credentials = array_merge($this->credentials($request),['is_active'=>true]);
        return $this->guard()->attempt(
            $credentials, $request->filled('remember')
        );
    }

    //重寫credentials
    //使用Email or UserName 登入都可
    protected function credentials(Request $request)
    {
        //判斷$this->username() 是否為email格式，是的話$field為email，反之$field為name
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'name';
        return [
            $field     => $request->get($this->username()),
            'password' => $request->get('password')
        ];
    }
}
