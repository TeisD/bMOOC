/************
* VALIDATIE *
************/

/**
 * Main validation (abide)
 */
$(document).foundation({
    abide : {
        timeout: 1000,
        patterns: {
            day_month_year: /(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](19|20)\d\d/
        },
        validators: {
            tag_new: function(el, required, parent){
                var tags = [];
                var valid = true;
                $('[data-abide-validator="tag_new"]').each(function(){
                    if($.inArray($(this).val(), tags) > -1){
                        valid = false;
                    }
                    tags.push($(this).val());
                });
                return valid;
            },
            tag_existing: function(el, required, parent){
                var tags = [];
                var valid = true;
                $('#answer_tags input[type=checkbox]:checked').each(function() {
                    if ($.inArray($(this).next().text(), tags) > -1) {
                        valid = false;
                    }
                    tags.push($(this).next().text());
                })
                $('[data-abide-validator="tag_existing"]').each(function(){
                    if($.inArray($(this).val(), tags) > -1){
                        valid = false;
                    }
                    tags.push($(this).val());
                });
                if(el.value == "" || el.value == null) valid = false;
                return valid;
            },
            tag_select: function(el, required, parent){
                var valid = true;
                if (parent.parent().find('input').length){
                    if (parent.parent().find('input:checked').length != 2) {
                        valid = false;
                        parent.parent().find('small.error').css('display', 'block');
                    } else {
                        parent.parent().find('small.error').css('display', 'none');
                    }
                }
                return valid;
            },
            filesize: function(el, required, parent){
                var valid = true;
                msg = '';
                if(!required) return valid;
                if(el.files.length > 0){
                    var f = el.files[0];
                    if (f.size > 5120000) {
                        valid = false;
                        $('small.error', parent).text("The file is too large (> 5MB)");
                    }
                } else {
                    valid = false;
                    $('small.error', parent).text("Please select a file to upload");
                }
                return valid;
            },
            quill: function(el, required, parent){
                var valid = true;
                if(!required) return valid;
                $(el).val(parent.find('.ql-editor').html());
                if(parent.find('.ql-editor').text().length == 0) valid = false;
                return valid;

            }
        }
    }
});

/**
 * Toon of verberg invoervelden van antwoordtypes
 * @param {event} e Event van de knop die werd geklikt
 */
function showAnswerType(e, el) {

    e.preventDefault();

    var parent = $(el).parents("fieldset");
    var el = $(el);

    // clear form and errors
    $('.filetype_error', parent).hide();
    parent.removeClass('error');
    $('div.error', parent).removeClass('error');

    if(el.hasClass('active')){
        return false;
    }
    $('.type_select', parent).removeClass('active');
    $('input, textarea', parent).prop('required', false);
    el.addClass('active');

    parent.find('.type_input').hide();

    switch(el.data('type')){
        case 'text':
            $('#text textarea', parent).prop('required', true);
            $('#text', parent).slideDown();
            break;
        case 'image':
            $('#upload .label', parent).html('Select an image to upload <small>(JPG, PNG or GIF, &lt;5MB)</small>');
            $('#upload input', parent).prop('required', true);
            parent.find('#upload').slideDown();
            break;
        case 'video':
            $('#url input', parent).prop('required', true);
            $('#url', parent).slideDown();
            break;
        case 'file':
            parent.find('#upload .label').html('Select a PDF to upload. <small>(&lt;5MB. If the file is too large you can use <a href="http://smallpdf.com/compress-pdf">this free tool</a> to resize your PDF)</small>');
            $('#upload input', parent).prop('required', true);
            $('#upload', parent).slideDown();
            break;
        default:
            break;
    }

    $('#filetype', parent).val(el.data('type'));

    return false;
}

/********
* FORMS *
********/

/* CLEAR FILE UPLOAD */
$(function(){
    $('button .clear').on("click", function(){
        var control = $("#control");
        control.replaceWith( control.val('').clone( true ) );
    });
});

