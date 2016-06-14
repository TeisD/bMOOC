<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model {

    protected $fillable = ['topic_id', 'active_from', 'active_until', 'author_id', 'type_id', 'title', 'content'];

    public function type() {
        return $this->belongsTo('App\Type');
    }

    public function availableTypes() {
        return $this->belongsToMany('App\ArtefactType', 'instructions_artefact_types', 'instruction_id', 'artefact_type_id');
    }

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public static function getCurrent($thread) {
        return Self::with(['available_types', 'instruction_type'])->where('thread', $thread)->where('active_from', '<=', date('Y-m-d H:i:s'))->where(function($q) {
                    $q->whereNull('active_until')->orWhere('active_until', '>=', date('Y-m-d H:i:s'));
                })->get()->first();
    }

}
