{{-- options: ['form' => 'feedback', 'class' => 'small', 'ajax' => 'true'] --}}
<div id="{{$form}}" class="reveal-modal {{ $class }}" data-reveal aria-hidden="true" role="dialog">
    @include('forms.'.$form)
    <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
</div>

@if(isset($ajax))
<script src="/js/jquery.form.min.js"></script>
@endif
