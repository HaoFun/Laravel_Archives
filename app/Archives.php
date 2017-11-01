<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archives extends Model
{
    protected $fillable = ['title','body','user_id'];

    public function topics()
    {
        //withTimestamps 操作時自動填充created_at & updated_at
        //第二參數為table name
        return $this->belongsToMany(Topics::class,'archives_topics')->withTimestamps();
    }

    //archive與user 一對一關係
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    //archive與answers 一對多關係
    public function answers()
    {
        return $this->hasMany(Answers::class,'archive_id');
    }

    //scope方法 判斷archive is_hidden 欄位是否為false
    public function scopePublished($query)
    {
        return $query->where('is_hidden',false);
    }

}