;( function( $, window, document, undefined ){
	$( '.inputfile' ).each( function(){
		var $input	   = $( this ),
			$el  	   = $input.parent( 'label' ),
            $filename  = $el.find('.file_filename');

        $el.find('.file_reset').on('click', function (e){
            e.preventDefault();

            $input.wrap('<form></form>').closest('form').get(0).reset();
            $input.unwrap();
            $input.trigger('change');
        });

		$input.on( 'change', function( e ){
			var fileName = '';

			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else if( e.target.value )
				fileName = e.target.value.split( '\\' ).pop();

            $filename.html( fileName );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
	});
})( jQuery, window, document );


/* FEEDBACK */
$(function(){
    $('#feedback').submit(function(e){
        e.preventDefault();

        var name = $('#feedback #fb_name').val();
        var email = $('#feedback #fb_mail').val();
        var message = $('#feedback #fb_msg').val();
        var token = $('#feedback input[name="_token"]').val();

        $.ajax({
        type: "POST",
        url: '/feedback',
        data: {name:name, email:email, body:message, _token: token},
        success: function( msg ) {
            $('#feedback .mailstatus').addClass('success');
            $('#feedback .mailstatus').html(msg);
            $('#feedback .mailstatus').css('display', 'block');
            setTimeout(function() {
                $('#feedback .mailstatus').slideUp()
            }, 5000);
        },
        fail: function(msg){
            $('#feedback .mailstatus').html(msg);
            $('#feedback .mailstatus').css('display', 'block');
            setTimeout(function() {
                $('#feedback .mailstatus').slideUp()
            }, 5000);
        }
    });
    })
});

/* NEW topic / instruction / artefact */
$(function(){
    $('*[data-abide="ajax"]').on('valid.fndtn.abide', function(e) {
        var form = $(this);
        e.preventDefault();

        var button = $('*[type="submit"]', form);
        button.html('<img src="/img/loader_transparent.gif" /> Submitting...');
        button.prop('disabled', true);
        button.addClass('disabled');

        var input = $('#upload input', form);
        var parent = form.parents('[data-reveal]');

        if(input.length){

            input = input[0];

            var t_100 = new Thumbnail(input, 100);
            var t_1000 = new Thumbnail(input, 1000);

            $.when(t_100.generate(), t_1000.generate()).done(function(){
                if(t_100.hasData) {
                    $('<input>', {
                        type: 'hidden',
                        id: 'thumbnail_small',
                        name: 'thumbnail_small',
                        value: t_100.get()
                    }).appendTo(form);
                }
                if(t_1000.hasData) {
                    $('<input>', {
                        type: 'hidden',
                        id: 'thumbnail_large',
                        name: 'thumbnail_large',
                        value: t_1000.get()
                    }).appendTo(form);
                }
                formSubmit(form, parent);
            }).fail(function(data) {
                formSubmit(form, parent);
            });
        } else {
            formSubmit(form, parent);
        }
    });
});

function formSubmit(form, parent){
     var options = {
        success: function(data){
            var button = $('*[type="submit"]', form);
            button.html('Submit');
            button.prop('disabled', false);
            button.removeClass('disabled');
            if(data.refresh) location.reload(true);
            else window.location = data.url;
        },
        error: function(data){

            console.log(data);

            $(".json.error").remove();

            var button = $('*[type="submit"]', form);
            button.html('Submit');
            button.prop('disabled', false);
            button.removeClass('disabled');

            if(data.responseJSON){
                $.each(data.responseJSON, function(i, msg){
                    form.prepend('<small class="error json" style="display: block">'+msg+'</small>');
                });
            } else{
                form.prepend('<small class="error json" style="display: block">Oops, something went wrong while submitting the form. Please check if the entered information is correct and try again. If the error remains, please use the feedback form to contact the system administrator.</small>');
            }
        }
     };

    // bind form using 'ajaxForm'
    form.ajaxSubmit(options);
}

/*********
* RENDER *
*********/

/**
 * Show an artefact in the desired container
 * The container should have two divs, .loader & .artefact
 * @param div A div which contains two childeren, .loader & .artefact
 * @param data The artefact
 * @param data An optinal parameter describing the quality of the artefact
 */
function render(div, data, quality, url){
    var html;
    var loadImg = false;
    var expandable = false;
    if(url == undefined) url = 'artefact'
    if(quality == undefined) quality = 'medium'

    div.find('.artefact').hide();
    div.find('.loader').show();
    switch (data.type_id) {
        case 28:
            html = "<div class=\"textContainer\"><div class=\"text\"><h2>" + data.title + "</h2>" + data.content + "</div></div>";
            break;
        case 29:
        case 30:
            html = '&nbsp;<img src="/'+url+'/' + data.id + '/'+quality+'">';
            if(quality == 'original') expandable = true;
            loadImg = true;
            break;
        case 31:
            html = '<iframe  src="' + data.content + '?autoplay=0" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            break;
        case 32:
            html = '<iframe src="' + data.content + '" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            break;
        case 33:
        case 34:
            html = '<object data="/'+url+'/' + data.id + '/original" type="application/pdf"><a href="/'+url+'/' + data.id + '/original">Click to view PDF</a><br/><small>(Your browser does not support viewing of PDF\'s inside bMOOC)</small></object>';
            break;
        default:
            html = '<p>Oops, there was an error loading the artefact.<br />Please try reloading this page.</p>';
            break;
    }

    div.find('.artefact').html(html);

    if (loadImg) {
        div.imagesLoaded(function () {
            load();
        });
    } else {
        load();
    }

    function load(){
        div.stop(true, true)
        div.find('.loader').hide();
        div.find('.artefact').fadeIn();

        var img = $('.artefact img', div)[0]

        if(expandable && (img.naturalHeight > $('.artefact img', div).height() || img.naturalWidth > $('.artefact img', div).width())){
            div.find('#artefact').append('<button class="secondary square expand"><i class="fi-arrows-out"></i></button>');

            div.find('.expand').on('click', function(){
                div.find('.artefact').toggleClass('expanded');
                div.find('.expand i').toggleClass('fi-arrows-out');
                div.find('.expand i').toggleClass('fi-arrows-in');
            });
        }
    }
}

/**
 * Update the text in a lightbox
 * The elements to be updated should have a data-attr tag matching the artefacts properties
 * @param div The container div
 * @param artefact The artefact
 */
function update(div, artefact){
    div.find("[data-attr=title]").html(artefact.title);
    div.find("[data-attr=created_at]").html(artefact.created_at);
    div.find("[data-attr=author]").html('<a href="/search/'+artefact.author.id+'">'+artefact.author.name+'</a>');
    tags = div.find("[data-attr=tags]");
    tags.html('');
    for(var i = 0; i < artefact.tags.length; i++){
        tags.append('<li><a href="/search/all/'+artefact.tags[i].id+'">'+artefact.tags[i].tag+'</a></li>');
    }
}

/** Generate thumbnails using HTML5 Canvas & PDFJS. */
var Thumbnail = (function(){

    var BASE64_MARKER = ';base64,';

    /**
     * Create a thumbnail.
     * @param {dom element} el - The input element containing a image or pdf.
     * @param {number} size - The size of the thumbnails bounding box.
     */
    function Thumbnail(el, size){
        this.dfd = new $.Deferred();
        this.c = document.createElement("canvas");
        this.ctx = this.c.getContext("2d");
        this.el = el;
        this.size = size;
        this.hasData = false;
        this.file = null;
    }

    /**
     * Generate the thumbnail.
     * @return {promise} A Jquery promise.
     */
    Thumbnail.prototype.generate = function(){
        var support = browserSupport.call(this);
        if(support.isSupported){
            this.file = this.el.files[0];
            this.readFile();
        } else {
            this.dfd.reject(support.msg);
        }
        return this.dfd.promise();
    }

    /**
     * Get the thumbnail image.
     * @return {image} A base64 encoded png.
     */
    Thumbnail.prototype.get = function(){
        return this.c.toDataURL('image/png');
    }

    /**
     * Read the input file and call the appropriate render method
     */
    Thumbnail.prototype.readFile = function(){
        var fr = new FileReader();
        var pointer = this;
        fr.onload = function(e){
            if(pointer.file.type.match('image.*')){
                var img = new Image();
                img.onload = function(){
                    pointer.render(img);
                }
                img.onerror = function(e){
                    pointer.dfd.reject("Failed to read the image");
                }
                img.src = e.target.result;
            } else if(pointer.file.type.match('application/pdf') || pointer.file.type.match('application/x-pdf')){
                var pdfAsArray = convertDataURIToBinary(e.target.result);
                pointer.renderPDF(pdfAsArray);
            } else {
                pointer.dfd.reject("The uploaded file was not an image, nor a pdf");
            }
        }

        fr.onerror = function(e){
            pointer.dfd.reject("Failed to read the file");
        }

        fr.readAsDataURL(pointer.file);
    }

    /**
     * Render a thumbnail given an image.
     * @param {Image} img - The original image
     */
    Thumbnail.prototype.render = function(img){
        var ratio = img.height/img.width;
        if(ratio > 1){ // portrait
            if(img.height < this.size){ this.dfd.resolve(); return;}
            this.c.height = this.size;
            this.c.width = this.size/ratio;
        } else{ // landscape
            if(img.width < this.size){ this.dfd.resolve(); return;}
            this.c.width = this.size;
            this.c.height = this.size*ratio;
        }

        this.ctx.drawImage(img, 0, 0, this.c.width, this.c.height);
        this.hasData = true;
        this.dfd.resolve();
    }

    /**
     * Render a thumbnail given a pdf.
     * @param {Uint8Array} url - The original pdf, encoded as a Uint8Array
     */
    Thumbnail.prototype.renderPDF = function(url){
        var pointer = this;
        PDFJS.workerSrc = '/js/pdf.worker.js';

        PDFJS.getDocument(url).then(function getPdfHelloWorld(pdf) {
            pdf.getPage(1).then(function getPageHelloWorld(page) {
                var viewport = page.getViewport(1);
                var ratio = viewport.height/viewport.width;
                if(ratio > 1){ // portrait -> s = max height
                    var scale = pointer.size / viewport.height;
                    viewport = page.getViewport(scale);
                } else{ // landscape -> s = max width
                    var scale = pointer.size / viewport.width;
                    viewport = page.getViewport(scale);
                }

                pointer.c.height = viewport.height;
                pointer.c.width = viewport.width;

                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: pointer.ctx,
                    viewport: viewport
                };

                var renderTask = page.render(renderContext);

                renderTask.promise.then(function(){
                    pointer.hasData = true;
                    pointer.dfd.resolve();
                });
            });
        });
    }

    /**
     * Check if the browser supports the FileReader class and if input file is valid
     * @return {boolean} isSupported.
     * @return {string} msg.
     */
    function browserSupport(){
        var support = {
            isSupported: true,
            msg: 'Browser supports thumbnail generation.'
        }

        if (!window.File || !window.FileReader || !window.FileList || !window.Blob){
            support = {
                isSupported: false,
                msg: 'The File APIs are not fully supported in this browser.'
            }
        } else if (!this.el) {
            support = {
                isSupported: false,
                msg: 'Couldn\'t find the fileinput element.'
            }
        } else if (!this.el.files) {
            support = {
                isSupported: false,
                msg: 'This browser doesn\'t seem to support the \'files\' property of file inputs.'
            }
        } else if (!this.el.files[0]) {
            support = {
                isSupported: false,
                msg: 'No file selected.'
            }
        }
        return support;
    }

    /**
     * Convert pdf from base64 to Uint8Array.
     * @param {base64} dataURI - A base64 encoded pdf
     * @return {Uint8Array} array - A Uint8Array encoded pdf
     */
    function convertDataURIToBinary(dataURI) {
        var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
        var base64 = dataURI.substring(base64Index);
        var raw = window.atob(base64);
        var rawLength = raw.length;
        var array = new Uint8Array(new ArrayBuffer(rawLength));

        for(var i = 0; i < rawLength; i++) {
            array[i] = raw.charCodeAt(i);
        }
        return array;
    }

    return Thumbnail;

})();


/************************************************
* RENDER TREES (dependencies: d3js & 3dplus.js) *
************************************************/

var Vis = (function(){

    /** Static variables shared by all instances **/
    Vis.IMAGE_SIZE = 100;
    Vis.MARGIN = {
        top: Vis.IMAGE_SIZE/2,
        right: Vis.IMAGE_SIZE/2,
        bottom: Vis.IMAGE_SIZE/2,
        left: Vis.IMAGE_SIZE/2
    };
    Vis.TEXTBOUNDS = {
        width: Vis.IMAGE_SIZE,
        height: Vis.IMAGE_SIZE,
        resize: true
    };

    /**
     * Create a Vis.
     * @param {dom element} el - The container for the Vis svg element.
     * @param {JSON} data - A JSON-object containing the data to visualize.
        {
            "list" : [
                 {"id": id, "content": content, ...},
                 {"id": id, "content": content, ...},
                 ...
            ],
            "links" : [
                {"source": source_id, "target": target_id, "links": ["tag1", "tag2", 0, "4", true, "false"]},
                {"source": source_id, "target": target_id, "links": [...]},
                ...
            ],
            "tree" : [
                {"id": id, "content": content, children: [
                    { "id": id, "content": content, children: []},
                    { "id": id, "content": content, children: [
                        { "id": id, "content": content, children: [...]},
                        ...
                    ]},
                ]}
            ]
        }
     * @param {object} opt - An optional object defining the trees behavior
     */
    function Vis(el, data, url, opt){

        // Options array
        this.options = {
            interactive: true, // 0: none, 1:Allow dragging & zooming, 3:allow dragging nodes
            type: 'tree', // Type of the visualisation: tree or network
            mode: 'nodes', // Show nodes, text or all
            background: true, // give a background to text so the links appear behind
            fit: true, // scales the visualisation to fit the container upon render
            collide: true, // let the elements of a force layout overlap
            resize: true,
            rotate: false
        };

        if(typeof opt !== 'undefined'){
            if(typeof opt.interactive !== 'undefined') this.options.interactive = opt.interactive;
            if(typeof opt.type !== 'undefined') this.options.type = opt.type;
            if(typeof opt.mode !== 'undefined') this.options.mode = opt.mode;
            if(typeof opt.background !== 'undefined') this.options.background = opt.background;
            if(typeof opt.fit !== 'undefined') this.options.fit = opt.fit;
            if(typeof opt.collide !== 'undefined') this.options.collide = opt.collide;
            if(typeof opt.resize !== 'undefined') this.options.resize = opt.resize;
            if(typeof opt.rotate !== 'undefined') this.options.rotate = opt.rotate;
        }

        this.data = data;
        this.el = el;
        this.url = url;

        this.zoomListener = d3.behavior.zoom()
            .on("zoom", this.zoomed);
        this.hasZoom = false;

        this.svg = d3.select(this.el).append("svg")
                .attr("width", '100%')
                .attr("height", '100%')
                .attr("class", "vis");
        // add one g to capture events
        this.container = this.svg.append("g")
            .attr("class", "vis_container");
        // add one g to scale
        this.zoomContainer = this.container.append("g")
            .attr("class", "vis_zoom")
        // add another g to draw the visualisation
        this.g = this.zoomContainer.append("g");

        this.width = function(){
            return this.el.getBoundingClientRect().width;
        }
        this.height = function() {
            return this.el.getBoundingClientRect().height;
        }
    }

    /**
     *  Render a visualisation
     */
    Vis.prototype.render = function(type){
        // clear the current vis
        d3.select(this.el).select(".vis_container").remove();
        d3.select(this.el).selectAll(".vis-gui.zoom").remove();
        // add one g to capture events
        this.container = this.svg.insert("g",":first-child")
            .attr("class", "vis_container");
        // add one g to scale
        this.zoomContainer = this.container.append("g")
            .attr("class", "vis_zoom")
        // add another g to draw the visualisation
        this.g = this.zoomContainer.append("g")
            .attr("class", "vis_data");

        if(this.options.interactive >= 1){
            this.zoomContainer.insert("rect",":first-child")
                .attr('class', 'vis_zoom-capture')
                .style('visibility', 'hidden')
                .attr('x', this.g.node().getBBox().x - 25)
                .attr('y', this.g.node().getBBox().y - 25)
                .attr('width', this.g.node().getBBox().width + 50)
                .attr('height', this.g.node().getBBox().height + 50);
            this.container.call(this.zoomListener);
            // GUI
            var gui = d3.select(this.el).append('div')
                .attr('class', 'vis-gui zoom')
            var pointer = this;
            gui.append('button')
                .attr('class', 'secondary square rotate')
                .html('&#x21bb;&#xfe0e;')
                .on('click', function(){
                    pointer.options.rotate = !pointer.options.rotate;
                    pointer.draw();
                });
            gui.append('button')
                .attr('class', 'secondary square zoom-in')
                .html('<i class="fi-plus"></i>')
                .on('click', function(){ pointer.zoom(0.1) });
            gui.append('button')
                .attr('class', 'secondary square zoom-out')
                .html('<i class="fi-minus"></i>')
                .on('click', function(){ pointer.zoom(-0.1) });
        }
        var pointer = this;
        if(this.options.type == "tree") this.renderTree();
        if(this.options.type == "network") this.renderForce();
        if(this.options.resize) d3.select(window).on('resize', function(){
            pointer.fit();
        });
    }
    
    Vis.prototype.draw = function(){
        this.drawNodes();
        this.drawLinks();
        if(this.options.fit) this.fit();
    }

    /**
     *  Resize the tree to fit the container
     */
    Vis.prototype.fit = function(){

        width = this.width();
        height = this.height();

        if(width == 0 || height == 0) return;

        var t = [0,0],
            s = 1,
            w = this.g.node().getBBox().width + Vis.MARGIN.left*2,
            h = this.g.node().getBBox().height + Vis.MARGIN.top*2;

        if(w > width) s = width/w;
        if(h > height && height/h < s) s = height/h;

        //t_w = width/2 - (w/2)*s;
        t_w = -this.g.node().getBBox().x*s + (width-w*s)/2 + (Vis.MARGIN.left*s)
        t_h = -this.g.node().getBBox().y*s + (height-h*s)/2 + + (Vis.MARGIN.top*s)

        this.zoomListener
            .scale(s)
            .translate([t_w, t_h])
            .scaleExtent([s, 1]);

        // for some mysterious reason, updating the zoomListener only works when called twice
        this.zoomListener
            .scale(s)
            .translate([t_w, t_h])
            .scaleExtent([s, 1]);

        this.svg.transition()
            .duration(125)
            .call(this.zoomListener.event);

        if(s != 1){
            this.hasZoom = true;
        }
    }

    /**
     * scale+move when window is resized
     */
    Vis.prototype.resize = function(){

        width = this.width();
        height = this.height();

        var t = [0,0],
            s = this.zoomListener.scale(),
            w = this.g.node().getBBox().width,
            h = this.g.node().getBBox().height;

        t_w = width/2 - (w/2)*s;
        t_h = -this.g.node().getBBox().y*s + (height-h*s)/2

        this.zoomListener
            .scale(s)
            .translate([t_w, t_h])
            .event(d3.select(this.el));

        if(s != 1){
            this.hasZoom = true;
        }
    }

    /**
     *  Update zoom container to fit dimensions of rendered tree
     */
    Vis.prototype.updateZoom = function(){
        if(this.options.interactive >= 1){
            this.zoomContainer.select('.vis_zoom-capture')
                .attr('x', this.g.node().getBBox().x - 25)
                .attr('y', this.g.node().getBBox().y - 25)
                .attr('width', this.g.node().getBBox().width + 50)
                .attr('height', this.g.node().getBBox().height + 50);
        }
    }

    /**
     *  Zoom programatically
     */
    Vis.prototype.zoom = function(z){
        this.zoomListener
            .scale(this.zoomListener.scale() + z)
            .event(d3.select(this.el));
    }

    /**
     * Zoom and pan
     * some interesting hints here: http://stackoverflow.com/questions/17405638/d3-js-zooming-and-panning-a-collapsible-Vis-diagram
     * It's important that this.zoomListener has been updated in the resize function
     */
    Vis.prototype.zoomed = function(){
        // this should be this.container g element
        d3.select(this).select('.vis_zoom').attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
        d3.select(window).on("mouseup.zoom", function(){
            d3.select(window).on("mousemove.zoom", null).on("mouseup.zoom", null);
        });
    }

    /**
     * Generate and show the tree.
     */
    Vis.prototype.renderTree = function(){
        console.log("render: tree");
        
        if(this.data.tree == null) throw("Vis error: no data provided to render.");

        treelayout = d3.layout.tree()
            .nodeSize([Vis.IMAGE_SIZE, Vis.IMAGE_SIZE]);

        // Compute the new Vis layout.
        this.nodes = treelayout.nodes(this.data.tree);//.reverse()
        this.links = treelayout.links(this.nodes);

        // horizontal spacing of the nodes (depth of the node * x)
        this.nodes.forEach(function(d) { d.y = d.depth * (Vis.IMAGE_SIZE + Vis.IMAGE_SIZE/10) });

        this.draw();
    }

    /**
     * Generate and show the force layout.
     * data.list: een array met alle nodes
     * data.links: een associatieve array met links (source, target, (text)), geassocieerd met het id van de nodes
     */
    Vis.prototype.renderForce = function(){
        console.log("render: force");

        if(this.data.list == null) throw("Vis error: no data provided to render.");

        var nodes = this.data.list;
        var links = this.data.links;
        var edges = [];

        var pointer = this;

        var force = d3.layout.force()
            .size([this.width(), this.height()])
            .gravity(0)
            .charge(0)
            .linkStrength(0);

        // gebruik de index (gekoppeld aan de thread) in de array ipv id voor Force layout
        links.forEach(function(e) {
            var sourceNode = nodes.indexOf(nodes.filter(function(n) { return n.id === e.source; })[0]);

            var targetNode = nodes.indexOf(nodes.filter(function(n) { return n.id === e.target; })[0]);

            if(sourceNode > -1 && targetNode > -1) edges.push({source: sourceNode, target: targetNode, value: e.links});

        });

        // add a random start point in some corner
        nodes.forEach(function(d,i){
            d.px = undefined;
            d.py = undefined;
            d.x = i * (pointer.width() / nodes.length);
            d.y = Math.random() * pointer.height();
            d.width = 500; // for collision detection
            d.height = 50;
        });
                
        this.nodes = nodes;
        this.links = edges;

        force.nodes(this.nodes)
            .links(this.links)
            .on("start", start)
            .on("end", end)
            //.on("tick", tick)
            .start();

        if(this.options.interactive == 2) node.call(force.drag);
        
        function tick(){
            pointer.draw();
        }

        function start(){
            pointer.g.append("g").selectAll(".linktext").remove();
            var ticksPerRender = 10;
            requestAnimationFrame(function render() {

                for (var i = 0; i < ticksPerRender; i++) force.tick();

                if(!pointer.options.collide){
                    var q = d3.geom.quadtree(pointer.nodes),
                    i = 0,
                    n = pointer.nodes.length;
                    while (++i < n) q.visit(collide(pointer.nodes[i]));
                }
                
                //pointer.draw();

                if (force.alpha() > 0) requestAnimationFrame(render);
            });
        }

        function collide(node) {
            var spacing = 10
            return function(quad, x1, y1, x2, y2) {
                var updated = false;
                if (quad.point && (quad.point !== node)) {

                    var x = node.x - quad.point.x,
                        y = node.y - quad.point.y,
                        xSpacing = (quad.point.width + node.width) / 2 + spacing,
                        ySpacing = (quad.point.height + node.height) / 2 + spacing,
                        absX = Math.abs(x),
                        absY = Math.abs(y),
                        l,
                        lx,
                        ly;

                    if (absX < xSpacing && absY < ySpacing) {
                        l = Math.sqrt(x * x + y * y);

                        lx = (absX - xSpacing) / l;
                        ly = (absY - ySpacing) / l;

                        // the one that's barely within the bounds probably triggered the collision
                        if (Math.abs(lx) > Math.abs(ly)) {
                            lx = 0;
                        } else {
                            ly = 0;
                        }

                        node.x -= x *= lx;
                        node.y -= y *= ly;
                        quad.point.x += x;
                        quad.point.y += y;

                        updated = true;
                    }
                }
                return updated;
            };
        }

        var ended = false;

        function end(){
            if(!ended){
                ended = true;
                var linktext = pointer.g.insert("g", ":first-child").selectAll(".linktext")
                    .data(pointer.links)
                    .enter()
                    .append('g')
                    .attr("class", "linktext")
                    /*.append("a")
                    .attr("xlink:href", function(d) {
                        console.log(d);
                        return "/search/all/"+d.value.id;
                    })*/
                    .append('text')
                    .attr("y", "-20")
                    .attr("text-anchor", "middle")
                    .append("textPath")
                    .attr("startOffset", "50%")
                    .attr("xlink:href",function(d,i) { return "#linkId_" + i;})
                    .text(function(d) {
                        var tags = [];
                        for(var i = 0; i < d.value.length; i++) tags.push(d.value[i].tag);
                        return tags.join(", ");
                    });
                pointer.draw();
            }
        }
    }

    /**
     * Draw the nodes. Reused by the render functions
     * http://stackoverflow.com/questions/12992351/how-to-update-elements-of-d3-force-layout-when-the-underlying-data-changes
     */
    Vis.prototype.drawNodes = function(){

        var pointer = this;
                        
        // Declare the nodes.
        var node = this.g.selectAll(".node")
            .data(this.nodes);

        // Enter the nodes.
        var nodeEnter = node.enter().append("g")
            .attr("class", "node")
            .attr("id", function(d){ return d.id })

        if(this.options.rotate){
            node.attr("transform", function(d) {
                return "translate(" + parseInt(d.y - Vis.IMAGE_SIZE/4) + "," + d.x + ")";
            });
        } else {
            node.attr("transform", function(d) {
                return "translate(" + parseInt(d.x - Vis.IMAGE_SIZE/2) + "," + d.y + ")";
            });
        }

        if(this.options.mode == 'all'){
            //hidden
            nodeEnter.filter(function(d) { return d.hidden; })
                .append("a")
                .attr("xlink:href", function(d) {
                    return "/"+pointer.url+"/"+d.id;
                })
                .append("circle")
                .attr("cx", 5)
                .attr("cy", 0)
                .attr("r", 5);

            //img
            nodeEnter.filter(function(d) { return d.type_id > 28; })
                .filter(function(d) { return !d.hidden })
                .append("a")
                .attr("xlink:href", function(d) {
                    return "/"+pointer.url+"/"+d.id;
                })
                .append("image")
                .attr("xlink:href", function(d) {
                    return "/artefact/" + d.id + "/thumbnail/"
                })
                .attr('y', -Vis.IMAGE_SIZE/2)
                .attr('width', Vis.IMAGE_SIZE)
                .attr('height', Vis.IMAGE_SIZE);

            //text
            var bg = nodeEnter.filter(function(d) { return d.type_id == 28 })
                .filter(function(d) { return !d.hidden })
                .append("rect")
                .attr('width', Vis.IMAGE_SIZE)
                .attr('height', Vis.IMAGE_SIZE)
                .attr('y', -Vis.IMAGE_SIZE/2);

            if(this.options.background) bg.attr("class", "filled");
            
            nodeEnter.filter(function(d) { return d.type_id == 28 })
                .filter(function(d) { return !d.hidden })
                .append("a")
                .attr("xlink:href", function(d) {
                    return "/"+pointer.url+"/"+d.id;
                })
                .append("text")
                .attr('y', -Vis.IMAGE_SIZE/2)
                .text(function(d) { return splitString(d.title); })
                .each(function(d){
                    d3plus.textwrap()
                        .config(Vis.TEXTBOUNDS)
                        .valign('middle')
                        .align('center')
                        .container(d3.select(this))
                        .draw();
                });

        } else if(this.options.mode == 'nodes') {
            nodeEnter.append("a")
                .attr("xlink:href", function(d) {
                    return "/"+pointer.url+"/"+d.id;
                })
                .append("circle")
                .attr("cx", 0)
                .attr("cy", 0)
                .attr("r", 5)
                .append("title")
                .text(function(d) { return d.title; });
        } else if(this.options.mode == 'text') {
            var a = nodeEnter.append("g")
                .append("a")
                .attr("xlink:href", function(d) {
                    return "/"+pointer.url+"/"+d.id;
                });

            if(this.options.background){
                a.append("text")
                    .attr("class", "title stroke")
                    .text(function(d){
                        return d.title;
                    })
            }

            var txt = a.append("text")
                .attr("class", function(d){
                    var c = "title";
                    if(!d.active) c += " archived";
                    return c;
                })
                .text(function(d){
                    return d.title;
                });

            txt.append("tspan")
                .attr("class", "small")
                .text(function(d){
                    var c = ''
                    if(!d.active) c+= " (inactive)";
                    if(d.archived) c+= " (archived)";
                    return c;
                });
        }

        this.updateZoom();
    }
    
    Vis.prototype.drawLinks = function(){
        // Declare the links
        var link = this.g.selectAll("path.link")
            .data(this.links);

        // .data(this.links, function(d) { console.log(d.source.id); return d.source.id; })

        if(this.options.rotate){
            var diagonal = d3.svg.diagonal()
                .projection(function(d) { return [d.y, d.x]; });
        } else {
            var diagonal = d3.svg.diagonal()
                .projection(function(d) { return [d.x, d.y]; });
        }

        link.enter().insert("path", "g")
            .attr("class", "link")
            .attr("id",function(d,i) {
                return "linkId_" + i;
            });
        
        link.attr("d", diagonal);
    }

    return Vis;

})();

/***********
* TIMELINE *
***********/

var Timeline = (function(){

    /**
     * Create a Timeline.
     * @param {Vis} vis - The visualisation to be associated with the timeline. Requires  data.list to be set.
     */
    function Timeline(vis){

        var pointer = this;

        this.vis = vis;

        /* public vars & functions */
        this.formatDate = d3.time.format("%d %b %Y");

        this.max = d3.max(this.vis.data.list, function(d){
                  var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                  return date;
              });

        this.min = d3.min(this.vis.data.list, function(d){
                  var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                  return date;
              });

        this.startingValue = this.max;

        this.timeScale = d3.time.scale()
          .domain([this.min, this.max])
          .range([100, this.vis.width() - 100])
          .clamp(true);

        this.brush = d3.svg.brush()
            .x(this.timeScale)
            .extent([this.max, this.max])
            .on("brush", function(){
                pointer.brushed(this);
            });

        // define the axis
        this.axis = d3.svg.axis()
            .scale(this.timeScale)
            .orient("bottom")
            .tickFormat(function(d) {
                return pointer.formatDate(d);
            })
            .tickSize(0)
            .tickPadding(12)
            .tickValues([this.timeScale.domain()[0], this.timeScale.domain()[1]])
    }

    Timeline.prototype.resize = function(){
        this.stop();

        this.timeScale.range([100, this.vis.width() - 100]);
        d3.select('.x.axis')
            .attr("transform", "translate(0," + (this.vis.height() - Vis.MARGIN.top) + ")")
            .call(this.axis);

        d3.select('.slider')
            .attr("transform", "translate(0, " + (this.vis.height() - Vis.MARGIN.top) + ")");
        var value = this.brush.extent()[0];
        d3.select(this.vis.el).select(".handle").attr("transform", "translate(" + this.timeScale(value) + ",0)");

    }

    Timeline.prototype.brushed = function(e){
        var pointer = this;

        if(this.brush.extent()[0] < this.min) this.brush.extent([this.min, this.min]);
        if(this.brush.extent()[0] > this.max) this.brush.extent([this.max, this.max]);

        var value = this.brush.extent()[0];

        if(e){
            // put the brush to a new value
            if (d3.event.sourceEvent) { // not a programmatic event
                value = this.timeScale.invert(d3.mouse(e)[0]);
                this.brush.extent([value, value]);
            }
        }

        d3.select(this.vis.el).select(".handle").attr("transform", "translate(" + this.timeScale(value) + ",0)");
        d3.select(this.vis.el).select(".handle").select('text').text(this.formatDate(value));

        // show all the nodes
        this.vis.g.selectAll("g.node").attr("display", "block");
        this.vis.g.selectAll(".link").attr("display", "block");

        // hide the newest nodes
        this.vis.g.selectAll("g.node")
            .filter(function(d) {
                var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                return date > pointer.brush.extent()[0];
            }).attr("display", "none");
        this.vis.g.selectAll(".link")
            .filter(function(d) {
                var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.target.created_at);
                return date > pointer.brush.extent()[0];
            })//.attr("opacity", "0.2");
            .attr("display", "none");
        this.vis.g.selectAll(".linktext")
            .filter(function(d) {
                var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.target.created_at);
                return date > pointer.brush.extent()[0];
            })//.attr("opacity", "0.2");
            .attr("display", "none");

        if(this.vis.options.fit) this.vis.fit();
    }

    /**
     *  Show the timeline
     */
    Timeline.prototype.show = function(){

        var pointer = this;

        // AXIS

        // select on parent node, we don't want to scale the timeline
        this.vis.svg.append("g")
            .attr("class", "x axis")
            // put in middle of screen
            .attr("transform", "translate(0," + (this.vis.height() - Vis.MARGIN.top) + ")")
            // inroduce axis
            .call(this.axis)
            .select(".domain");

        // SLIDER

        // add slider handle on parentNode, we don't want to scale it
        var slider = this.vis.svg.append("g")
          .attr("class", "slider")
            .attr("transform", "translate(0, " + (this.vis.height() - Vis.MARGIN.top) + ")")
          .call(this.brush);

        slider.selectAll(".extent,.resize")
          .remove();

        // background for slider to make selection area bigger
        slider.select(".background")
          .attr("height", 25)
            .attr("transform", "translate(0," + -12 + ")")

        // SLIDER > HANDLE
        var handle = slider.append("g")
          .attr("class", "handle")

        handle.append("circle")
          .attr("r", 5)

        handle.append('text')
          .text(this.startingValue)
            .attr("class", "lbl")
            .attr("transform", "translate(" + 0 + " ," + -20 + ")");

        slider
          .call(this.brush.event);
        
        // BUTTONS
            var gui = d3.select(vis.el).append('div')
                .attr('class', 'vis-gui timeline')
            var pointer = this;
            gui.append('button')
                .attr('class', 'secondary square rewind inline')
                .html('<i class="fi-rewind"></i>')
                .on('click', function(){ pointer.rewind() });
            gui.append('button')
                .attr('class', 'secondary square stop inline')
                .html('<i class="fi-stop"></i>')
                .on('click', function(){ pointer.stop() });
            gui.append('button')
                .attr('class', 'secondary square forward inline')
                .html('<i class="fi-fast-forward"></i>')
                .on('click', function(){ pointer.forward() });

        if(this.vis.options.resize){
            d3.select(window).on('resize', function(){
                pointer.resize();
                pointer.vis.fit();
            });
        }
    }

    /**
     *  Put the timeline on a given date
     */
    Timeline.prototype.setDate = function(date){
        this.brush.extent([date, date]);
        d3.select('.slider').call(this.brush.event);
    }

    /**
     *  Put a last visit mark on the timeline at the given date
     */
    Timeline.prototype.mark = function(date){
        lastVisit = d3.select(".slider").append("g")
            .attr("class", "mark")
            .attr("transform", "translate(" + this.timeScale(date) + ",0)")
        lastVisit.append("circle")
            .attr("r", 5)
            .attr("class", "marker");
    }

    /**
     *  Hide the timeline
     */
    Timeline.prototype.hide = function(){
    }

    /**
     *  Play forward
     */
    Timeline.prototype.forward = function(){
        var pointer = this;

        d3.select(d3.select('.slider').node()).transition()
            .ease(d3.ease("linear"))
            .duration(function(){
                return (5000 + 200 * d3.select(this.parentNode).selectAll("g.node").size()) * Math.abs((pointer.max - pointer.brush.extent()[0]) / (pointer.max - pointer.min));
            })
            .call(this.brush.extent([this.max, this.max]))
            .call(this.brush.event);
    }

    /**
     *  Play backwards
     */
    Timeline.prototype.rewind = function(){
        var pointer = this;

        d3.select(d3.select('.slider').node()).transition()
            .ease(d3.ease("linear"))
            .duration(function(){
                return (5000 + 200 * d3.select(this.parentNode).selectAll("g.node").size()) * Math.abs((pointer.brush.extent()[0] - pointer.min) / (pointer.max - pointer.min));
            })
            .call(this.brush.extent([this.min, this.min]))
            .call(this.brush.event);
    }

    Timeline.prototype.stop = function(){
        d3.select(d3.select('.slider').node()).transition();
    }

    Timeline.prototype.update = function(){
        d3.select(d3.select('.slider').node()).call(this.brush.event);
    }

    /**
     *  Set to start
     */
    Timeline.prototype.toStart = function(){
    }

    /**
     *  Set to end
     */
    Timeline.prototype.toEnd = function(){
    }

    return Timeline;

})();


