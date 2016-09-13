<?php

namespace App\Http\Controllers;

use App\User;
use App\Artefact;
use App\ArtefactType;
use App\Instruction;
use App\Tag;
use App\Topic;
use App\UserRole;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use Redirect;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Carbon\Carbon;
use Exception;
use Mail;
use Response;
use File;
use URL;
use Log;
use stdClass;
use Storage;

abstract class Types
{
    const TEXT = 28;
    const IMAGE = 29;
    const VIDEO_YOUTUBE = 31;
    const VIDEO_VIMEO = 32;
    const FILE = 33;

}

class BmoocController extends Controller {

    public function __construct() {
        //$this->middleware('auth', ['except' => 'index']);
    }

    public function getLogout() {
        Auth::logout();
        return Redirect::to('/');
    }

    public function viewPage($name, $options = []){
        $user = Auth::user();
        $authors = User::orderBy('name')->get();
        $tags = Tag::orderBy('tag')->get();

        $options = array_merge($options, ['user' => $user, 'authors' => $authors, 'tags' => $tags]);

        return view($name, $options);
    }

    public function viewModal($name, $options = []){
        $user = Auth::user();

        $options = array_merge($options, ['user' => $user]);

        return view($name, $options);
    }

    public function index($archived = false) {
        $topics = Topic::where('archived', $archived)->get();

        // lijst per tag alle threads op, selecteer degene met meerdere threads
        $links_query = DB::select(DB::raw('
            SELECT tag_id, tag, GROUP_CONCAT(topic_id ORDER BY topic_id ASC) as items, COUNT(*) as count
            FROM
            (
                SELECT DISTINCT tag_id, artefacts.topic_id, tags.tag
                FROM artefact_tags
                LEFT JOIN artefacts ON artefact_tags.artefact_id = artefacts.id
                LEFT JOIN tags ON artefact_tags.tag_id = tags.id
            ) topics_tags
            GROUP BY topics_tags.tag_id
            HAVING count > 1
        '));

        $links = VisController::getLinks($links_query);

        return BmoocController::viewPage('index', ['topics' => $topics, 'links' => $links, 'archived' => $archived]);
    }

    public function archive(){
        return BmoocController::index(true);
    }

    public function topic(Request $request, $id){
        $topic = Topic::find($id);
        $user = Auth::user();

        /* IS THERE AN ACTION TO BE EXECUTED? */
        if($request->has('action') && isset($user) && $user->role->id > 1){
            switch($request->input('action')){
                case 'delete':
                    $topic->delete();
                    break;
                case 'archive':
                    $topic->update(['archived' => 1]);
                    break;
                case 'unarchive':
                    $topic->update(['archived' => 0]);
                    break;
                case 'edit':
                    return view('forms.master_standalone', ['user' => $user, 'form'=> 'new_topic', 'topic' => $topic]);
                    break;
                default:
                    break;
            }
            // redirect to homepage
            return redirect('/#list');
        }

        // is the topic empty?
        if($topic->artefactCount < 1){
            return BmoocController::viewPage('topic', ['topic' => $topic, 'tree' => null, 'list' => null, 'links' => null]);
        }

        $tree = VisController::getTree($topic->firstAddition);
        $list = $topic->artefacts;

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

        $links = VisController::getLinks($links);

        return BmoocController::viewPage('topic', ['topic' => $topic, 'tree' => $tree, 'list' => $list, 'links' => $links]);
    }

    public function relation($id, $child_id = 0){
        $artefact = Artefact::find($id);

        return BmoocController::viewPage('relation', ['artefact'=> $artefact, 'child_id' => $child_id]);
    }

    public function artefact($id){
        $artefact = Artefact::find($id);

        return BmoocController::viewModal('modals.artefact', ['artefact'=> $artefact]);
    }

    public function instruction($id){
        $instruction = Instruction::find($id);

        return BmoocController::viewModal('modals.instruction', ['instruction'=> $instruction]);
    }

    public function feedback(Request $request){

        $data = $request->all();

        if($data['email'] == "") $data['email'] = "teis.degreve@luca-arts.be";
        if($data['name'] == "") $data['name'] = "Teis De Greve";

        $validator = Validator::make($data,
            array(
              'name' => 'required',
              'email' => 'required|email',
              'body' => 'required',
            )
        );

          if ($validator->fails())
          {
              print("Oops. Something went wrong. Please try again or send your feedback to <a href=\"mailto:teis.degreve@luca-arts.be\">teis.degreve@luca-arts.be</a>");
          } else {

            Mail::send('email.feedback', $data, function($m) use ($data) {
                $m->from($data['email'], $data['name'])
                    ->to('teis.degreve@luca-arts.be')
                    ->subject('bMOOC Feedback');
            });

            print("Thank you for your feedback!");
          }
    }

    public function searchDiscussions($author = null, $tag = null, $keyword = null) {
        $user = Auth::user();
        //filter the artefacts on author first
        $discussies = DB::table('artefacts');
        if (isset($author) && $author != "all") {
            $discussies
                    ->where('author', $author);
        }
        // tags
        if (isset($tag) && $tag != "all") {
            $discussies
                    ->join('artefacts_tags', 'artefacts.id', '=', 'artefacts_tags.artefact_id')
                    ->join('tags', 'artefacts_tags.tag_id', '=', 'tags.id')
                    ->where('tag_id', $tag);
        }
        // query
        if (isset($keyword)) {
            $discussies
                    ->where(function($q) use( &$keyword) {
                        $q
                        ->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('contents', 'LIKE', '%' . $keyword . '%');
                    });
        }
        // the current implementation is to return treads, not artefacts
        $discussies = $discussies
                ->select('thread')
                ->distinct()
                ->lists('thread');
        // SORT?
        //->distinct();
        $discs = Artefact::with(['last_modifier'])
                ->orderBy('updated_at', 'desc')
                ->whereIn('thread', $discussies)
                ->whereNull('parent_id')
                ->get();
        // extra information needed
        $auteurs = DB::table('users')->select('name', 'id')->distinct()->get();
        $tags = Tags::orderBy('tag')->get();
        $aantalAntwoorden = DB::table('artefacts')
                        ->select(DB::raw('count(*) as aantal_antwoorden, thread'))
                        ->groupBy('thread')->get();

        return view('index', ['topic' => $discs, 'user' => $user, 'auteurs' => $auteurs, 'tags' => $tags, 'titel' => "met tag '" . $tag . "'", 'aantalAntwoorden' => $aantalAntwoorden, 'search' => ['tag' => $tag, 'author' => $author, 'keyword' => $keyword]]);
    }

    public function newTopic(Request $request){
        $user = Auth::user();
        if(!$user) return false;
        if($user->role_id < 1) return false;

        $request->start_date = str_replace('/', '-', $request->start_date);
        $request->end_date = str_replace('/', '-', $request->end_date);

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'topic_description_raw' => 'required',
            'topic_goal_raw' => 'required',
            'start_date' => 'date|required',
            'end_date' => 'date|required|after:start_date'
        ]);

        try{

            $topic;

            if ($request->isMethod('post')) {
                $topic = new Topic;
                $topic->author_id = $user->id;
            }

            if($request->isMethod('patch')){
                $topic = Topic::find($request->topic_id);
            }

            $topic->title = $request->title;
            $topic->description = $request->topic_description_raw;
            $topic->goal = $request->topic_goal_raw;
            $topic->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
            $topic->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));
            $topic->save();

