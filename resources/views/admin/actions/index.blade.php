@extends('admin.actions.master')

@section('content')
    @parent
    <p>&nbsp;</p>
    <div class="row">
        <div class="columns">
            <h2>Videos</h2>
            <p>The list of videos currently shown on the "about bmooc" page. One of them will be randomly selected every time a user visits the bMOOC homepage</p>
        </div>
    </div>
    <div class="row">
       <div class="columns large-6">
            <h3>Add a new video</h3>
            {{ Form::open(array('id'=>'add_video', 'url'=>'admin/videos','method'=>'POST')) }}
                <label for="url">paste a link to a video <strong>on YouTube</strong>:
                    <input type="text" name="url" id="url" />
                </label>
                <button type="submit">add video</button>
            {{ Form::close() }}
        </div>
        <div class="columns large-6">
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
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="row">
        <div class="columns">
        <h2>Users</h2>
        <p>The list of all bMOOC users. You can set their roles to:
        <ul>
            <li><strong>author</strong> - a student who can add contributions to a topic</li>
            <li><strong>moderator</strong> - a teacher who can make new topics and add instructions</li>
            <li><strong>admin</strong> - an administrator who can access this control panel</li>
        </ul>
        <table>
            <thead>
                <tr>
                    <td>Name</td><td># contributions</td><td>Role</td><td>Delete</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->artefacts->count()}}</td>
                    <td>
                        <select id="{{$user->id}}">
                            <option value="1" @if($user->role->id == 1) selected @endif >author</option>
                            <option value="2" @if($user->role->id == 2) selected @endif >moderator</option>
                            <option value="3" @if($user->role->id == 3) selected @endif >admin</option>
                        </select>
                    </td>
                    <td>
                       @if ($user->artefacts->count() == 0)
                        <button data-id="{{$user->id}}" class="button alert delete">delete</button>
                        @else
                        <button disabled class="button alert">delete</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script>
        $('select').change(function(){
            var id = $(this).attr('id');
            var role = $('option:selected', $(this)).attr('value');
            $.post('/admin/users', {
                'id': id,
                'role': role,
                '_token': '{{ csrf_token() }}'
            }, function(data){
                console.log(data);
            });
        })

        $('.delete').on('click', function(){
            var id = $(this).data('id');
            var c = confirm('Are you sure you want to delete this user?');
            if(c){
                $.ajax({
                  type: "POST",
                  url: '/admin/users/delete',
                  data: {
                    'id': id,
                    '_token': '{{ csrf_token() }}'
                  },
                  success: function(){ location.reload() }
                });
            }
        })
    </script>
@endsection
