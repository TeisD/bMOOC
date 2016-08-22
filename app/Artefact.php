<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artefact extends Model {

    protected $fillable = ['parent_id', 'topic_id', 'author_id', 'title', 'type_id', 'content', 'notes', 'copyright'];
    protected $appends = ['has_children', 'has_parent'];


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
        return $this->belongsTo('App\Type');
    }

    public function topic() {
        return $this->belongsTo('App\Topic');
    }

    public function getHasChildrenAttribute(){
        return $this->children()->count() > 0;
    }

    public function getHasParentAttribute(){
        return $this->parent()->count() > 0;
    }

}
