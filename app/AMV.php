<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AMV extends Model
{
    protected $table = 'amvs';
    protected $fillable = ['title', 'genre', 'animes', 'music', 'description', 'poster', 'bg', 'video', 'user_id'];

    protected $hidden = ['user_id'];

    public function contests() {
        return $this->belongsToMany('App\Contest', 'amv_contest', 'amv_id', 'contest_id')
            ->withPivot('award as award');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
