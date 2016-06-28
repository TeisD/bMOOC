@extends('master')

@section('title', 'bMOOC')

@section('header_actions')
    @if ((isset($user) && $user->role=="editor") || (1 == 1))
        <button class="primary plus indent" data-help="index" data-help-id="new_topic" data-reveal-id="new_topic"></button>
    @endif
@stop

@section('header_search')
    @include('forms.search')
@stop

@if(isset($archived) && $archived)
@section('header_content')
    <div class="row">
       <div class="columns">
           <h2 class="inline sub">Archive</h2>
       </div>
   </div>
@stop
@endif

@section('content')
    <div class="row full" id="vis-container">
        <div class="vis-gui render">

        </div>
    </div>
    <div class="row" id="vis-fallback">
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
                    <li>
                        <a href="#" class="sort" data-sort="active_from_ts">Active from</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="active_until_ts">Active until</a>
                    </li>
                  </ul>
              </div>
          </div>
          <ul class="list grid">
            @foreach($topics as $topic)
            @if($topic->active)
               <li>
            @else
                <li class="archived">
            @endif
           <a href="/topic/{{$topic->id}}">
            <div class="row">
                <div class="columns large-3">
                    <h3 class="title inline">{{ $topic->title }}</h3>
                </div>
                <div class="columns medium-6 large-2">
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
                <div class="columns medium-6 large-2">
                    <span class="light">initiated by</span> <span class="initiator">{{$topic->author->name}}</span>
                </div>
                <div class="columns medium-6 large-3">
                    <span class="light">last addition</span>
                    <span class="last_addition">{{date('d/m/Y', strtotime($topic->lastAddition->created_at))}}</span>
                    <span class="last_addition_ts" hidden="hidden" style="display: none;">{{$topic->lastAddition->created_at}}</span>
                    <span class="light">by</span>
                    <span class="last_author">{{$topic->lastAddition->author->name}}</span>
                </div>
                <div class="columns medium-6 large-2">
                    <span class="light">active from</span>
                    <span class="active_from">{{date('d/m/Y', strtotime($topic->start_date))}}</span>
                    <span class="active_from_ts" hidden="hidden" style="display: none;">{{$topic->start_date}}</span>
                    <span class="light">until</span>
                    <span class="active_until">{{date('d/m/Y', strtotime($topic->end_date))}}</span>
                    <span class="active_until_ts" hidden="hidden" style="display: none;">{{$topic->end_date}}</span>
                </div>
            </div>
            </a></li>
            @endforeach
          </ul>
        </div>
        <div class="columns margin-bottom">
            <div class="columns text-center">
                <a href="/archive">view archived topics</a>
            </div>
        </div>
    </div>
@stop

@section('forms')
    {{-- NEW TOPIC FORM --}}
    @include('forms.master', ['form' => 'new_topic', 'class' => 'slide'])
@stop

@section('scripts')
  <script src="/js/d3.min.js"></script>
  <script src="/js/d3plus.min.js"></script>
  <script src="/js/list.min.js"></script>
    <script>
        var data = {};
        data.list = JSON.parse('{!! addslashes(json_encode($topics)) !!}');
        data.links = JSON.parse('{!! addslashes(json_encode($links)) !!}');

        var vis = new Vis($('#vis-container').get(0), data, 'topic', {
            interactive: true,
            type: 'network',
            rotate: false,
            mode: 'text',
            fit: true,
            collide: false,
            resize: true
        });

        $(document).ready(function(){
            if($('html').hasClass('svg')){
                $('#vis-menu button[data-vis="network"]').addClass('active');
                vis.render();
            }
        });

        var visMenu = new Menu('vis-menu', 'vis-container', 'vis-fallback', vis, {
            disabled: ['grid', 'tree']
        });

        var userList = new List('vis-fallback', {
            valueNames: [ 'title', 'additions', 'contributors', 'author', 'initiator', 'last_addition_ts', 'last_author', 'active_from_ts', 'active_until_ts']
        });

        userList.sort('last_addition_ts', { order: "desc" });


    </script>
@stop
