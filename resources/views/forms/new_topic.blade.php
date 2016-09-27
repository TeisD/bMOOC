<div class="row">
    <div class="columns large-10 large-offset-1">
@if(isset($edit))
<h2>Edit topic <em>{{$topic->title}}</em></h2>
@else
<h2>Start a new topic</h2>
@endif
<p>Initiate a topic by filling in a title and a description for the topic, the goal of the topic and it's start and end date. Choose your words carefully, as this information might have a big impact on how the students understand and use the topic.</p>
{{ Form::open(array('id'=>'new_topic_form', 'data-abide'=>'ajax', 'url'=>'topic/new','method'=>'POST', 'files'=>true)) }}
   @if(isset($edit))
   {{ method_field('PATCH') }}
   <input type="text" hidden="hidden" id="topic_id" name="topic_id" value="{{$topic->id}}"/>
   @endif
    {{-- INPUT: topic_title --}}
    <div class="row"><div class="columns">
        <label>Title:
            <input type="text" required id="topic_title" name="title" @if(isset($edit)) value="{{$topic->title}}" @endif/>
        </label>
        <small class="error">Please enter a title for the topic.</small>
    </div></div>

    {{-- INPUT: description --}}
    <div class="row"><div class="columns">
        <label>Description:</label>
        @if(isset($edit))
            @include('forms.elements.texteditor', ['id' => 'topic_description', 'required' => 'required', 'error_msg' => 'Please enter a description for the topic.', 'content' => $topic->description])
        @else
            @include('forms.elements.texteditor', ['id' => 'topic_description', 'required' => 'required', 'error_msg' => 'Please enter a description for the topic.'])
        @endif
    </div></div>

    {{-- INPUT: goal --}}
    <div class="row"><div class="columns">
        <label>Goal:</label>
        @if(isset($edit))
            @include('forms.elements.texteditor', ['id' => 'topic_goal', 'required' => 'required', 'error_msg' => 'Please enter a goal for the topic.', 'content' => $topic->goal])
        @else
            @include('forms.elements.texteditor', ['id' => 'topic_goal', 'required' => 'required', 'error_msg' => 'Please enter a goal for the topic.'])
        @endif
    </div></div>

    <fieldset class="form-inline">
       <h3>Topic duration</h3>
        {{-- DATEPICKER: date --}}
        <div class="row"><div class="columns large-6">
            <label for="topic_start_date">Start date:</label>
            <div class="field">
                <input type="date" id="topic_start_date" pattern="day_month_year" placeholder="dd/mm/yyyy" @if(isset($edit)) value="{{ date_format(new DateTime($topic->start_date), 'd/m/Y')}}" @endif data-date-format="dd/mm/yyyy" name="start_date" required />
                <small class="error">Please select a start date for the topic.</small>
            </div>
        </div></div>

        <div class="row"><div class="columns large-6">
            <label for="topic_end_date">End date:</label>
            <div class="field">
                <input type="date" id="topic_end_date" pattern="day_month_year" placeholder="dd/mm/yyyy" @if(isset($edit)) value="{{ date_format(new DateTime($topic->end_date), 'd/m/Y')}}" @endif data-date-format="dd/mm/yyyy" name="end_date" required />
                <small class="error">Please select an end date for the topic.</small>
            </div>
        </div></div>
    </fieldset>

    <button type="submit">
        @if(isset($edit))
            Save changes
        @else
            Add topic
        @endif
    </button>
{{ Form::close() }}
    </div>
</div>

<script src="/js/foundation-datepicker.min.js" async onload="datepicker_init();"></script>
<script src="/js/quill.min.js" async onload="quill_topic_init();"></script>
<script>
    function datepicker_init(){
        $(function(){
            $('#new_topic #topic_start_date').fdatepicker();
            $('#new_topic #topic_end_date').fdatepicker();
        });
    }

    function quill_topic_init(){
        $(function(){
            var description = new Quill('#topic_description .ql_editor', {
                modules: {
                    'toolbar': { container: '#topic_description .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
            var goal = new Quill('#topic_goal .ql_editor', {
                modules: {
                    'toolbar': { container: '#topic_goal .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
        });
    }
</script>
