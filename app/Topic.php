<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected $fillable = ['title', 'author_id', 'description', 'goal', 'start_date', 'end_date', 'archived'];
    protected $appends = ['artefactCount', 'contributorCount', 'active'];

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function artefacts() {
        return $this->hasMany('App\Artefact');
    }

    // number of artefacts in a topic
    public function artefactCountRelation() {
        return $this->hasOne('App\Artefact')
            ->selectRaw('topic_id, count(*) as count')
            ->groupBy('topic_id');
    }

    public function getArtefactCountAttribute(){
        if(!$this->artefactCountRelation) return 0;
        return $this->artefactCountRelation->count;
    }

    // contributors in a topic
    public function contributors() {
        return $this->artefacts()
            ->leftJoin('users', 'artefacts.author_id', '=', 'users.id')
            ->select('users.*')
            ->groupBy('users.id');
    }

    // count (without loading) contributors in a topic
    public function contributorCountRelation() {
        return $this->artefacts()
            ->leftJoin('users', 'artefacts.author_id', '=', 'users.id')
            ->selectRaw('count(*) as count')
            ->groupBy('users.id');
    }

    public function getContributorCountAttribute(){
        if(!$this->contributorCountRelation) return 0;
        return $this->contributorCountRelation->count();
    }

    public function lastAddition() {
        return $this->hasOne('App\Artefact')
            ->orderBy('artefacts.created_at', 'DESC')
            ->limit(1);
    }

    public function firstAddition() {
        return $this->hasOne('App\Artefact')
            ->orderBy('artefacts.created_at', 'ASC')
            ->limit(1);
    }

    public function instructions(){
        return $this->hasMany('App\Instruction');
    }

    public function activeInstruction() {
        return $this->hasOne('App\Instruction')
            ->where('instructions.active_until', '=', null);
    }

    public function getActiveAttribute(){
        return (strtotime($this->start_date) < strtotime('now') && strtotime($this->end_date) > strtotime('now'));
    }

}
