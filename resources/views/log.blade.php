@extends('master_simple')

@section('content')
   <div class="row">
       <div class="columns text-center">
          <h2>{{$log->title}}</h2>
           <p><span class="light">recorded</span> {{$log->created_at->format('d/m/Y')}} <span class="light">by</span> <a href="/search/{{$log->author->name}}">{{$log->author->name}}</a></p>
           <table class="clean center">
               <thead>
                   <tr>
                       <th>timestamp</th>
                       <th>action</th>
                       <th>description</th>
                   </tr>
               </thead>
               <tbody>
                   @foreach($log->commands as $command)
                       @if($command->event == 'page')
                          <tr class="light">
                        @else
                          <tr>
                        @endif
                           <td>{{$command->created_at}}</td>
                           <td>{{$command->event}}</td>
                           <td>{{$command->command}}</td>
                       </tr>
                   @endforeach
               </tbody>
           </table>
       </div>
   </div>
@endsection
