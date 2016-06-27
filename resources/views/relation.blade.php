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
   <div class="row dropdown" id="info">
       <div class="columns">
          <a class="close" aria-label="Close">&#215;</a>
           <p><span class="light">topic initiated</span> {{date('d/m/Y', strtotime($artefact->topic->created_at))}} <span class="light">by</span> {{$artefact->topic->author->name}}</p>
           <h3>Description</h3>
           <p>{{$artefact->topic->description}}</p>
           <h3>Goal</h3>
           <p>{{$artefact->topic->goal}}</p>
           <h3>Duration</h3>
           <p>{{date('d/m/Y', strtotime($artefact->topic->start_date))}} <span class="light">until</span> {{date('d/m/Y', strtotime($artefact->topic->end_date))}}</p>
       </div>
   </div>
@stop

@section('content')
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

    <div id="artefact_lightbox_left" class="reveal-modal full" data-reveal aria-hidden="true" role="dialog">
       @include('modals.artefact', ['artefact' => $artefact->parent])
        <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
    </div>

    <div id="artefact_lightbox_right" class="reveal-modal full" data-reveal aria-hidden="true" role="dialog">
       @include('modals.artefact', ['artefact' => $artefact])
        <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
    </div>
@stop

@section('scripts')
   <script src="/js/imagesloaded.min.js" type="text/javascript"></script>

    <script>
        $('#vis-menu button[data-vis=list]').addClass('disabled');
        $('#vis-menu button[data-vis=grid]').addClass('disabled');
        $('#vis-menu button[data-vis=network]').addClass('disabled');
    </script>

    <script>
        var artefact = JSON.parse('{!! addslashes(json_encode($artefact)) !!}');
        var parent = JSON.parse('{!! addslashes(json_encode($artefact->parent)) !!}');

        render($('#artefact_left'), parent);
        render($('#artefact_right'), artefact);

        $(document).on('open.fndtn.reveal', '#artefact_lightbox_left[data-reveal]', function () {
            $(document).off('open.fndtn.reveal', '#artefact_lightbox_left[data-reveal]')
            render($('#artefact_lightbox_left'), parent, 'original');
        });

        $(document).on('open.fndtn.reveal', '#artefact_lightbox_right[data-reveal]', function () {
            $(document).off('open.fndtn.reveal', '#artefact_lightbox_right[data-reveal]')
            render($('#artefact_lightbox_right'), artefact, 'original');
        });

    </script>
@stop
