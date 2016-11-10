@if(isset($open))
  <div class="row dropdown open" id="info">
@else
  <div class="row dropdown" id="info">
@endif
    <a class="close" data-log="21" aria-label="Close">&#215;</a>
   <div class="columns">
       <p><span class="light">found</span> {{count($results)}} <span class="light">results</span></p>
       <h3>Logs</h3>
       @if ($user->logs->count() == 0)
        <p>This user did not make any logs yet.</p>
        @else
        <ul class="no-list">
        @foreach ($user->logs as $log)
            <li><a href="/log/{{$log->id}}" target="_blank" class="show-log external" data-log-id="{{$log->id}}">{{$log->created_at->format('d/m/Y')}} - {{$log->title}}</a></li>
        @endforeach
        </ul>
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
