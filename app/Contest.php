<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $table = 'contests';
    protected $fillable = ['name', 'year', 'creator_id', 'description', 'rules',
        'deadline', 'event_start', 'event_end'];

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

    /**
     * Many-to-Many Relationship: One Contest has many Users, with pivot indicating their role:
     * 1: Admin
     * 2: Judge
     * 3: Participant
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('role');
    }

    /**
     * Retrieve all admins for this contest
     */
    public function admins()
    {
        $users = $this->users;
        return $users->wherePivot('role', 1);
    }

    /**
     * Retrieve all judges for this contest
     */
    public function judges()
    {
        $users = $this->users;
        return $users->wherePivot('role', 2);
    }

    /**
     * Retrieve all participants for this contest
     */
    public function participants()
    {
        $users = $this->users;
        return $users->wherePivot('role', 3);
    }
}
