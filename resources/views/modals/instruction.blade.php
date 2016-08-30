@extends('modals.master')
  @section('modal_content')
   <div class="row full">
    <div class="columns large-3">
        <h2 data-attr="title">{{ $instruction->title }}</h2>
        <dl class="details">
           <div class="row">
               <div class="small-6 medium-3 large-12 columns">
                <dt>Added</dt>
                <dd data-attr="created_at">{{date('d/m/Y H:i', strtotime($instruction->created_at))}}</dd>
               </div>
               @if(isset($instruction->active_until))
               <div class="small-6 medium-3 large-12 columns">
                <dt>Active until</dt>
                <dd data-attr="created_at">{{date('d/m/Y H:i', strtotime($instruction->active_until))}}</dd>
               </div>
               @endif
               <div class="small-6 medium-3 large-12 columns">
                <dt>By</dt>
                <dd data-attr="author"><a href="/search/{{$instruction->author->id}}">{{$instruction->author->name}}</a></dd>
               </div>
            </div>
        </dl>
    </div>
    <div class="columns large-9" id="artefact">
        <div class="loader">
            <img src="/img/loader_overlay_big.gif" alt="loading..." />
        </div>
        <div class="artefact">
        </div>
    </div>
</div>
@endsection
