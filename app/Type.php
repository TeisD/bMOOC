<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {
    protected $fillable = ['type'];

	public function artefacts() {
		return $this->hasMany('App\Artefact');
	}

	public function instructions() {
		return $this->hasMany('App\Instruction');
	}
}
