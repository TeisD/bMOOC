<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected $fillable = ['title', 'author', 'description', 'goal', 'start_date', 'end_date'];

    public function author() {
        return $this->belongsTo('App\User', 'author');
    }

    public function artefacts() {
        return $this->hasMany('App\Artefact', 'topic');
    }

    public function last_addition() {
        return $this->hasOne('App\Artefact', 'topic')
            ->orderBy('artefacts.created_at', 'DESC')
            ->limit(1);
    }

    public function instructions(){
        return $this->hasMany('App\Instruction', 'topic');
    }

    public function active_instruction() {
        return $this->hasOne('App\Instruction', 'topic')
            ->where('instructions.active_until', '=', null);
    }

}
