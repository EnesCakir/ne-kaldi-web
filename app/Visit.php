<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{

    protected $fillable = [
        'exam_id', 'platform', 'visitor_id',
    ];

    public function visitor(){
        return $this->belongsTo('App\Visitor');
    }

    public function exam(){
        return $this->hasOne('App\Exam');
    }

}