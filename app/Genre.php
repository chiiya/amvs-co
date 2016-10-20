<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    
    public $timestamps = false; // Model does not have timestamps
    protected $table = 'genres';
    protected $fillable = ['name'];
    protected $hidden = ['pivot'];

    /**
     * Many-To-Many Relationship: One Genre has many AMVs.
     */
    public function amvs() {
        return $this->belongsToMany('App\AMV', 'amv_genre', 'genre_id', 'amv_id');
    }

}
