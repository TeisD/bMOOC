<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtefactType extends Model {
    protected $fillable = ['type'];

	public function artefacts() {
		return $this->hasMany('App\Artefact', 'type');
	}

	public function instructions() {
		return $this->hasMany('App\Instruction', 'type');
	}
}
