<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $table = 'contests';
    protected $fillable = ['name', 'year'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * One-To-Many Relationship: One Contest has many Awards.
     */
    public function awards()
    {
        return $this->hasMany('App\Award', 'amv_id');
    }
}
