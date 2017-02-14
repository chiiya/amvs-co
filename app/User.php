<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'location', 'studio', 'website'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'created_at', 'updated_at'
    ];

    /**
     * One-To-Many Relationship: One user has many AMVs.
     */
    public function amvs()
    {
        return $this->hasMany('App\AMV', 'user_id');
    }

    /**
     * One-To-Many Relationship: One user has many Likes.
     */
    public function likes()
    {
        return $this->belongsToMany('App\AMV', 'likes', 'user_id', 'amv_id');
    }

    /**
     * Many-to-Many Relationship: One User has (or takes part in) many Contests, with pivot indicating their role:
     * 1: Admin
     * 2: Judge
     * 3: Participant
     */
    public function contests()
    {
        return $this->belongsToMany('App\Contest')->withPivot('role');
    }

    /**
     * Retrieve all contests where this user is admin
     */
    public function contestsAsAdmin()
    {
        $contests = $this->contests;
        return $contests->wherePivot('role', 1);
    }

    /**
     * Retrieve all contests where this user is judge
     */
    public function contestsAsJudge()
    {
        $contests = $this->contests;
        return $contests->wherePivot('role', 2);
    }

    /**
     * Retrieve all contests where this user is participant
     */
    public function contestsAsParticipant()
    {
        $contests = $this->contests;
        return $contests->wherePivot('role', 3);
    }
}
