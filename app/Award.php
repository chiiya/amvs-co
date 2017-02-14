<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $table = 'awards';
    protected $fillable = ['award', 'amv_id', 'contest_id'];
    public $timestamps = false;

    /**
     * One-To-Many Relationship (Inverse): One Award belongs to one AMV.
     */
    public function amv() {
        return $this->belongsTo('App\AMV', 'amv_id');
    }

    /**
     * One-To-Many Relationship (Inverse): One Award belongs to one Contest.
     */
    public function contest() {
        return $this->belongsTo('App\Contest');
    }
}
