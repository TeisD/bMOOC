<?php

namespace App\Http\Controllers;

use App\Artefact;

class VisController extends Controller {

    static public function getTree(Artefact $a){
        $a->load('children');
        foreach($a->children as $child){
            self::getTree($child);
        }
        return $a;
    }
}
