<div id="{{$id}}" class="ql">
    <div class="ql_toolbar" class="toolbar ql-toolbar ql-snow">
        <span class="ql-format-group">
            <select title="Size" class="ql-size">
                <option value="0.8rem">Small</option>
                <option value="1rem" selected="selected">Normal</option>
                <option value="1.3rem">Large</option>
            </select>
        </span>
        <span class="ql-format-group">
            <span title="Bold" class="ql-format-button ql-bold"></span>
            <span class="ql-format-separator"></span>
            <span title="Italic" class="ql-format-button ql-italic"></span>
            <span class="ql-format-separator"></span>
            <span title="Underline" class="ql-format-button ql-underline"></span>
            <span class="ql-format-separator"></span>
            <span title="Strikethrough" class="ql-format-button ql-strike"></span>
        </span>
        <span class="ql-format-group">
            <span title="List" class="ql-format-button ql-list"></span>
            <span class="ql-format-separator"></span>
            <span title="Bullet" class="ql-format-button ql-bullet"></span>
            <span class="ql-format-separator"></span>
            <select title="Text Alignment" class="ql-align">
                <option value="left" label="Left" selected=""></option>
                <option value="center" label="Center"></option>
                <option value="right" label="Right"></option>
                <option value="justify" label="Justify"></option>
            </select>
        </span>
        <span class="ql-format-group">
            <span title="Link" class="ql-format-button ql-link"></span>
        </span>
    </div>

    <div class="ql_editor"></div>

    <textarea name="{{$id}}_raw" id="{{$id}}_raw" @if(isset($required)) required data-abide-validator="quill" @endif style="display:none"></textarea>

    @if(isset($required))
    <small class="error">
    @if(isset($error_msg)) {{$error_msg}}
    @else This field is required.
    @endif
    </small>
    @endif

</div>
