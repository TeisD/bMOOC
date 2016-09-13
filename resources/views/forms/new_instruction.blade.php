<div class="row">
    <div class="columns large-10 large-offset-1">
<h2>Add a new instruction</h2>
<p>Add an instruction to the topic. An instruction could be a text, an image, a video or a pdf document you created.</p>
{{ Form::open(array('id'=>'new_topic_form', 'data-abide'=>'ajax', 'url'=>'instruction/new','method'=>'POST', 'files'=>true)) }}
      <input type="text" hidden="hidden" id="id" name="id" value="{{$topic->id}}"/>

    {{-- INPUT: topic_title --}}
    <div class="row"><div class="columns">
        <label>Title:
            <input type="text" required id="title" name="title" />
        </label>
        <small class="error">Please enter a title for the instruction.</small>
    </div></div>

    {{-- INPUT: description --}}
    <div class="row"><div class="columns">
        @include('forms.elements.artefacteditor', ['id' => 'instruction_af', 'required' => 'required'])
    </div></div>

    <button type="submit">
        Add instruction
    </button>
{{ Form::close() }}
    </div>
</div>

<script src="/js/pdf.js"></script>
<script src="/js/quill.min.js" async onload="quill_instruction_init();"></script>
<script>

    function quill_instruction_init(){
        $(function(){
            var artefact = new Quill('#instruction_af .ql_editor', {
                modules: {
                    'toolbar': { container: '#instruction_af .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
        });
    }
</script>
