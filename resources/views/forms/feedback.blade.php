<h2>Feedback</h2>
<p>Remarks, problems or suggestions? Please fill in the form below.</p>
{{ Form::open(array('data-abide', 'id'=>'feedback_form', 'url'=>'/feedback','method'=>'POST', 'files'=>true)) }}
    <small class="mailstatus error full"></small>

    <div>
        <label for="fb_name">Name:
            <input type="text" id="fb_name" name="fb_name"/>
        </label>
    </div>

    <div>
        <label for="fb_mail">E-mail:
            <input type="email" id="fb_mail" name="fb_mail"/>
        </label>
        <small class="error">Please enter a valid e-mail address.</small>
    </div>

    <div>
       <label for="fb_msg">Message:
        <textarea required rows="5" id="fb_msg"></textarea>
        </label>
        <small class="error">Please describe your remark, problem or suggestion.</small>
    </div>

    <button type="submit">Submit</button>
{{ Form::close() }}
