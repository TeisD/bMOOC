@if(isset($open))
  <div class="row dropdown open" id="info">
@else
  <div class="row dropdown" id="info">
@endif
    <a class="close" data-log="21" aria-label="Close">&#215;</a>
   <div class="columns">
       <span class="light">found</span> {{count($results)}} <span class="light">results</span>
       <h3>Logs</h3>
       @if (count($user->logs() == 0))
        <p>This user did not make any logs yet.</p>
        @else
        <p>logl</p>
        @endif
       {{--<p><span class="light">topic initiated</span> {{date('d/m/Y', strtotime($topic->created_at))}} <span class="light">by</span> {{$topic->author->name}}</p>
       <h3>Description</h3>
       {!!$topic->description!!}<p></p>
       <h3>Goal</h3>
       {!!$topic->goal!!}<p></p>
       <h3>Duration</h3>
       <p>{{date('d/m/Y', strtotime($topic->start_date))}} <span class="light">until</span> {{date('d/m/Y', strtotime($topic->end_date))}}</p>--}}
   </div>
</div>
