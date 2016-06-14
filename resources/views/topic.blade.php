@extends('master')

@section('title', $topic->title)

@section('header_actions')
    @if (isset($user) && $user->role=="editor")
        <button class="primary plus" data-help="index" data-help-id="new_topic" data-reveal-id="new_topic">Start a new topic</button>
    @endif
@stop

@section('header_search')
    @include('forms.search')
@stop

@section('content')
   <div class="row">
       <div class="columns">
           <h2 class="sub">{{$topic->title}}</h2>
       </div>
   </div>
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
                        <a href="#" class="sort" data-sort="type">Type</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="date_ts">Date added</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="author">Author</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="tag_1">Tag 1</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="tag_2">Tag 2</a>
                    </li>
                    <li>
                        <a href="#" class="sort" data-sort="tag_3">Tag 3</a>
                    </li>
                  </ul>
              </div>
          </div>
          <ul class="list block">
           @foreach($topic->artefacts as $artefact)
           <li>
                <div class="row">
                <div class="columns large-4">
                    <h3 class="title">
                        {{ $artefact->title }}
                    </h3>
                    <span class="title_clean" hidden="hidden" style="display:none"><?php
                        $s = str_replace(' ', '_', $artefact->title);
                        echo preg_replace('/[^A-Za-z0-9\_]/', '', $s) ?></span>
                </div>
                <div class="columns large-1">
                    <span class="type">{{ $artefact->type->type }}</span>
                </div>
                <div class="columns large-3">
                    <span class="light">added</span>
                    <span class="date">{{date('d/m/Y', strtotime($artefact->created_at))}}</span>
                    <span class="date_ts" hidden="hidden" style="display: none;">{{$artefact->created_at}}</span>
                    <span class="light">by</span>
                    <span class="author">{{$artefact->author->name}}</span>
                </div>
                <div class="columns large-1">
                    <span class="tag_1">
                        {{ $artefact->tags[0]->tag }}
                    </span>
                </div>
                <div class="columns large-1">
                    <span class="tag_2">
                        {{ $artefact->tags[1]->tag }}
                    </span>
                </div>
                <div class="columns large-1 end">
                    <span class="tag_3">
                        {{ $artefact->tags[2]->tag }}
                    </span>
                </div>

            </div>
            </li>
            @endforeach
          </ul>
        </div>
    </div>
@stop


@section('scripts')
  <script src="/js/d3.min.js"></script>
  <script src="/js/d3plus.min.js"></script>
  <script src="/js/list.min.js"></script>
    <script>
        var data = {};
        data.tree = JSON.parse('{!! addslashes(json_encode($tree)) !!}');
        data.list = JSON.parse('{!! addslashes(json_encode($list)) !!}');

        var vis;

        $(document).ready(function(){
            if($('html').hasClass('svg')){
                vis = new Vis($('#vis-container').get(0), data, {
                    interactive: true,
                    mode: 'all',
                    fit: true,
                    collide: false,
                    resize: true,
                    rotate: true
                });
                timeline = new Timeline(vis)
                $('#vis-menu button[data-vis="tree"]').addClass('active');
                vis.render('tree');
                timeline.show();
            }
        });


        var visMenu = new Menu('vis-menu', 'vis-container', 'vis-fallback');

        var userList = new List('vis-fallback', {
            valueNames: [ 'title', 'author', 'type', 'date_ts', 'tag_1', 'tag_2', 'tag_3' ]
        });
    </script>
@stop
