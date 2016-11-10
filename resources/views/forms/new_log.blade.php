<div class="row">
    <div class="columns large-10 large-offset-1">
        <h2>Create a new log</h2>
        <p>A log is recording of a sequence of actions on the platform directed by yourself. The result is a detailed list of button clicks and commands which other users can execute to reconstruct, understand and get inspired by your way of reading, navigating and exploring bMOOC.</p>
        {{ Form::open(array('id'=>'new_log_form', 'data-abide'=>'ajax', 'url'=>'log/new','method'=>'POST')) }}
            {{-- INPUT: topic_title --}}
            <div class="row"><div class="columns">
                <label>Title:
                    <input type="text" required id="topic_title" name="title" @if(isset($edit)) value="{{$topic->title}}" @endif/>
                </label>
                <small class="error">Please enter a title for your log.</small>
            </div></div>

        <p>When you click <strong>start logging</strong>, all your actions on the platform will be recorded, until you click the <strong>stop logging</strong> button which will appear on top of your screen.</p>
            <button type="submit">Start logging</button>
        {{ Form::close() }}
    </div>
</div>

<script>
    $('#new_log_form').submit(function(ev) {
        createCookie('logging', 1, 1);
    });
</script>
