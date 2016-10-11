@extends('master')

@section('title', $artefact->topic->title)

@section('header_search')
    @include('forms.search')
@stop

@section('header_content')
    <div class="row">
      @if(!$artefact->topic->active)
        <div class="columns archived">
      @else
        <div class="columns">
      @endif
           <h2 class="inline sub"><a href="/topic/{{$artefact->topic->id}}">{{$artefact->topic->title}}</a></h2>
           @if(!$artefact->topic->active)
                <small>(inactive)</small>
            @endif
            @if($artefact->topic->archived)
                <small>(archived)</small>
            @endif
           <a data-help data-help-id="view_info" data-log="24" class="button primary indent information" data-dropdown="info">&nbsp;</a>
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
                <div class="artefact" data-log="28" data-reveal-id="artefact_lightbox_left" style="cursor: pointer !important"></div>
            </div>
            <div class="small-6 columns artefact_right" id="artefact_right">
                <div class="loader">
                    <img src="/img/loader_dark_big.gif" alt="loading..." />
                </div>
                <div class="artefact" data-log="29" data-reveal-id="artefact_lightbox_right" style="cursor:pointer !important;"></div>
            </div>
        </div>
        <div class="row">
            <div class="small-6 columns">
               <button class="primary eye" data-help data-help-id="details" data-reveal-id="artefact_lightbox_left" data-log="26">details</button>
                @if($artefact->topic->active || $user->role->id > 1)
                <button class="primary plus indent" data-help data-help-id="new_artefact" data-artefact=0 data-reveal-id="new_artefact">add (some)thing</button>
                @endif
            </div>
            <div class="small-6 columns">
               <button class="primary eye artefact_right" data-reveal-id="artefact_lightbox_right" data-help data-help-id="details" data-log="27">details</button>
               @if($artefact->topic->active || $user->role->id > 1)
                <button class="primary plus indent artefact_right" data-artefact=1 data-reveal-id="new_artefact" data-help data-help-id="new_artefact">add (some)thing</button>
                @endif
            </div>
        </div>
    </div>

    <div id="artefact_lightbox_left" class="reveal-modal full artefact_lightbox" data-reveal aria-hidden="true" role="dialog"></div>

    <div id="artefact_lightbox_right" class="reveal-modal full artefact_lightbox" data-reveal aria-hidden="true" role="dialog"></div>
@stop

@section('forms')
    {{-- NEW ARTEFACT FORM --}}
    @include('forms.master', ['form' => 'new_artefact', 'class' => 'slide', 'topic' => $artefact->topic])
@stop

