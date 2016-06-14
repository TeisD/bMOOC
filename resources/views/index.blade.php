@extends('master')

@section('title', 'bMOOC')

@section('header_actions')
    @if (isset($user) && $user->role=="editor")
        <button class="primary plus" data-help="index" data-help-id="new_topic" data-reveal-id="new_topic">Start a new topic</button>
    @endif
@stop

@section('header_search')
    @include('forms.search')
@stop

@section('content')
    <div class="row full" id="vis-container">
        <div class="vis-gui render">

        </div>
    </div>
    <div class="row full" id="vis-fallback">
      <div class="columns">
          <div class="row vis-sort">
              <div class="columns text-center">
                 sort by:
                <ul class="inline slash">
                    <li>
                        <a href="#" class="sort" data-sort="title">Title</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="additions"># additions</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="contributors"># contributors</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="initiator">Initiator</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="last_addition_ts">Last addition date</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="last_author">Last addition author</a>
                    </li>
                  </ul>
              </div>
          </div>
          <ul class="list block">
           @foreach($topics as $topic)
           <li>
            <div class="row">
                <div class="columns large-4">
                    <h2 class="title">{{ $topic->title }}</h2>
                </div>
                <div class="columns large-2">
                    <strong class="additions">{{ $topic->artefactCount }}</strong>
                         @if ($topic->artefactCount == 1)
                            <span class="light">addition</span>
                         @else
                            <span class="light">additions</span>
                         @endif
                    <span class="light">by</span>
                    <strong class="contributors">{{ $topic->contributorCount }}</strong>
                        @if ($topic->contributorCount == 1)
                            <span class="light">contributor</span>
                         @else
                            <span class="light">contributors</span>
                         @endif
                </div>
                <div class="columns large-2">
                    <span class="light">initiated by</span> <span class="initiator">{{$topic->author->name}}</span>
                </div>
                <div class="columns large-3">
                    <span class="light">last addition</span>
                    <span class="last_addition">{{date('d/m/Y', strtotime($topic->lastAddition->created_at))}}</span>
                    <span class="last_addition_ts" hidden="hidden" style="display: none;">{{$topic->lastAddition->created_at}}</span>
                    <span class="light">by</span>
                    <span class="last_author">{{$topic->lastAddition->author->name}}</span>
                </div>

            </div>
            </li>
            @endforeach
          </ul>
        </div>
    </div>
@stop


@section('scripts')
  <script src="js/d3.min.js"></script>
  <script src="js/d3plus.min.js"></script>
  <script src="js/list.min.js"></script>
    <script>
        var data = {};
        data.list = JSON.parse('{!! addslashes(json_encode($topics)) !!}');
        data.links = JSON.parse('{!! addslashes(json_encode($links)) !!}');;

        console.log(data);

        var vis;

        $(document).ready(function(){
            if($('html').hasClass('svg')){
                vis = new Vis($('#vis-container').get(0), data, {
                    interactive: true,
                    mode: 'text',
                    fit: true,
                    collide: false,
                    resize: true
                });
                $('#vis-menu button[data-vis="network"]').addClass('active');
                vis.render('network');
            }
        });

        var visMenu = new Menu('vis-menu', 'vis-container', 'vis-fallback', {
            disabled: ['tree']
        });

        var userList = new List('vis-fallback', {
            valueNames: [ 'title', 'additions', 'contributors', 'author', 'initiator', 'last_addition_ts', 'last_author' ]
        });

        userList.sort('last_addition_ts', { order: "desc" });


    </script>
@stop
