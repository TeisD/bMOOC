@extends('master')
<?php $edit = true; ?>
{{-- options: ['form' => 'feedback'] --}}
@section('content')
   <div class="row" id="{{$form}}">
       <div class="columns large-8 large-push-2 medium-10 medium-push-1">
            @include('forms.'.$form)
       </div>
   </div>
@endsection

@section('scripts')
    <script src="/js/jquery.form.min.js"></script>
@endsection
