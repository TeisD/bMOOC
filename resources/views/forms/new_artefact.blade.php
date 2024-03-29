<div class="row">
    <div class="columns large-10 large-offset-1">
<h2>Add (some)thing</h2>
<p>Add something to the topic. Your addition will be linked to the preceding additions, and to additions from different topics using tags, descriptions and keywords.</p>
{{ Form::open(array('id'=>'new_artefact_form', 'data-abide'=>'ajax', 'url'=>'artefact/new','method'=>'POST', 'files'=>true)) }}
    @if(isset($artefact))
        <input type="text" hidden="hidden" id="parent_id" name="parent_id"/>
        <input type="text" hidden="hidden" id="id" name="id" value="{{$artefact->topic->id}}"/>
    @elseif(isset($topic))
        <input type="text" hidden="hidden" id="id" name="id" value="{{$topic->id}}"/>
    @endif

      @if(isset($topic->activeInstruction) || isset($artefact->topic->activeInstruction))
       <fieldset>
        <h3 class="showhide">Active instruction</h3>
          <div class="row">
              <div class="columns">
                 <div class="instruction">
                    <h4>{{$topic->activeInstruction->title}}</h4>
                  @if($topic->activeInstruction->type_id == 28)
                      {!!$topic->activeInstruction->content!!}
                  @elseif($topic->activeInstruction->type_id == 29 || $topic->activeInstruction->type_id == 30)
                      <img src="/instruction/{{$topic->activeInstruction->id}}/medium" alt="{{$topic->activeInstruction->title}}"/>
                  @elseif($topic->activeInstruction->type_id == 31 || $topic->activeInstruction->type_id == 32)
                      <iframe src="{{$topic->activeInstruction->content}}"></iframe>
                  @elseif($topic->activeInstruction->type_id == 33)
                  <object data="/instruction/{{$topic->activeInstruction->id}}/original" type="application/pdf"><a href="/instruction/{{$topic->activeInstruction->id}}/original">Click to view PDF</a><br/><small>(Your browser does not support viewing of PDF\'s inside bMOOC)</small></object>
                  @endif
                  </div>
              </div>
          </div>
        </fieldset>
      @endif
    <fieldset>
       <h3>General information</h3>

        {{-- INPUT: title --}}
        <div class="row"><div class="columns">
            <label>Title:
                <input type="text" required id="title" name="title" />
            </label>
            <small class="error">Please enter a title for the artefact.</small>
        </div></div>

        @if(isset($first))
            {{-- INPUT: new tags --}}
            <div class="form-inline">
            <div class="row"><div class="columns large-6">
                <label for="new_tag-1">tag 1:</label>
                <div class="field">
                    <input type="text" required data-abide-validator="tag_new" id="new_tag-1" name="new_tags[]" />
                    <small class="error">3 unique tags are required.</small>
                </div>
            </div></div>
            <div class="row">
               <div class="columns large-6">
                <label for="new_tag-2">tag 2:</label>
                <div class="field">
                    <input type="text" required data-abide-validator="tag_new" id="new_tag-2" name="new_tags[]" />
                    <small class="error">3 unique tags are required.</small>
                </div>
                </div>
            </div>
            <div class="row">
               <div class="columns large-6">
                <label for="new_tag-3">tag 3:</label>
                <div class="field">
                    <input type="text" required data-abide-validator="tag_new" id="new_tag-3" name="new_tags[]" />
                    <small class="error">3 unique tags are required.</small>
                </div>
                </div>
            </div>
        </div>
        @else
            {{-- INPUT: old_tags --}}
            <div class="row">
                <div class="columns">
                    @include('forms.elements.tags')
                </div>
            </div>
            {{-- INPUT: old_tags --}}
            <div class="row">
                <div class="columns large-6">
                    <label>Add one new tag:
                        <input type="text" required data-abide-validator="tag_existing" id="new_tag" name="new_tag" />
                    </label>
                    <small class="error">The new tag can not be empty and cannot be the same as the selected tags.</small>
                </div>
            </div>
        @endif

    </fieldset>

    <fieldset>
        {{-- INPUT: artefact --}}
        <div class="row"><div class="columns">
            @include('forms.elements.artefacteditor', ['id' => 'artefact_af', 'required' => 'required'])
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
       <label>Describe what you see in the previous contribution:</label>
        @include('forms.elements.texteditor', ['id' => 'artefact_description', 'required' => 'required', 'error_msg' => 'Please attempt to describe what you see in the previous contribution.'])
        </div></div>
    </fieldset>

    <button type="submit">
        Add (some)thing
    </button>
{{ Form::close() }}
    </div>
</div>

<script src="/js/pdf.js"></script>
<script src="/js/quill.min.js" async onload="quill_artefact_init();"></script>
<script>

    $(function(){
        $('.showhide').on('click', function(){
            $(this).toggleClass('hidden');
            $('.instruction').slideToggle();
        });
    });

    function quill_artefact_init(){
        $(function(){
            var artefact = new Quill('#artefact_af .ql_editor', {
                modules: {
                    'toolbar': { container: '#artefact_af .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
            var description = new Quill('#artefact_description .ql_editor', {
                modules: {
                    'toolbar': { container: '#artefact_description .ql_toolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
        });
    }
</script>
