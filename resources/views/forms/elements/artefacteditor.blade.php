<fieldset id="{{$id}}">
    <h3>Add text, an image, a video or a pdf:</h3>
       {{-- BUTTONS --}}
        <div class="row margin-bottom">
           <div class="small-6 large-3 columns text-center">
               <button class="tertiary type_select" data-type="text" onclick="showAnswerType(event, this)">
                   <i class="fi-align-left"></i>
                   text
               </button>
           </div>
           <div class="small-6 large-3 columns text-center">
               <button class="tertiary type_select" data-type="image" onclick="showAnswerType(event, this)">
                   <i class="fi-camera"></i>
                   image<br /><small>(jpg, png, gif)</small>
               </button>
           </div>
            <div class="small-6 large-3 columns text-center">
               <button class="tertiary type_select" data-type="video" onclick="showAnswerType(event, this)">
                    <i class="fi-video"></i>
                      video<br /><small>(youtube, vimeo)</small>
              </button>
           </div>
            <div class="small-6 large-3 columns text-center end">
               <button class="tertiary type_select" data-type="file" onclick="showAnswerType(event, this)">
                   <i class="fi-page-pdf"></i>
                   document<br /><small>(pdf)</small>
               </button>
           </div>
        </div>

        {{-- INPUT: TEXT --}}
        <div class="row type_input" style="display: none" id="text">
           <div class="columns">
                @include('forms.elements.texteditor', ['id' => 'af_text', 'required' => 'required', 'error_msg' => 'Please enter some text.'])
            </div>
        </div>

        {{-- INPUT: FILE --}}
        <div class="row type_input" style="display: none;" id="upload">
            <div class="columns">
                @include('forms.elements.fileupload', ['id' => 'af_upload', 'required' => 'required'])
            </div>
        </div>

        {{-- INPUT: URL --}}

        <div class="row type_input" style="display: none"; id="url">
            <div class="columns">
                <label>Upload or find a video on YouTube or Vimeo and paste the link to the video here:
                    <input type="text" required id="af_url" name="af_url"/>
                </label>
                <small class="error">Please enter a link to a video on youtube or vimeo.</small>
            </div>
        </div>

        {{-- HIDDEN FILETYPE FIELD + ERROR MSG --}}
        <div class="row">
            <div class="columns">
                <input type="hidden" name="filetype" required id="filetype" />
                <small class="error filetype_error">Please choose one of the file types.</small>
            </div>
        </div>
</fieldset>
