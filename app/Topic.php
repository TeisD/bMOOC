<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected $fillable = ['title', 'author_id', 'description', 'goal', 'start_date', 'end_date'];
    protected $appends = ['artefactsCount'];

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function artefacts() {
        return $this->hasMany('App\Artefact');
    }

    public function artefactsCountRelation() {
        return $this->hasOne('App\Artefact')
            ->selectRaw('topic, count(*) as count')
            ->groupBy('topic');
    }

    public function getArtefactsCountAttribute(){
        return $this->artefactsCountRelation->count;
    }

    public function lastAddition() {
        return $this->hasOne('App\Artefact', 'topic')
            ->orderBy('artefacts.created_at', 'DESC')
            ->limit(1);
    }

    public function instructions(){
        return $this->hasMany('App\Instruction');
    }

    public function activeInstruction() {
        return $this->hasOne('App\Instruction')
            ->where('instructions.active_until', '=', null);
    }

}
