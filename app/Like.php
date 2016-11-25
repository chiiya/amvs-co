<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    protected $fillable = ['amv_id', 'user_id'];

    /**
     * One-To-Many Relationship: One like belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * One-To-Many Relationship: One like belongs to one AMV.
     */
    public function amv()
    {
        return $this->belongsTo('App\AMV');
    }
}
