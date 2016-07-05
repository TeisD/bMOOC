{{-- options: ['form' => 'feedback', 'class' => 'small'] --}}
<div id="{{$form}}" class="reveal-modal {{ $class }}" data-reveal aria-hidden="true" role="dialog">
    @include('forms.'.$form)
    <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
</div>

<script src="/js/jquery.form.min.js"></script>
