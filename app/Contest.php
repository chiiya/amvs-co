<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $table = 'contests';
    protected $fillable = ['name', 'year'];

    public function amvs() {
        return $this->belongsToMany('App\AMV', 'amv_contest', 'contest_id', 'amv_id')
            ->withPivot('award');
    }
}
