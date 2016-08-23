<div class="row">
    <div class="columns large-10 large-offset-1">
<h2>Add (some)thing</h2>
<p>Add something to the topic. Your artefact will be linked to the preceding additions, and to artefacts from different topics using tags, descriptions and keywords</p>
{{ Form::open(array('id'=>'new_artefact_form', 'data-abide'=>'ajax', 'url'=>'artefact/new','method'=>'POST', 'files'=>true)) }}
      <input type="text" hidden="hidden" id="parent_id" name="parent_id" value=""/>

    <fieldset>
       <h3>General information</h3>

        {{-- INPUT: title --}}
        <div class="row"><div class="columns">
            <label>Title:
                <input type="text" required id="title" name="title" />
            </label>
            <small class="error">Please enter a title for the artefact.</small>
        </div></div>

        {{-- INPUT: old_tags --}}
        <div class="row">
            <div class="columns">
                <label>Select two tags below:</label>
                <div class="tag-select" id="old_tags">
                    <div class="tag-button"><label><input type="checkbox" data-abide-validator="tag_select" name="old_tags[]" value="tag1" /><span>tag1</span></label></div>
                    <div class="tag-button"><label><input type="checkbox" data-abide-validator="tag_select" name="old_tags[]" value="tag3" /><span>tag3</span></label></div>
                    <div class="tag-button"><label><input type="checkbox" data-abide-validator="tag_select" name="old_tags[]" value="tag2" /><span>tag2</span></label></div>
                    <small class="error" id="error_tags">Select exactly 2 existing tags.</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="columns">
                <label>Add one new tag:
                    <input type="text" required id="new_tag" name="new_tag" />
                </label>
                <small class="error">The new tag can not be empty and can not be the same as the selected tags.</small>
            </div>
        </div>

    </fieldset>

    <fieldset>
        {{-- INPUT: artefact --}}
        <div class="row"><div class="columns">
            @include('forms.elements.artefacteditor', ['id' => 'af', 'required' => 'required'])
        </div></div>

        <div class="row">
            <div class="columns">
                <label>Copyright, author or reference (optional):
                    <input type="text" id="copyright" name="copyright" />
                </label>
            </div>
        </div>

    </fieldset>

    <fieldset>
      <div class="row"><div class="columns">
       <h3>Description</h3>
       <label>Try to describe why you chose this artefact:</label>
        @include('forms.elements.texteditor', ['id' => 'description', 'required' => 'required', 'error_msg' => 'Please attempt to describe your addition.'])
        </div></div>
    </fieldset>

    <button type="submit">
        Add (some)thing
    </button>
{{ Form::close() }}
    </div>
</div>

<script src="/js/pdf.js"></script>
<script src="/js/quill.min.js" async onload="quill_init();"></script>
<script>

    function quill_init(){
        $(function(){
            var artefact = new Quill('#af .ql_editor', {
                modules: {
                    'toolbar': { container: '#af .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
            var description = new Quill('#description .ql_editor', {
                modules: {
                    'toolbar': { container: '#description .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
        });
    }
</script>
