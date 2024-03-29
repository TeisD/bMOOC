@extends('master')

@section('title', $topic->title)

@section('header_actions')
    @if (isset($user) && $user->role->id > 1)
        <button class="primary plus indent" data-help="index" data-help-id="new_instruction" data-reveal-id="new_instruction">new instruction</button>
    @endif
@stop

@section('header_search')
    @include('forms.search')
@stop

@section('header_content')
    <div class="row">
      @if(!$topic->active)
        <div class="columns archived">
      @else
        <div class="columns">
      @endif
           <h2 class="inline sub"><a href="/topic/{{$topic->id}}">{{$topic->title}}</a></h2>
           @if(!$topic->active)
                <small>(inactive)</small>
            @endif
            @if($topic->archived)
                <small>(archived)</small>
            @endif
           @if(isset($user) && $user->role->id > 1)
            <h2 class="inline">
                <a href="javascript:;" class="emphasis" data-dropdown="topic_edit_{{$topic->id}}" data-help data-help-id="topic_edit" data-dropdown-position="anchor">&darr;</a>
            </h2>
            @include('dropdowns.topic_edit', ['id'=>$topic->id])
           @endif
           <a data-help data-help-id="view_info" data-log="24" class="button primary indent information" data-dropdown="info">&nbsp;</a>
       </div>
   </div>
   @include('dropdowns.topic_info', ['open'=>'open', 'topic'=>$topic])
@stop

@section('content')
   @if($topic->artefactCount < 1)
       @if($topic->active || $user->role->id > 1)
        <div class="row full">
            <button class="primary plus centered" data-reveal-id="new_artefact">add (some)thing</button>
        </div>
        @else
        <div class="row text-center">
            This topic is empty &amp; inactive.<br />You can add some(thing) to it from <strong>{{date('d/m/Y', strtotime($topic->start_date))}}</strong> untill <strong>{{date('d/m/Y', strtotime($topic->end_date))}}</strong>.
        </div>
        @endif
   @else
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
                        <a href="javascript:;" class="sort" data-sort="title">Title</a>
                    </li>
                    <li>
                        <a href="javascript:;" class="sort" data-sort="type">Type</a>
                    </li>
                    <li>
                        <a href="javascript:;" class="sort asc" data-sort="date_ts">Date added</a>
                    </li>
                    <li>
                        <a href="javascript:;" class="sort" data-sort="author">Author</a>
                    </li>
                    <li>
                        <a href="javascript:;" class="sort" data-sort="tag_1">Tag 1</a>
                    </li>
                    <li>
                        <a href="javascript:;" class="sort" data-sort="tag_2">Tag 2</a>
                    </li>
                    <li>
                        <a href="javascript:;" class="sort" data-sort="tag_3">Tag 3</a>
                    </li>
                  </ul>
              </div>
          </div>
          <ul class="list grid">
           @foreach($topic->artefacts as $artefact)
           <li>
                <div class="row">
                    <a href="/relation/{{$artefact->id}}" data-log="{{$artefact->title}}">
                   <div class="columns large-12" vis-grid>
                      @if($artefact->type_id > 28)
                       <img src="/artefact/{{$artefact->id}}/thumbnail" alt="{{$artefact->title}}"/>
                        @else
                       <h3>{{$artefact->title}}</h3>
                       @endif
                   </div>
                    </a>
                    <div class="columns large-4" vis-list>
                        <h3 class="title inline">
                            <a href="/relation/{{$artefact->id}}">{{ $artefact->title }}</a>&nbsp;
                        </h3>
                        <span class="title_clean" hidden="hidden" style="display:none"><?php
                            $s = str_replace(' ', '_', $artefact->title);
                            echo preg_replace('/[^A-Za-z0-9\_]/', '', $s) ?></span>
                    </div>
                    <div class="columns large-1" vis-list>
                        <span class="type">{{ $artefact->type->type }}</span>
                    </div>
                    <div class="columns large-3" vis-list>
                        <span class="light">added</span>
                        <span class="date">{{date('d/m/Y', strtotime($artefact->created_at))}}</span>
                        <span class="date_ts" hidden="hidden" style="display: none;">{{$artefact->created_at}}</span>
                        <span class="light">by</span>
                        <a href="/search/{{$artefact->author->name}}"><span class="author">{{$artefact->author->name}}</span></a>
                    </div>
                    <div class="columns large-1" vis-list>
                        <span class="tag_1">
                           @if(isset($artefact->tags[0]))
                            <a href="/search/all/{{$artefact->tags[0]->tag}}">{{ $artefact->tags[0]->tag }}</a>
                            @endif
                        </span>
                    </div>
                    <div class="columns large-1" vis-list>
                        <span class="tag_2">
                           @if(isset($artefact->tags[1]))
                            <a href="/search/all/{{$artefact->tags[1]->tag}}">{{ $artefact->tags[1]->tag }}</a>
                            @endif
                        </span>
                    </div>
                    <div class="columns large-1 end" vis-list>
                        <span class="tag_3">
                            @if(isset($artefact->tags[2]))
                            <a href="/search/all/{{$artefact->tags[2]->tag}}">{{ $artefact->tags[2]->tag }}</a>
                            @endif
                        </span>
                    </div>
                </div>
            </li>
            @endforeach
          </ul>
        </div>
    </div>
    @endif
