<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
	protected $fillable = ['tag'];

	public function artefacts() {
		return $this->belongsToMany('App\Artefact', 'artefact_tags', 'tag_id', 'artefact_id');
	}

}
