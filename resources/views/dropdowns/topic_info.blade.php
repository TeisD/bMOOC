@if(isset($open))
  <div class="row dropdown open" id="info">
@else
  <div class="row dropdown" id="info">
@endif
    <a class="close" aria-label="Close">&#215;</a>
    @if(count($topic->instructions) > 0)
   <div class="columns medium-6">
      @else
   <div class="columns">
    @endif
       <p><span class="light">topic initiated</span> {{date('d/m/Y', strtotime($topic->created_at))}} <span class="light">by</span> {{$topic->author->name}}</p>
       <h3>Description</h3>
       {!!$topic->description!!}<p></p>
       <h3>Goal</h3>
       {!!$topic->goal!!}<p></p>
       <h3>Duration</h3>
       <p>{{date('d/m/Y', strtotime($topic->start_date))}} <span class="light">until</span> {{date('d/m/Y', strtotime($topic->end_date))}}</p>
   </div><!--
   --><div class="columns medium-6">
       @if(isset($topic->activeInstruction))
       <h3>Active instruction</h3>
      <p><a href="javascript:;" data-reveal-id="instruction_lightbox" data-reveal-ajax="/instruction/{{$topic->activeInstruction->id}}">{{$topic->activeInstruction->title}}</a></p>
      @endif
      @if(count($topic->prevInstructions) > 0)
      <h3>Previous instructions</h3>
      @foreach($topic->prevInstructions as $instruction)
       <ul class="no-list">
           <li><a href="javascript:;" data-reveal-id="instruction_lightbox" data-reveal-ajax="/instruction/{{$instruction->id}}" class="emphasis">{{$instruction->title}}</a></li>
        </ul>
       @endforeach
       @endif
   </div>
</div>

<div id="instruction_lightbox" class="reveal-modal full" data-reveal aria-hidden="true" role="dialog"></div>

<script>
    $(function(){
       $('[data-reveal-id=instruction_lightbox]').on('click', function(){

       });
    });

    $(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
        var modal = $(this);
    });
</script>