@stop

@section('forms')
    {{-- NEW TOPIC FORM --}}
    @include('forms.master', ['form' => 'new_instruction', 'class' => 'slide'])
    {{-- NEW ARTEFACT FORM --}}
    @if($topic->artefactCount < 1)
        @include('forms.master', ['form' => 'new_artefact', 'class' => 'slide', $first = true])
    @else
        @include('forms.master', ['form' => 'new_artefact', 'class' => 'slide'])
    @endif
@stop

@section('scripts')
  <script src="/js/d3.min.js"></script>
  <script src="/js/d3plus.min.js"></script>
  <script src="/js/list.min.js"></script>
    <script>
        var data = {};
        data.tree = JSON.parse('{!! addslashes(json_encode($tree)) !!}');
        data.list = JSON.parse('{!! addslashes(json_encode($list)) !!}');
        data.links = JSON.parse('{!! addslashes(json_encode($links)) !!}');

        var vis = new Vis($('#vis-container').get(0), data, 'relation', {
            interactive: true,
            type: 'tree',
            mode: 'all',
            fit: true,
            collide: false,
            resize: true,
            rotate: true
        });
        var timeline = new Timeline(vis, "{!!$topic->start_date!!}", "{!!$topic->end_date!!}")

        $(document).ready(function(){
            if($('html').hasClass('svg')){
                $('#vis-menu button[data-vis="tree"]').addClass('active');
                vis.render();
                timeline.show();

                /* mark the last visit */
                var lastVisit = new Date(parseInt(readCookie({{$topic->id}})));
                // start from beginning
                if(isNaN( lastVisit.getTime() )){
                    lastVisit = timeline.min;
                }
                // start from stored time
                if (lastVisit.getTime() < Date.now()){
                    timeline.mark(lastVisit);
                    timeline.mark(new Date());
                    timeline.setDate(lastVisit);
                    setTimeout(function(){ timeline.forward(); }, 500);
                }

                /* store a new cookie for the last visit mark */
                createCookie({{$topic->id}}, Date.now(), 365);
            }
        });

        var visMenu = new Menu('vis-menu', 'vis-container', 'vis-fallback', vis, {
            enabled: ['list', 'grid', 'tree', 'network'],
            default: 'tree'
        });

        var userList = new List('vis-fallback', {
            valueNames: [ 'title', 'author', 'type', 'date_ts', 'tag_1', 'tag_2', 'tag_3' ]
        });

    </script>
@stop
