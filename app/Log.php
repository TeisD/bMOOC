<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {
	protected $fillable = ['user_id', 'title'];

	public function author() {
		return $this->belongsTo('App\User', 'author_id');
	}

    public function commands() {
        return $this->hasMany('App\LogCommand');
    }

}
