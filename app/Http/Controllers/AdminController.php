<?php

namespace App\Http\Controllers;

use Auth;
use Request;
use App;
use Exception;
use Input;
use Artefacts;
use App\User;
use App\Artefact;
use App\Tags;
use stdClass;
use DB;
use Carbon;
use App\Http\Controllers\Controller;
use Schema;
use Artisan;

class AdminController extends Controller {

    public function __construct() {
        //$this->middleware('auth', ['except' => 'index']);
    }

    public function basic($topic = null) {
        // LIST OF TOPICS
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        if(!is_numeric($topic)) $topic = null;

        $artefacts = new stdClass();
        $users = new stdClass();
        $tags = new stdClass();
        // ALGEMEEN
        $artefacts->count = DB::table('artefacts')
            ->where('thread', 'LIKE', $topic)
            ->count();
        if($topic)
            $users->count = sizeof(DB::table('users')
                ->select('users.id')
                ->leftJoin('artefacts', 'users.id', '=', 'artefacts.author')
                ->where('thread', 'LIKE', $topic)
                ->groupBy('users.id')
                ->get());
        else $users->count = DB::table('users')->count();
        $users->all = DB::table('users')->count();
        if($topic)
            $tags->count = sizeof(DB::table('artefacts_tags')
                ->join('artefacts', 'artefact_id', '=', 'artefacts.id')
                ->where('thread', 'LIKE', $topic)
                ->groupBy('tag_id')
                ->get());
        else $tags->count = DB::table('tags')->count();
        // ARTEFACTS
        $artefacts->types = [
            "text" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where('artefact_type', '28')
                ->count(),
            "image" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where(function ($query) {
                    $query->where('artefact_type', '29')
                        ->orwhere('artefact_type', '30');
                })
                ->count(),
            "video" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where(function ($query) {
                    $query->where('artefact_type', '31')
                        ->orwhere('artefact_type', '32');
                })
                ->count(),
            "pdf" => DB::table('artefacts')
                ->where('thread', 'LIKE', $topic)
                ->where(function ($query) {
                    $query->where('artefact_type', '33')
                        ->orwhere('artefact_type', '34');
                })
                ->count()
        ];
        // USERS
        $users->topten = DB::table('artefacts')
            ->select('users.name', DB::raw('COUNT(author) AS post_count'))
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('artefacts.author')
            ->orderBy('post_count', 'DESC')
            ->limit(10)
            ->get();
        $users->active = sizeof(DB::table('artefacts')
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('artefacts.author')
            ->get());
        $users->passive = $users->all - $users->active;
        $users->users = DB::table('artefacts')
            ->select('users.name', DB::raw('COUNT(author) AS post_count'))
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('artefacts.author')
            ->orderBy('post_count', 'DESC')
            ->get();
        // TAGS
        $tags->topten = DB::table('artefacts_tags')
            ->select('tag_id', 'tag', DB::raw('count(*) as times_used'))
            ->leftJoin('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('tag')
            ->orderBy('times_used', 'DESC')
            ->orderBy('tag', 'ASC')
            ->limit(10)
            ->join('tags', 'tag_id', '=', 'tags.id')
            ->get();
        $tags->single = DB::table('artefacts_tags')
            ->select('tag_id', 'tag', DB::raw('count(*) as times_used'))
            ->leftJoin('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->where('thread', 'LIKE', $topic)
            ->groupBy('tag')
            ->orderBy('tag', 'ASC')
            ->having('times_used', '=', 1)
            ->join('tags', 'tag_id', '=', 'tags.id')
            ->get();
        /*$temp = DB::table('artefacts')
            ->select(DB::raw('artefacts.thread, artefacts_tags.tag_id, COUNT(*) AS ct'))
            ->distinct()
            ->join('artefacts_tags', 'artefacts.id', '=', 'artefacts_tags.artefact_id')
            ->groupBy('artefacts_tags.tag_id')
            ->having('ct', '>', 1)
            ->orderBY('ct', 'DESC')
            ->get();
        $sub = DB::table('artefacts_tags')
            ->select(DB::raw('tag_id, tag, thread'))
            ->groupBy('tag')
            ->join('tags', 'tag_id', '=', 'id')
            ->join('artefacts', 'artefacts_tags.artefact_id', '=', 'artefacts.id')
            ->distinct()
            ->get();
        //$tags->multiple = DB::table('tags'); */
        $user = Auth::user();
        if ($user && $user->role == "editor") {
            return view('admin.data.basic', ['topics' => $topics, 'topic' => $topic, 'artefacts' => $artefacts, 'users' => $users, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }


    /*
    public function index(Request $request) {
        $artefacts = new stdClass();
        $users = new stdClass();
        $tags = new stdClass();
        // ALGEMEEN
        $artefacts->aantal = DB::table('artefacts')->count();
        $users->aantal = DB::table('users')->count();
        $tags->aantal = DB::table('tags')->count();
        // ARTEFACTS
        $artefacts->types = [
            "text" => DB::table('artefacts')->where('artefact_type', '28')->count(),
            "image" => DB::table('artefacts')->where('artefact_type', '29')->orwhere('artefact_type', '30')->count(),
            "video" => DB::table('artefacts')->where('artefact_type', '31')->orwhere('artefact_type', '32')->count(),
            "pdf" => DB::table('artefacts')->where('artefact_type', '33')->orwhere('artefact_type', '34')->count()
        ];
        $artefacts->progress = json_encode(DB::table('artefacts')
            ->select(DB::raw('artefacts.updated_at, CAST(artefacts.updated_at AS DATE) date, COUNT(updated_at) AS amount'))
            ->groupBy(DB::raw('CAST(updated_at AS DATE)'))
            ->get());
        // USERS
        $users->topten = DB::table('artefacts')
            ->select(DB::raw('users.name, COUNT(author) AS theCount'))
            ->join('users', 'artefacts.author', '=', 'users.id')
            ->groupBy('artefacts.author')
            ->orderBy('theCount', 'DESC')
            ->limit(10)
            ->get();
        $users->passive = DB::table('users')
            ->select('name')
            ->leftJoin('artefacts', 'users.id', '=', 'artefacts.author')
            ->where('artefacts.author', '=', NULL)
            ->count();
        // TAGS
        // TAGS
        $tags->topten = DB::table('tags')
            ->select('tag', 'times_used')
            ->orderBy('times_used', 'DESC')
            ->orderBy('tag', 'ASC')
            ->limit(10)
            ->get();
        $user = Auth::user();
        if ($user && $user->role == "editor") {
            return view('admin.data.simple', ['artefacts' => $artefacts, 'users' => $users, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }*/

    public function progress(Request $request){
        // LIST OF TOPICS
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        $topic = Input::get('topic');
        if(!is_numeric($topic)) $topic = null;

        $artefacts = DB::table('artefacts')
            ->select('id', 'created_at')
            ->where('thread', 'LIKE', $topic)
            ->get();

        $user = Auth::user();
        if ($user && $user->role == "editor") {
            return view('admin.data.progress', ['topics' => $topics, 'topic' => $topic, 'artefacts' => $artefacts]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }

    public function tree(Request $request){
        // LIST OF TOPICS
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        $topic = Input::get('topic');
        if($topic == "all") $topic = null;
        else if(!is_numeric($topic)) $topic = $topics[0]->thread;

        // BUILD TREE
        $parent = DB::table('artefacts')
            ->select('id')
            ->where('thread', 'LIKE', $topic)
            ->where('parent_id', '=', NULL)
            ->get();
        $parent = Artefact::all()->find($parent[0]->id);

        $tree = $parent;

        $parent->children = BmoocJsonController::buildTree($parent->children, $parent->id);

        // GET PLAIN LIST
        $list = DB::table('artefacts')
            ->where('thread', 'LIKE', $topic)
            ->get();

        $tags = DB::table('artefacts_tags')
            ->select('artefact_id', 'tag_id')
            ->leftJoin('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->where('thread', 'LIKE', $topic)
            ->get();

        $user = Auth::user();

        if ($user && $user->role == "editor") {
            return view('admin.data.tree', ['topics' => $topics, 'topic' => $topic, 'tree' => $tree, 'list' => $list, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }

    }

    public function topics(Request $request) {
        $topics = DB::table('artefacts')
            ->orderBy('updated_at', 'desc')
            ->whereNull('parent_id')
            ->get();
        $topic = Input::get('topic');
        if(!is_numeric($topic)) $topic = null;

        // GET PLAIN LIST of topics
        $list = DB::table('artefacts')
            ->where('parent_id', '=', null)
            ->get();

        // Get a list of tag, and connect them to a topic
        $tags = DB::table('artefacts_tags')
            ->select('artefacts.thread', 'tag_id')
            ->join('artefacts', 'artefact_id', '=', 'artefacts.id')
            ->get();

        $user = Auth::user();

        if ($user && $user->role == "editor") {
            return view('admin.data.topics', ['topics' => $topics, 'topic'=> $topic, 'list' => $list, 'tags' => $tags]);
        } else {
            App::abort(401, 'Not authenticated');
        }
    }

    public function getThumbnails(Request $request) {
        //$user = Auth::user();
        //if (!$user || $user->role_id != 3) App::abort(401, 'Not authenticated');

        // get a list of all the local pdf's & images
        $artefacts = Artefact::where('artefact_type', '29')
            ->orWhere('artefact_type', '33')
            ->get();

        // check which image sizes are available
        $basepath = base_path().'/../uploads/thumbnails';
        foreach($artefacts as $artefact){
            $sizes = Array();
            if (file_exists($basepath . '/../' . $artefact->url)) {
                array_push($sizes, 'original');
            }
            if (file_exists($basepath . '/small/' . $artefact->url)) {
                array_push($sizes, 'small');
            }
            if (file_exists($basepath . '/large/' . $artefact->url)) {
                array_push($sizes, 'large');
            }
            $artefact->setAttribute('sizes', $sizes);
        }


        return view('admin.actions.thumbnails', ['artefacts' => $artefacts]);
    }

    public function postThumbnails(Request $request){
        try{
            if(Input::get('size') && Input::get('file') && Input::get('filename')){
                $size = Input::get('size');
                $file = Input::get('file');
                $filename = Input::get('filename');
                $destinationPath = 'uploads/thumbnails/';
                switch($size){
                    case 'small':
                        $destinationPath .= 'small/' . $filename;
                        break;
                    case 'large':
                        $destinationPath .= 'large/' . $filename;
                        break;
                    default:
                        throw new Exception ('Unrecognized file size.');
                        break;
                }
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));
                try {
                    file_put_contents($destinationPath, $data);
                    return Response::json( [
                        'status' => '200',
                        'message' => 'Succes!'
                    ], 200);
                } catch(Exception $e){
                    throw new Exception ('Failed to store files');
                }
            } else throw new Exception ('No file, size or filename given.');
        } catch(Exception $e){
            throw $e;
        }
    }

    public function getTags(Request $request){
        //$user = Auth::user();
        //if (!($user && $user->role_id != 3)) App::abort(401, 'Not authenticated');

        $duplicates = DB::table('tags')
            ->select('tag', DB::raw('COUNT(*) as count, GROUP_CONCAT(id) as id, GROUP_CONCAT(times_used) as times_used'))
            ->groupBy('tag')
            ->having('count', '>', '1')
            ->get();

        foreach($duplicates as $duplicate){
            $duplicate->id = explode(',', $duplicate->id);
            $duplicate->times_used = explode(',', $duplicate->times_used);
        }

        return view('admin.actions.tags', ['duplicates' => $duplicates]);
    }

    public function postTags(Request $request){
        //$user = Auth::user();
        //if (!($user && $user->role_id != 3)) App::abort(401, 'Not authenticated');

        $duplicates = AdminController::getTags($request)->getData()['duplicates'];

        foreach($duplicates as $duplicate){
            $id = $duplicate->id[0];
            $times_used = $duplicate->times_used[0];
            for($i = 1; $i < sizeof($duplicate->id); $i++){
                // add to times used
                $times_used += $duplicate->times_used[$i];
                // change all occurences of id[i] in artefacts_tags to id
                DB::table('artefacts_tags')
                    ->where('tag_id', $duplicate->id[$i])
                    ->update(array('tag_id' => $id, 'updated_at' => Carbon\Carbon::now()));
                // remove id[i] from table::tags
                DB::table('tags')
                    ->where('id', $duplicate->id[$i])
                    ->delete();
            }
            // write times_used to table::tags
            DB::table('tags')
                    ->where('id', $id)
                    ->update(array('times_used' => $times_used, 'updated_at' => Carbon\Carbon::now()));
        }

        return AdminController::getTags($request);
    }

    public function getMigrate(){
        //$user = Auth::user();
        //if (!($user && $user->role_id != 3)) App::abort(401, 'Not authenticated');

        return view('admin.actions.migrate');

    }

    public function postMigrateNew(){
        Artisan::call('migrate');
        return view('admin.actions.migrate');
    }

    public function postMigrate(){
        //$user = Auth::user();
        //if (!($user && $user->role_id != 3)) App::abort(401, 'Not authenticated');

        /**
         *  artefact_types
         */
        if (Schema::hasColumn('artefact_types', 'description')){
            Schema::table('artefact_types', function($table){
                $table->renameColumn('description', 'type');
            });
        }
        if (Schema::hasColumn('artefact_types', 'description')){
            Schema::table('artefact_types', function($table){
                $table->string('type', 100)->change();
            });
        }

        /**
         *  artefacts
         */
        if (Schema::hasColumn('artefacts', 'thread')) {
            Schema::table('artefacts', function($table){
                $table->renameColumn('thread', 'topic');
                $table->renameColumn('artefact_type', 'type');
                $table->renameColumn('contents', 'content');
                $table->text('notes')->nullable();
            });
        }
        if (Schema::hasColumn('artefacts', 'topic')) {
            Schema::table('artefacts', function($table){
                $table->integer('author')->change();
                $table->string('title', 100)->change();
                $table->integer('type')->change();
                // loop through and copy the contents of the 'url' column to the 'content' column
                DB::statement('UPDATE v2_artefacts
                    SET content = url
                    WHERE url <> \'NULL\'');
            });
        }

        /**
         *  artefact_tags
         */
        if (Schema::hasTable('artefacts_tags')){
            Schema::table('artefacts_tags', function($table){
                $table->primary('id');
            });
            DB::statement('RENAME TABLE v2_artefacts_tags TO v2_artefact_tags');
        }

        /**
         *  instructions
         */
        if (Schema::hasColumn('instructions', 'thread')){
            Schema::table('instructions', function($table){
                $table->renameColumn('thread', 'topic');
                $table->renameColumn('instruction_type', 'type');
                $table->renameColumn('contents', 'content');
            });
        }
        if (Schema::hasColumn('instructions', 'topic')){
            Schema::table('instructions', function($table){
                $table->integer('author')->change();
                $table->integer('type')->change();
                $table->string('title', 100)->change();
                DB::statement('UPDATE v2_instructions
                    SET content = url
                    WHERE url <> \'NULL\'');
            });
        }

        /**
         *  instruction_artefact_types
         */
        if (Schema::hasTable('instructions_artefact_types')){
            DB::statement('RENAME TABLE v2_instructions_artefact_types TO instruction_artefact_types');
        }

        /**
         *  tags
         */
        Schema::table('tags', function($table){
            $table->string('tag', 100)->change();
        });

        /**
         *  users
         */
        Schema::table('users', function($table){
            $table->integer('role')->change();
        });

        /**
         * topics
         */
        DB::statement('INSERT INTO v2_topics (id, author, title, description, goal, start_date, end_date, created_at, updated_at)
        SELECT topic, author, title, \'no description\', \'no goal\', created_at, \'2016-09-01 12:00:00\', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
        FROM v2_artefacts
        WHERE parent_id IS NULL');

        /**
         *  add migrations to enable artisan
         */
        $batch = DB::table('migrations')
            ->select('batch')
            ->orderBy('batch', 'DESC')
            ->limit(1)
            ->get();
        $batch = $batch[0]->batch;
        $batch++;
        $migrations = [
            ['2014_10_12_000000_create_users_table', $batch],
            ['2014_10_12_100000_create_password_resets_table', $batch],
            ['2016_06_13_105547_create_tags_table', $batch],
            ['2016_06_13_105758_create_instructions_table', $batch],
            ['2016_06_13_110550_create_artefact_types_table', $batch],
            ['2016_06_13_110635_create_artefacts_table', $batch],
            ['2016_06_13_111140_create_artefact_tags_table', $batch],
            ['2016_06_13_111918_create_instruction_artefact_types_table', $batch]
        ];
        foreach($migrations as $migration){
            DB::insert('insert into migrations (migration, batch) values (?, ?)', $migration);
        }

        Artisan::call('migrate');

        return view('admin.actions.migrate');
    }
}