            if ( $request->isXmlHttpRequest() ) {
                return Response::json( [
                    'status' => '200',
                    'url' => URL::to('/topic/'.$topic->id)
                ], 200);
            }
            return BmoocController::topic($request, $topic->id);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function newInstruction(Request $request){
        $user = Auth::user();
        if(!$user) return false;
        if($user->role_id < 2) return false;

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'filetype' => 'required',
            'id' => 'required'
        ]);

        try{

            $now = date('Y-m-d H:i:s');

            // disable current instruction
            $c_instruction = Instruction::where('topic_id', $request->id)
                ->where('active_until', null)
                ->first();
            if($c_instruction){
                $c_instruction->active_until = $now;
                $c_instruction->save();
            }

            $instruction = new Instruction;
            $instruction->author_id = $user->id;
            $instruction->topic_id = $request->id;
            $instruction->title = $request->title;
            $instruction->active_from = $now;

            $af = BmoocController::parseArtefact($request);

            $instruction->content = $af->content;
            $instruction->type_id = $af->type;

            $instruction->save();

            if ( $request->isXmlHttpRequest() ) {
                return Response::json( [
                    'status' => '200',
                    'refresh' => true
                ], 200);
            }
            return BmoocController::topic($request, $request->id);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function newArtefact(Request $request){
        $user = Auth::user();
        if(!$user) return false;
        if($user->role_id < 1) return false;

        $parent_id = $request->parent_id;

        // eerste artefact in topic of niet?
        if($parent_id){
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:100',
                'filetype' => 'required',
                'id' => 'required'
            ]);
        } else {
            //
        }

    }

    private function parseArtefact(Request $request){
        $filename = uniqid();

        $af = new stdClass();

        // do some stuff with content here
        switch($request->filetype){
            case 'text':
                $validator = Validator::make($request->all(), [
                    'af_text' => 'required|string'
                ]);
                $af->content = $request->af_text_raw;
                $af->type = Types::TEXT;
                break;
            case 'image':
                $validator = Validator::make($request->all(), [
                    'af_upload' => 'required|image'
                ]);
                Storage::put('artefacts/'.$filename, $request->af_upload);
                BmoocController::storeThumbnails($request, $filename);
                $af->content = $filename;
                $af->type = Types::IMAGE;
                break;
            case 'video':
                $validator = Validator::make($request->all(), [
                    'af_url' => 'required|url'
                ]);
                $url = $request->af_url;
                if (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false) {
                    // Youtube video
                    $af->content = '//youtube.com/embed/' . BmoocController::parseYoutube($url);
                    $af->type = Types::VIDEO_YOUTUBE;
                } elseif (strpos($url, 'vimeo.com') !== false) {
                    // Vimeo video
                    $af->content = '//player.vimeo.com/video/'.substr($url, strpos($url, 'vimeo.com/') + 10);
                    $af->type = Types::VIDEO_VIMEO;
                } else {
                    throw new Exception('The URL you entered is not a valid link to a YouTube or Vimeo video.');
                }
                break;
            case 'file':
                $validator = Validator::make($request->all(), [
                    'af_upload' => 'required|mimes:pdf'
                ]);
                //TODO: make pdf validator
                Storage::put('artefacts/'.$filename, $request->af_upload);
                BmoocController::storeThumbnails($request, $filename);
                $af->content = $filename;
                $af->type = Types::FILE;
                break;
            default:
                throw new Exception('Invalid filetype');
        }

        return $af;
    }

    private function storeThumbnails(Request $request, $filename){
        // Thumbnails opslaan
        // small
        if($request->thumbnail_small && $request->thumbnail_small != null && $request->thumbnail_small != ''){
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->thumbnail_small));
            Storage::put('artefacts/thumbnails/small/'.$filename, $data);
        }
        // large
        if($request->thumbnail_large && $request->thumbnail_large != null && $request->thumbnail_large != ''){
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->thumbnail_large));
            Storage::put('artefacts/thumbnails/large/'.$filename, $data);
        }
    }

    public function getImage($id){
        $a = Artefact::find($id);
        $path = storage_path('/app/artefacts/thumbnails/large/'.$a->content);
        if (file_exists($path)) {
            $filetype = mime_content_type( $path );
            $response = Response::make( File::get( $path ) , 200 );
            $response->header('Content-Type', $filetype);
            return $response;
        }
        return BmoocController::getImageOriginal($id);
    }

    public function getImageThumbnail($id){
        // get url from id
        $a = Artefact::find($id);
        $path = storage_path('/app/artefacts/thumbnails/small/'.$a->content);
        // check if the artefact has a thumbnail based on id
        if (file_exists($path)) {
            $filetype = mime_content_type( $path );
            $response = Response::make( File::get( $path ) , 200 );
            $response->header('Content-Type', $filetype);
            return $response;
        }
        return BmoocController::getImage($id);

    }

    public function getImageOriginal($id){
        $a = Artefact::find($id);
        $path = storage_path('/app/artefacts/'.$a->content);
        if (file_exists($path)) {
            $filetype = mime_content_type( $path );
            $response = Response::make( File::get( $path ) , 200 );
            $response->header('Content-Type', $filetype);
            return $response;
        } else if($a->type_id == 31){
            $url = str_replace('www.youtube.com/embed', 'img.youtube.com/vi', $a->content);
            $url .= '/0.jpg';
            $response = Response::make( file_get_contents($url), 200 );
            $response->header('Content-Type', 'image/jpeg');
            return $response;
        } else if($a->type_id == 32){
            $oembed_endpoint = 'http://vimeo.com/api/oembed';
            $url = $oembed_endpoint . '.json?url=' . rawurlencode($a->content);
            $json = file_get_contents($url);
            $obj = json_decode($json);
            $response = Response::make( file_get_contents($obj->thumbnail_url), 200 );
            $response->header('Content-Type', 'image/jpeg');
            return $response;
        }
        abort(404, 'Image not found');
    }

    private function parseYoutube($url){
        $video_id = false;
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $video_id = $match[1];
        }
        if($video_id && $video_id != '') return $video_id;
        else throw new Exception('The URL is not a valid link to a YouTube video');
    }
}
