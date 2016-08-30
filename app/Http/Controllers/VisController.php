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
    
    static public function getLinks($a){
        $links = [];

        foreach ($a as $link){
            if($link->count <= 1) continue;
            $link->items = array_map('intval', explode(',', $link->items));

            for($i = 0; $i < sizeof($link->items); $i++){
                for($j = $i+1; $j < sizeof($link->items); $j++){
                    $source = $link->items[$i];
                    $target = $link->items[$j];
                    // check if source and target have a first generation relationship
                    //if(Artefact::find($target)->parent_id == $source) continue;
                    //if(Artefact::find($source)->parent_id == $target) continue;
                    // check if source target already exists
                    $sources = array_keys(array_column($links, 'source'), $source);
                    $targets = array_keys(array_column($links, 'target'), $target);
                    $intersect = array_intersect($sources, $targets);
                    // intersect keeps index, so re-index
                    $intersect = array_values($intersect);
                    if(sizeof($intersect) > 0){
                        // add the tag
                        array_push($links[$intersect[0]]['links'], ["id" => $link->tag_id, "tag" => $link->tag]);
                    } else {
                        // else make a new one
                        array_push($links, ["source" => $source, "target" => $target, "links" => [["id" => $link->tag_id, "tag" => $link->tag]]]);
                    }
                }
            }
        }
        return $links;
    }
}