@section('scripts')
   <script src="/js/imagesloaded.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function(){
            $('#vis-menu .button[data-vis=list]').addClass('disabled');
            $('#vis-menu .button[data-vis=grid]').addClass('disabled');
            $('#vis-menu .button[data-vis=network]').addClass('disabled');
            $('#vis-menu .button[data-vis=tree]').addClass('active');
        });
    </script>

    <script>

        var artefact = JSON.parse('{!! addslashes(json_encode($artefact)) !!}');
        var children = JSON.parse('{!! addslashes(json_encode($artefact->children()->with("tags")->get())) !!}');
        var artefact_id = artefact.id;
        var child_id = {{ min($child_id, count($artefact->children)-1) }}
        var a;

        $("[data-reveal-id=artefact_lightbox_left]").on('click', function(){
            $('#artefact_lightbox_left').html("");
            $('#artefact_lightbox_left').foundation('reveal', 'open', {
                url: '/artefact/'+artefact_id,
            });
            return false;
        });
        $("[data-reveal-id=artefact_lightbox_right]").on('click', function(){
            $('#artefact_lightbox_right').html("");
            $('#artefact_lightbox_right').foundation('reveal', 'open', {
                url: '/artefact/'+children[child_id].id,
            });
            return false;
        });

        $(document).on('opened.fndtn.reveal', '#artefact_lightbox_left[data-reveal]', function () {
            render($('#artefact_lightbox_left'), artefact, 'original');
        });

        $(document).on('opened.fndtn.reveal', '#artefact_lightbox_right[data-reveal]', function () {
            render($('#artefact_lightbox_right'), children[child_id], 'original');
        });

        $('[data-reveal-id=new_artefact]').on('click', function(){
            a = $(this).data('artefact');
        });

        // stop youtube and vimeo playback
        $(document).on('open.fndtn.reveal', '[data-reveal]', function () {
            $('iframe').each(function(){
                var src = $(this).attr("src");
                $(this).attr("src", "");
                $(this).attr("src", src);
            });
        });

        $(document).on('open.fndtn.reveal', '#new_artefact', function (event) {
            $("#parent_id", $(this)).attr('value', (a ? children[child_id].id : artefact_id));
            // update tags
            $("#old_tags div", $(this)).remove();
            console.log(children[child_id]);
            $.each((a ? children[child_id].tags : artefact.tags), function (k, tag) {
                $('#old_tags').prepend('<div class="tag-button purple"><label><input  type="checkbox" data-abide-validator="tag_select" name="old_tags[]" value="' + tag.tag + '"><span>' + tag.tag + '</span></label></div>\n');
            });
        });

        showChild();
        showArtefact();
        history.replaceState({
            artefact: artefact,
            children: children,
            artefact_id: artefact_id,
            child_id: child_id
        }, "", "/relation/"+artefact_id+"/"+child_id);

        function up(){
            if(child_id > 0){
                child_id--;
                showChild();
                updateUrl();
            }
        }

        function down(){
            if(child_id < children.length-1){
                child_id++;
                showChild();
                updateUrl();
            }
        }

        function right(){
            if(children[child_id].has_children){
                artefact = children[child_id];
                artefact_id = artefact.id;
                child_id = 0;
                showArtefact();
                // load children
                $.getJSON("/json/artefact/"+artefact.id+"/children", function(data){
                    children = data;
                    showChild();
                    updateUrl();
                });
            }
        }

        function left(){
            if(artefact.has_parent){
                artefact_id = artefact.parent_id;
                prev_id = artefact.id;
                $.when(
                    $.getJSON("/json/artefact/"+artefact.parent_id+"/children", function(data){
                        children = data;
                        child_id = 0;
                        // find child_id
                        $.each(children, function(index, child){
                            if (child.id == prev_id) child_id = index
                        });
                        showChild();
                    }),
                    // load parent of artefact & set artefact
                    $.getJSON("/json/artefact/"+artefact.parent_id, function(data){
                        artefact = data;
                        showArtefact();
                    })
                ).then(function(){
                    updateUrl();
                });
            }

        }

        function showArtefact(){
            if(artefact.has_parent) $("#nav_left").show();
            else $("#nav_left").hide();
            render($('#artefact_left'), artefact);
        }

        function showChild(){
            if(children.length <= 0) {
                $("#nav_down").hide();
                $("#nav_up").hide();
                $("#nav_right").hide();
                $(".artefact_right").hide();
                return;
            }
            if(child_id >= children.length-1) $("#nav_down").hide();
            else $("#nav_down").show();
            if(child_id <= 0) $("#nav_up").hide();
            else $("#nav_up").show();
            if(children[child_id].has_children) $("#nav_right").show();
            else $("#nav_right").hide();

            $(".artefact_right").show();
            render($('#artefact_right'), children[child_id]);
        }

        function updateUrl(){
            var stateObj = { artefact: artefact, children: children, artefact_id: artefact_id, child_id: child_id };
            history.pushState(stateObj, "", "/relation/"+artefact_id+"/"+child_id);
        }

        window.onpopstate = function(event) {
            if (event.state) {
                if(artefact != event.state.artefact || artefact_id != event.state.artefact_id){
                    artefact = event.state.artefact;
                    artefact_id = event.state.artefact_id;
                    showArtefact();
                }
                if(children != event.state.children || child_id != event.state.child_id){
                    children = event.state.children;
                    child_id = event.state.child_id;
                    showChild();
                }
            }
        };

        $('nav #nav_up').on('click', up);
        $('nav #nav_down').on('click', down);
        $('nav #nav_left').on('click', left);
        $('nav #nav_right').on('click', right);

        document.onkeydown = function(evt) {
            evt = evt || window.event;
            switch (evt.keyCode) {
                case 37:
                    left();
                    break;
                case 38:
                    up();
                    break;
                case 39:
                    right();
                    break;
                case 40:
                    down();
                    break;
            }
            //evt.preventDefault();
        };

    </script>
@stop
