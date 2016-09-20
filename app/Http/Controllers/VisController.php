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
    
    static public function buildLinks($a){
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

    static public function getLinks($list){
        // some pretty crazy DB request intensive method
        // This will match only the unique (user added) tag for each artefact

        function get_tags($a) {
            $ret = [];
            foreach($a as $b) array_push($ret, ['tag_id' => $b->id, 'tag' => strtolower($b->tag)]);
            return $ret;
        }

        function compare_tags($a, $b){
            return strcmp($a['tag'],$b['tag']);
        }

        function find_tag($needle, $haystack){
            $size = count($haystack);
            for($i = 0; $i < $size; $i++){
                if($haystack[$i]->tag == $needle['tag']) return $i;
            }
            return FALSE;
        }

        $links = [];

        foreach($list as $artefact){
            if(!$artefact->hasParent){
                $unique_tags = get_tags($artefact->tags);
                continue;
            } else{
                $artefact_tags = get_tags($artefact->tags);
                $parent_tags = get_tags($artefact->parent->tags);
                $unique_tags = array_udiff($artefact_tags, $parent_tags, 'App\Http\Controllers\\compare_tags');
            }
            foreach($unique_tags as $unique_tag){
                $key = find_tag($unique_tag, $links);
                if($key === FALSE){
                    array_push($links, (object)array('tag_id' => $unique_tag['tag_id'], 'tag' => $unique_tag['tag'], 'items' => (string)$artefact->id, 'count' => 1));
                } else {
                    $links[$key]->items = $links[$key]->items.','.(string)$artefact->id;
                    $links[$key]->count = $links[$key]->count + 1;
                }
            }
        }

        return $links;
    }
}
