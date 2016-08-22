@extends('master')

@section('title', $artefact->title)

@section('header_search')
    @include('forms.search')
@stop

@section('header_content')
    <div class="row">
       <div class="columns">
           <h2 class="inline sub"><a href="/topic/{{$artefact->topic->id}}">{{$artefact->topic->title}}</a></h2>
           <a class="button primary indent information" data-dropdown="info">&nbsp;</a>
       </div>
   </div>
    @include('dropdowns.topic_info', ['topic'=>$artefact->topic])

@stop

@section('content')
   <div id="relation">
        <nav>
            <a href="javascript:;" class="relation_nav" id="nav_up">&uarr;</a>
            <a href="javascript:;" class="relation_nav" id="nav_right">&rarr;</a>
            <a href="javascript:;" class="relation_nav" id="nav_down">&darr;</a>
            <a href="javascript:;" class="relation_nav" id="nav_left">&larr;</a>
        </nav>
        <div class="row full">
            <div class="small-6 columns" id="artefact_left">
                <div class="loader">
                    <img src="/img/loader_dark_big.gif" alt="loading..." />
                </div>
                <div class="artefact" data-reveal-id="artefact_lightbox_left" style="cursor: pointer !important"></div>
            </div>
            <div class="small-6 columns" id="artefact_right">
                <div class="loader">
                    <img src="/img/loader_dark_big.gif" alt="loading..." />
                </div>
                <div class="artefact" data-reveal-id="artefact_lightbox_right" style="cursor:pointer !important;"></div>
            </div>
        </div>
    </div>

    <div id="artefact_lightbox_left" class="reveal-modal full" data-reveal aria-hidden="true" role="dialog">
       @include('modals.artefact', ['artefact' => $artefact])
        <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
    </div>

    <div id="artefact_lightbox_right" class="reveal-modal full" data-reveal aria-hidden="true" role="dialog">
       @include('modals.artefact', ['artefact' => $artefact->children[min($child, count($artefact->children)-1)]])
        <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
    </div>
@stop

@section('scripts')
   <script src="/js/imagesloaded.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function(){
            $('#vis-menu .button[data-vis=list]').addClass('disabled');
            $('#vis-menu .button[data-vis=grid]').addClass('disabled');
            $('#vis-menu .button[data-vis=network]').addClass('disabled');
        });
    </script>

    <script>

        var artefact = JSON.parse('{!! addslashes(json_encode($artefact)) !!}');
        var children = JSON.parse('{!! addslashes(json_encode($artefact->children)) !!}');
        var child_id = {{ min($child, count($artefact->children)-1) }}

        $(document).on('open.fndtn.reveal', '#artefact_lightbox_left[data-reveal]', function () {
            $(document).off('open.fndtn.reveal', '#artefact_lightbox_left[data-reveal]')
            render($('#artefact_lightbox_left'), artefact, 'original');
        });

        $(document).on('open.fndtn.reveal', '#artefact_lightbox_right[data-reveal]', function () {
            $(document).off('open.fndtn.reveal', '#artefact_lightbox_right[data-reveal]')
            render($('#artefact_lightbox_right'), child, 'original');
        });

        showChild();
        showArtefact();

        function up(){
            if(child_id > 0){
                child_id--;
                showChild();
            }
        }

        function down(){
            if(child_id < children.length-1){
                child_id++;
                showChild();
            }
        }

        function right(){
            if(children[child_id].has_children){
                artefact = children[child_id];
                showArtefact();
                // load children
                $.getJSON("/json/artefact/"+artefact.id+"/children", function(data){
                    children = data;
                    console.log(children);
                    child_id = 0;
                    showChild();
                });
            }
        }

        function left(){
            if(artefact.has_parent){
                // load parent of artefact & set artefact
                $.getJSON("/json/artefact/"+artefact.parent_id+", function(data){
                    artefact = data;
                    showArtefact();
                });
                // load children of artefact & set child
            }

        }

        function showArtefact(){
            if(artefact.has_parent) $("#nav_left").show();
            else $("#nav_left").hide();
            render($('#artefact_left'), artefact);
        }

        function showChild(){
            if(child_id >= children.length-1) $("#nav_down").hide();
            else $("#nav_down").show();
            if(child_id <= 0) $("#nav_up").hide();
            else $("#nav_up").show();
            if(children[child_id].has_children) $("#nav_right").show();
            else $("#nav_right").hide();
            render($('#artefact_right'), children[child_id]);
        }

    </script>
@stop
