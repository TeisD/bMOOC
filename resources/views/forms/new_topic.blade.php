<div class="row">
    <div class="columns large-10 large-offset-1">
<h2>Start a new topic</h2>
<p>Initiate a topic by filling in a title and a description for the topic, the goal of the topic and it's start and end date. Choose your words carefully, as this information might might have a big impact on how the students understand and use the topic.</p>
{{ Form::open(array('id'=>'new_topic_form', 'data-abide'=>'ajax', 'url'=>'topic/new','method'=>'POST', 'files'=>true)) }}
    {{-- INPUT: topic_title --}}
    <div class="row"><div class="columns">
        <label>Title:
            <input type="text" required id="title" name="title"/>
        </label>
        <small class="error">Please enter a title for the topic.</small>
    </div></div>

    {{-- INPUT: description --}}
    <div class="row"><div class="columns">
        <label>Description:</label>
        @include('forms.elements.texteditor', ['id' => 'description'])
        <small class="error">Please enter a description for the topic.</small>
    </div></div>

    {{-- INPUT: goal --}}
    <div class="row"><div class="columns">
        <label>Goal:</label>
        @include('forms.elements.texteditor', ['id' => 'goal'])
        <small class="error">Please enter a goal for the topic.</small>
    </div></div>

    <fieldset class="form-inline">
       <h3>Topic duration</h3>
        {{-- DATEPICKER: date --}}
        <div class="row"><div class="columns large-6">
            <label for="start_date">Start date:</label>
            <div class="field">
                <input type="date" id="start_date" value="dd/mm/yyyy" data-date-format="dd/mm/yyyy" name="start_date" required />
                <small class="error">Please select a start date for the topic.</small>
            </div>
        </div></div>

        <div class="row"><div class="columns large-6">
            <label for="end_date">End date:</label>
            <div class="field">
                <input type="date" id="end_date" value="dd/mm/yyyy" data-date-format="dd/mm/yyyy" name="end_date" required />
                <small class="error">Please select an end date for the topic.</small>
            </div>
        </div></div>
    </fieldset>

    <button type="submit">Add topic</button>
{{ Form::close() }}
    </div>
</div>

<script src="/js/foundation-datepicker.min.js" async onload="datepicker_init();"></script>
<script src="/js/quill.min.js" async onload="quill_init();"></script>
<script>
    function datepicker_init(){
        $(function(){
            $('#new_topic #start_date').fdatepicker();
            $('#new_topic #end_date').fdatepicker();
        });
    }

    function quill_init(){
        $(function(){
            var description = new Quill('#description .ql_editor', {
                modules: {
                    'toolbar': { container: '#description .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
            var goal = new Quill('#goal .ql_editor', {
                modules: {
                    'toolbar': { container: '#goal .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
        });
    }
</script>
