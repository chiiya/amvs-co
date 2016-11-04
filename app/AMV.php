<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AMV extends Model
{
    protected $table = 'amvs';
    protected $fillable = ['title', 'animes', 'music', 'description', 'poster',
        'bg', 'video', 'videoHost', 'videoCode', 'download', 'driveId', 'published', 'user_id', 'url'];
    protected $hidden = ['user_id'];


    /**
     * Override default model create method in order to perform automated task before DB insert.
     * Generates a valid URL from the AMV title: 'Who is Björk?!' -> 'who_is_bjork'
     * URL is saved in the database
     */
    public static function create(array $attributes = array())
    {
        $title = $attributes['title'];

        setlocale(LC_ALL, 'en_US.UTF8');
        $replace=array();
        $delimiter='-';
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        $attributes['url'] = $clean;

        return parent::create($attributes);
    }


    /**
     * Many-To-Many Relationship: One AMV belongs to many genres.
     */
    public function genres() {
        return $this->belongsToMany('App\Genre', 'amv_genre', 'amv_id', 'genre_id');
    }

    /**
     * One-To-Many Relationship (Inverse): One AMV belongs to one user.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * One-To-Many Relationship: One AMV has many Awards.
     */
    public function awards()
    {
        return $this->hasMany('App\Award', 'amv_id');
    }
}
