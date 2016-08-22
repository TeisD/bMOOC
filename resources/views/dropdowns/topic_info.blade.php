@if(isset($open))
  <div class="row dropdown open" id="info">
@else
  <div class="row dropdown" id="info">
@endif
   <div class="columns">
      <a class="close" aria-label="Close">&#215;</a>
       <p><span class="light">topic initiated</span> {{date('d/m/Y', strtotime($topic->created_at))}} <span class="light">by</span> {{$topic->author->name}}</p>
       <h3>Description</h3>
       {!!$topic->description!!}<p></p>
       <h3>Goal</h3>
       {!!$topic->goal!!}<p></p>
       <h3>Duration</h3>
       <p>{{date('d/m/Y', strtotime($topic->start_date))}} <span class="light">until</span> {{date('d/m/Y', strtotime($topic->end_date))}}</p>
       <h3>Current instruction</h3>
       <p><a href="#">show instruction history</a></p>
   </div>
</div>
