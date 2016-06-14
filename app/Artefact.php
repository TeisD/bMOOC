<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artefact extends Model {

    protected $fillable = ['parent_id', 'topic_id', 'author_id', 'title', 'type_id', 'content', 'notes', 'copyright'];

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function children() {
        return $this->hasMany('App\Artefact', 'parent_id', 'id');
    }

    public function parent() {
        return $this->belongsTo('App\Artefact', 'parent_id');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'artefact_tags', 'artefact_id', 'tag_id');
    }

    public function type() {
        return $this->belongsTo('App\ArtefactType');
    }

    public function topic() {
        return $this->belongsTo('App\Topic');
    }

}
