/* help text */

/* example
 * Go to help mode
 * <a href="#" help-show="help_id">help</a>
 *
 * Add help to an item
 * <div data-help="help_id" data-help-id="text_id"></div>
*/

// messages
var text = {
    new_topic: "<p>Use this button to create a topic.</p><p>A topic is an imaginary thinking space. It is a cluster, a collection of online things that join into some form or shape. This can be a conversation, a discussion, a tension or a kind of unspeakable resonance.</p><p>After creating a topic, all users can add (some)thing to the topic. You can specify or modify an instruction by opening the topic and clicking 'add instruction'.</p>",
    search: "<p>Use these fields to search for contributions by (a combination of) author, tag or keyword.</p>",
    view_current_instruction: "<p>Use this button to see the latest instruction for the current topic.</p>",
    new_instruction: "<p>Use this button to add a new instruction. If existing, the previous instruction will be disabled and replaced.</p>",
    details: "<p>Use this button to get more detailed information about the artefact above.</p>",
    new_artefact: "<p>Use this button to add (some)thing to the artefact above. Your addition will appear next to it, like a tree getting a new branch.</p>",
    topic_title: "<p>This is a topic.<p><p>Click the title to expand the topic and see a visualisation of all the artefacts inside the topic.</p>",
    vis_menu: "<p>Use these four buttons to select how contributions or topics are being shown.</p><p>The underlying data of each of these visualisation is the same, but the way some elements are shown, hidden or connected allows you to explore and discover new relations and insights.</p>",
    new_log: "<p>Use this button to create a new log.</p><p>Logs are used to record a sequence of actions on the platform. The resulting log is a detailed list of button clicks and commands which other users can execute to reconstruct, understand and get inspired by a way of reading, navigating and exploring a topic.</p>",
    archive: "<p>The archive contains old topics.</p>",
    view_info: "<p>Use this button to show a topic's info and instructions.</p><p>A topic is an imaginary thinking space. It is a cluster, a collection of online things that join into some form or shape. This can be a conversation, a discussion, a tension or a kind of unspeakable resonance.</p>",
    topic_edit: "<p>Use this button to edit, archive or delete a topic.</p>",
    vis_gui: "<p>Use these buttons to control the visualisation.</p><p>You can zoom in and out by using the zoom buttons or by scrolling up and down on the visualisation. Click and drag  the tree to move it around or click the rotate button to turn the tree 90 degrees.</p>",
    vis_timeline: "<p>The timeline allows you to see the visualisation of a topic as it was at any moment in time.</p><p>Navigate through time by clicking anywhere on the timeline, or by dragging the slider left or right. You can also use the play and stop buttons to scroll through time automatically.</p>"
}

$(document).ready(function(){

    var help = false;

    $("[help-show]").click(function(e){
        //e.stopImmediatePropagation();
        show();
    });

    function show(h){
        $("[data-help]").addClass('help');

        // disable modals
        /*$("[data-reveal-id]").each(function(){
            var d = $(this).attr("data-reveal-id");
            $(this).removeAttr("data-reveal-id");
            $(this).attr("data-rev-id", d);
        });*/

        // create bg
        var div_bg = document.createElement("div");
        div_bg.setAttribute('class', 'help-bg');
        div_bg.innerHTML = "<a class=\"help-close help-close-x\" aria-label=\"Close\">×</a><div class=\"help-txt\">Click a highlighted item to get help.<br /><a class=\"help-close\" aria-label=\"Close\">(&larr; go back)</a></div>";
        $("body").append(div_bg);
        $(".help-close").on('click', hide);
        $(".help-bg").fadeIn();

        // create msg
        var div_msg = document.createElement("div");
        var close = document.createElement("a");
        var msg_content = document.createElement("div");
        $(close).addClass('help-msg-close');
        $(close).html('×');
        $(close).on('click', function(){
            $(div_msg).fadeOut();
        });
        $(div_msg).append(close);
        $(msg_content).addClass('msg-content');
        $(div_msg).append(msg_content);
        $(div_msg).addClass('help-msg');
        $(div_msg).css('position', 'absolute');
        $(div_msg).css('z-index', '9999');
        $(div_msg).css('display', 'none');
        $('body').append(div_msg);

        $("[data-help]").bind('click', showMessage);

    }

    function hide(){
        $('.help-msg').fadeOut(function(){
            $('.help-msg').remove();
        });

        // enable modals
        /*$("[data-rev-id]").each(function(){
            var d = $(this).attr("data-rev-id");
            $(this).removeAttr("data-rev-id");
            $(this).attr("data-reveal-id", d);
        });*/

        $(".help-bg").fadeOut(function(){
            $(this).remove();
            $("[data-help]").removeClass('help');
        });

        $("[data-help]").unbind('click', showMessage);

    }

    function showMessage(e){
        $('.help-msg').hide();
        $('.help-msg').removeClass('right');
        $('.help-msg').removeClass('top');
        $('.help-msg').removeClass('bottom');
        $('.help-msg').removeClass('left');

        console.log($(this).offset());
        console.log($(window).height()/2);

        if($(this).offset().top < $(window).height()/2){
            console.log('top');
            $('.help-msg').css('bottom', '');
            $('.help-msg').addClass('top');
            $('.help-msg').css('top', $(this).offset().top + $(this).height() + 10 + 'px');
        } else{
            console.log('bottom');
            $('.help-msg').css('top', '');
            $('.help-msg').addClass('bottom');
            $('.help-msg').css('bottom', $(window).height() - $(this).offset().top - 10 + 'px');
        }

        if($(this).offset().left > $(window).width() - 250 ) {
            $('.help-msg').addClass('right');
            $('.help-msg').css('left', $(this).offset().left - 250 + 'px');
        } else{
            $('.help-msg').addClass('left');
            $('.help-msg').css('left', $(this).offset().left + 'px');
        }
        $('.msg-content').html(text[$(this).data('help-id')]);
        $('.help-msg').fadeIn();

        e.stopImmediatePropagation();
    }

});
