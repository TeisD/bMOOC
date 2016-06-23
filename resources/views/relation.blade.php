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
        <div class="small-6 columns">
            <div class="loader" id="artefact_left_loader">
                <img src="/img/loader_dark_big.gif" alt="loading..." />
            </div>
            <div class="artefact" id="artefact_left_contents" data-reveal-id="artefact_lightbox_left"></div>
        </div>
        <div class="small-6 columns">
            <div class="loader" id="artefact_right_loader">
                <img src="/img/loader_dark_big.gif" alt="loading..." />
            </div>
            <div class="artefact" id="artefact_right_contents" data-reveal-id="artefact_lightbox_right"></div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $('#vis-menu button[data-vis=list]').addClass('disabled');
        $('#vis-menu button[data-vis=grid]').addClass('disabled');
        $('#vis-menu button[data-vis=network]').addClass('disabled');
    </script>
@stop
