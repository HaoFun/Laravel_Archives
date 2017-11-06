<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'api_token', 'password','avatar','confirmation_token','is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //判斷傳入的$model->user_id 與用戶ID是否相同
    public function owns($model)
    {
        return $this->id === $model->user_id;
    }

    //user與archives 一對多關係
    public function archives()
    {
        return $this->hasMany(Archives::class,'user_id');
    }

    //user與answers 一對多關係
    public function answers()
    {
        return $this->hasMany(Answers::class,'user_id');
    }

    //Follow Model
    public function follows()
    {
        return $this->belongsToMany(Archives::class,'user_archives','user_id','archive_id')->withTimestamps();
    }

    //Follow toggle function，toggle方法用於判斷資料庫裡是否有相同的內容，如果有刪除，否則創建
    public function followThis($archive)
    {
        return $this->follows()->toggle($archive);
    }

    //確認用戶是否有關注文章
    public function followed($archive)
    {
        return $this->follows()->where('archive_id',$archive)->count();
    }

    //User 與 User 多對多關係 (foreignPivoKey follower_id 彼此關注)
    public function followers()
    {
       return $this->belongsToMany(self::class,'followers','follower_id','followed_id')->withTimestamps();
    }

    //User 與 User 多對多關係 (foreignPivoKey followed_id 彼此關注)
    public function followersUser()
    {
        return $this->belongsToMany(self::class,'followers','followed_id','follower_id')->withTimestamps();
    }

    //User 與 User 多對多關係 toggle方法
    public function followThisUser($user)
    {
        return $this->followers()->toggle($user);
    }

    //重寫密碼重置信件發送方法
    public function sendPasswordResetNotification($user)
    {
        $data= [
            'name' => $user['user']->name,
            'url' =>url('password/reset',$user['token']),
        ];
        Mail::send('email.reset',$data, function ($message)
        {
            $message->from('howhow926@gmail.com', 'HaoFun');
            $message->to('howhow926@gmail.com')->subject('HaoFun!密碼重置'); //先寫成這樣，mailgun 僅提供測試而已，不能外發Mail
        });
    }
}
