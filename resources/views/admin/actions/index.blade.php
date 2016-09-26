@extends('admin.actions.master')

@section('content')
    @parent
    <h2>Videos</h2>
    <p>The list of videos currently shown on the "about bmooc" page. One of them will be randomly selected every time a user visits the bMOOC homepage</p>
    <table>
       <thead>
           <tr>
               <td>URL</td><td>delete</td>
           </tr>
       </thead>
       <tbody>
          @foreach($videos as $video)
           <tr>
               <td><a href="http://youtu.be/{{$video->url}}">{{$video->url}}</a></td>
                <td>
                    {{ Form::open(array('id'=>'delete_video', 'url'=>'admin/videos','method'=>'DELETE')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    <input type="hidden" name="id" value="{{$video->id}}"/>
                    <button type="submit">delete</button>
                    {{ Form::close() }}
                </td>
           </tr>
           @endforeach
       </tbody>
    </table>
    <h3>Add a new video</h3>
    {{ Form::open(array('id'=>'add_video', 'url'=>'admin/videos','method'=>'POST')) }}
        <label for="url">paste a link to a video <strong>on YouTube</strong>:
            <input type="text" name="url" id="url" />
        </label>
        <button type="submit">add video</button>
    {{ Form::close() }}
@endsection

@section('scripts')
    @parent
@endsection
