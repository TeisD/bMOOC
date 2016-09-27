@extends('master')

@section('title', 'Search results')

@section('header_actions')
@stop

@section('header_search')
    @include('forms.search', ['currentAuthor'=>$currentAuthor, 'currentTag'=>$currentTag, 'currentKeyword'=>$currentKeyword])
@stop

@section('header_content')
    <div class="row">
        <div class="columns">
            <h2 class="inline sub"><a href="#">Search results for
            @if(isset($currentAuthor) && $currentAuthor != "all")
            author: <em>{{$currentAuthor}}</em>
                @if((isset($currentTag) && $currentTag != "all") || isset($currentKeyword))
                and
                @endif
            @endif
            @if(isset($currentTag) && $currentTag != "all")
            tag: <em>{{$currentTag}}</em>
                @if(isset($currentKeyword))
                and
                @endif
            @endif
            @if(isset($currentKeyword))
            keyword: <em>{{$currentKeyword}}</em>
            @endif
            </a></h2>
           <a class="button primary indent information" data-dropdown="info">&nbsp;</a>
       </div>
   </div>
   @include('dropdowns.search_info', ['open'=>'open'])
@stop

@section('content')
   @if(count($results) > 0)
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
                        <a href="javascript:;" class="sort" data-sort="topic">Topic</a>
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
           @foreach($results as $artefact)
           <li>
                <div class="row">
                    <a href="/relation/{{$artefact->id}}">
                   <div class="columns large-12" vis-grid>
                      @if($artefact->type_id > 28)
                       <img src="/artefact/{{$artefact->id}}/thumbnail" alt="{{$artefact->title}}"/>
                        @else
                       <h3>{{$artefact->title}}</h3>
                       @endif
                   </div>
                       </a>
                    <div class="columns large-3" vis-list>
                        <h3 class="title inline">
                            <a href="/relation/{{$artefact->id}}">{{ $artefact->title }}</a>&nbsp;
                        </h3>
                        <span class="title_clean" hidden="hidden" style="display:none"><?php
                            $s = str_replace(' ', '_', $artefact->title);
                            echo preg_replace('/[^A-Za-z0-9\_]/', '', $s) ?></span>
                    </div>
                    <div class="columns large-3" vis-list>
                        <span class="topic"><a href="/topic/{{$artefact->topic['id']}}">{{$artefact->topic->title}}</a></span>
                    </div>
                    <div class="columns large-1" vis-list>
                        <span class="type">{{ $artefact->type->type }}</span>
                    </div>
                    <div class="columns large-2" vis-list>
                        <span class="light">added</span>
                        <span class="date">{{date('d/m/Y', strtotime($artefact->created_at))}}</span>
                        <span class="date_ts" hidden="hidden" style="display: none;">{{$artefact->created_at}}</span>
                        <span class="light">by</span>
                        <a href="/search/{{$artefact->author->name}}"><span class="author">{{$artefact->author->name}}</span></a>
                    </div>
                    <div class="columns large-1" vis-list>
                        <span class="tag_1">
                           @if(isset($artefact->tags[0]))
                           <a href="/search/all/{{$artefact->tags[0]->tag}}">
                            {{ $artefact->tags[0]->tag }}
                            </a>
                            @endif
                        </span>
                    </div>
                    <div class="columns large-1" vis-list>
                        <span class="tag_2">
                           @if(isset($artefact->tags[1]))
                           <a href="/search/all/{{$artefact->tags[1]->tag}}">
                            {{ $artefact->tags[1]->tag }}
                            </a>
                            @endif
                        </span>
                    </div>
                    <div class="columns large-1 end" vis-list>
                        <span class="tag_3">
                            @if(isset($artefact->tags[2]))
                            <a href="/search/all/{{$artefact->tags[2]->tag}}">
                            {{ $artefact->tags[2]->tag }}
                            </a>
                            @endif
                        </span>
                    </div>
                </div>
            </a></li>
            @endforeach
          </ul>
        </div>
    </div>
    @endif
@stop

@section('scripts')
  <script src="/js/d3.min.js"></script>
  <script src="/js/d3plus.min.js"></script>
  <script src="/js/list.min.js"></script>
    <script>
        var data = {};
        data.list = JSON.parse('{!! addslashes(json_encode($results)) !!}');
        data.links = JSON.parse('{!! addslashes(json_encode($links)) !!}');

        var vis = new Vis($('#vis-container').get(0), data, 'relation', {
            interactive: true,
            type: 'network',
            rotate: false,
            mode: 'all',
            fit: true,
            collide: false,
            resize: true
        });
        var timeline = new Timeline(vis)

        $(document).ready(function(){
            if($('html').hasClass('svg')){
                $('#vis-menu button[data-vis="tree"]').addClass('active');
                vis.render();
                timeline.show();
            }
        });

        var visMenu = new Menu('vis-menu', 'vis-container', 'vis-fallback', vis, {
            enabled: ['list', 'grid', 'network'],
            default: 'network'
        });

        var userList = new List('vis-fallback', {
            valueNames: [ 'title', 'topic', 'author', 'type', 'date_ts', 'tag_1', 'tag_2', 'tag_3' ]
        });

        userList.sort('last_addition_ts', { order: "desc" });

    </script>
@stop
