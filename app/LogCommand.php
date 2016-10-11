<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class LogCommand extends Model {
	protected $fillable = ['log_id', 'event', 'button_id', 'description', 'created_at', 'updated_at'];
    protected $appends = ['command'];

	public function log() {
		return $this->belongsTo('App\Log');
	}

    public function getCommandAttribute(){
        if($this->description != 'NULL'){
            return $this->description;
        }elseif($this->button_id != 'NULL'){
            return DB::table('log_buttons')->where('id', $this->button_id)->first()->description;
        } else{
            return null;
        }
    }

}