/****************
* GENERATE MENU *
****************/

/** Generate menu to select visualisation type. Requires JQuery */
var Menu = (function(){

    /**
     * Create a Menu.
     * @param {id} btn - The container of the menu buttons.
     * @param {id} svg - The container of the svg element for interactive visulisation
     * @param {id} html - The container of the html elementen for list visualisation
     * @param {obj} opt - An optional object defining the menu's behavior
     */
    function Menu(menu, svg, html, vis, opt){
        this.menu = $("#"+menu);
        this.svg = $("#"+svg);
        this.html = $("#"+html);
        this.vis = vis;
        this.firstClick = true;

        // Options array
        this.options = {
            enabled: ['list'], // an array of modes to be enabled
            default: 'list' // the default view if no hash is set
        };
        if(typeof opt !== 'undefined'){
            if(typeof opt.enabled !== 'undefined') this.options.enabled = opt.enabled;
            if(typeof opt.default !== 'undefined') this.options.default = opt.default;
        }

        this.init();
    }

    /**
     * Binds events to the menu and hides unused buttons
     */
    Menu.prototype.init = function(){
        var pointer = this;

        /* bind events */
        $('.button', this.menu).each(function(){
            if($.inArray($(this).data('vis'), pointer.options.enabled) >= 0){
                $(this).on('click', function(){
                    hash = window.location.hash.substring(1);
                    $('.button', pointer.menu).removeClass('active');
                    $(this).addClass('active');
                    if(!pointer.firstClick){
                        $('.dropdown').hide();
                    }
                    pointer.firstClick = false;
                    
                    if(typeof($(this).attr('data-svg')) !== 'undefined'){
                        pointer.html.hide();
                        pointer.svg.show();
                        if($(this).attr('data-vis') == 'tree') pointer.vis.options.rotate = true;
                        else pointer.vis.options.rotate = false;
                        pointer.vis.options.type = $(this).attr('data-vis');
                        pointer.vis.render();
                    } else {
                        pointer.svg.hide();
                        pointer.html.show();
                        if($(this).attr('data-vis') == 'list'){
                            $('[vis-grid]').hide();
                            $('[vis-list]').show();
                            $('.list', pointer.html).removeClass('grid');
                            $('.list', pointer.html).addClass('block');
                        }
                        if($(this).attr('data-vis') == 'grid'){
                            $('[vis-list]').hide();
                            $('[vis-grid]').show();
                            $('.list', pointer.html).removeClass('block');
                            $('.list', pointer.html).addClass('grid');
                        }
                    }
                });
            } else{
                $(this).addClass('disabled');
                $(this).on('click', function(e){
                    e.preventDefault();
                });
            }
        });

        /* bind browser history buttons */
        window.onpopstate = function(event){
            pointer.update();
        };

        this.update();
    }

    Menu.prototype.update = function(){
        /* show hash or default */
        if(window.location.hash && ($.inArray(window.location.hash.substring(1), this.options.enabled) >= 0)){
            $('[data-vis='+window.location.hash.substring(1)+']', this.menu).click();
        } else {
            $('[data-vis='+this.options.default+']', this.menu).click();
        }
    }

    return Menu;

})();


/*******************
* HELPER FUNCTIONS *
*******************/

function parseDate(d) {
    var date = d.substring(0, d.indexOf(" "));
    var time = d.substring(d.indexOf(" ") + 1);
    var year = date.substring(0, 4);
    var month = date.substring(5, 7);
    var day = date.substring(8, 10);

    return(day + "/" + month + "/" + year + " " + time.substring(0, time.length - 3));
}

function splitString(str) {
    return str.replace(/(\w{12})(?=.)/g, '$1 ');
    //return str.replace(/[^A-Za-z0-9]/, ' ');
    //return str.replace(/\W+/g, " ")
}
