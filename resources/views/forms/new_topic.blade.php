<h2>Start a new topic</h2>
<p>Initiate a topic using text, an image, a video or a document. This will be the first contribution to the topic.</p>
{{ Form::open(array('id'=>'new_topic_form', 'data-abide'=>'ajax', 'url'=>'topic/new','method'=>'POST', 'files'=>true)) }}
    <fieldset>
        <h3>General information</h3>
        <!-- INPUT: topic_title -->
        <div class="field_title">
            <label>Title:
                <input type="text" required name="topic_title"/>
            </label>
            <small class="error">Please enter a title for the topic.</small>
        </div>
        <!-- CHECKBOX: topic_new_tag -->
        <label>Tags (enter 3 below):</label>
        <div class="form-inline">
            <div class="field_tag">
                <label for="new-tag-1">Tag 1</label>
                <span class="field">
                    <input required data-abide-validator="tag_new" class="new-tag" id="new-tag-1" type="text" name="topic_new_tag[]"/>
                    <small class="error">3 different tags are required.</small>
                </span>
            </div>
            <div class="field_tag">
                <label for="new-tag-2">Tag 2</label>
                <span class="field">
                   <input required data-abide-validator="tag_new" class="new-tag" id="new-tag-2" type="text" name="topic_new_tag[]"/>
                    <small class="error">3 different tags are required.</small>
                </span>
            </div>
            <div class="field_tag">
                <label for="new-tag-3">Tag 3</label>
                <span class="field">
                    <input required data-abide-validator="tag_new" class="new-tag" id="new-tag-3" type="text" name="topic_new_tag[]"/>
                    <small class="error">3 different tags are required.</small>
                </span>
            </div>
        </div>
    </fieldset>
    <fieldset><!-- EXTRA INFO topic_copyright, topic_attachment -->
        <h3>Extra information (optional)</h3>
        <label for="attachment">
                <span class="filetype_label">You can attach an extra JPG, PNG, GIF or PDF file to your contribution:</span>
                    <input data-abide-validator="filesize" type="file" id="attachment" name="answer_attachment" class="inputfile"/>
                    <div>
                       <span class="file_reset">
                            remove
                        </span>
                        <span>
                            <span class="file_label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
                            Choose file</span>
                            <span class="file_filename"></span>
                        </span>
                    </div>
            </label>
        <small class="error">The attachment is too large (> 5MB).</small>
    </fieldset>
    <input type="submit" class="full purple" value="Add topic"/>
{{ Form::close() }}
