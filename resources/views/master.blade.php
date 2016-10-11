<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') - bMOOC - LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="/img/favicon.ico">
    {{-- NON BLOCKING STYLESHEETS --}}
    <link rel="preload" href="/css/foundation-icons.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="//cdn.quilljs.com/0.20.1/quill.snow.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="/css/foundation-datepicker.min.css" as="style" onload="this.rel='stylesheet'">
    <script src="/js/loadCSS.js"></script>
    <script src="/js/cssrelpreload.js"></script>

    {{-- STYLESHEETS --}}
    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" href="/css/foundation.min.css">
    <link rel="stylesheet" href="/css/app.css?v=@version">

    {{-- SCRIPTS --}}
    <script src="/js/vendor/modernizr.js"></script>
    <script src="/js/vendor/jquery.min.js"></script>
  </head>
	<body id="wrapper">
        {{-- JS: Google Analytics --}}
	    <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-71362622-1', 'auto');
          ga('send', 'pageview');
        </script>

        <div data-alert class="alert-box modernizr-alert logging-gui">
            <div class="toggle options">
                <ul class="inline slash">
                <li>
                    <a href="javascript:;" class="logging_comment">add comment</a>
                </li>
                <li>
                    <a href="javascript:;" class="logging_stop">stop logging</a>
                </li>
            </ul>
            </div>
            <div class="toggle saving" style="display: none">
                <img src="/img/loader_overlay_big.gif" style="height: 1rem; width: auto;" alt="loading..."> saving...
            </div>
        </div>

        <div data-alert class="alert-box alert modernizr-alert js-alert">
            <strong>JavaScript appears to be disabled in your browser.</strong><br />
            For full functionality of this site, it is necessary to enable JavaScript. Here are <a href="http://enable-javascript.com" class="emphasis">instructions how to enable Javascript</a>.
          <a href="#" class="close">&times;</a>
        </div>

        <div data-alert class="alert-box warning modernizr-alert browser-alert">
          <strong>You seem to be using an older browser.</strong><br />
            Some of bMOOC's functionality will not work as intended. Please <a class="emphasis" href="https://browser-update.org/update.html">update your browser</a>.
          <a href="#" class="close">&times;</a>
        </div>

        <header>
            <div class="row space text-right">
			    <div class="small-12 columns">
                    <nav class="main">
                        <ul class="inline slash">
                           <li>
                                <a href="javascript:;" data-log="1" help-show>help</a>
                            </li>
                            <li>
                               <a href="/about" data-reveal-id="about" data-log="2" data-reveal-ajax="true">about</a>
                            </li>
                            <li>
                               <a href="javascript:;" data-log="3" data-reveal-id="feedback">feedback</a>
                            </li>
                            <li>
                                <a href="/logout" data-log="4">Sign out {{$user->name}}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row space">
				<div class="large-5 columns">
					<h1 class="inline"><a href="/" data-log="5">bMOOC</a></h1>
                    <span id="vis-menu">
                        <a class="button tertiary inline" data-log="6" data-help data-help-id="vis_menu" data-vis="list" href="#list"><img src="/img/vis_list_white.png" />list</a>
                        <a data-help data-help-id="vis_menu" data-log="7"  class="button tertiary inline" data-vis="grid" href="#grid"><img src="/img/vis_grid_white.png" />grid</a>
                        <a data-help data-help-id="vis_menu" data-log="8" class="button tertiary inline" data-vis="tree" data-svg href="#tree"><img src="/img/vis_tree_white.png" />tree</a>
                        <a data-help data-help-id="vis_menu" data-log="9" class="button tertiary inline" data-vis="network" data-svg href="#network"><img src="/img/vis_network_white.png"/>network</a>
                    </span>
                    @yield('header_actions')
				</div>
                <div class="large-7 columns" data-help="index" data-help-id="search">
                    @yield('header_search')
                </div>
            </div>
            @yield('header_content')
        </header>

        <div class="container">
            @yield('content')
        </div>

        {{-- MODALS --}}
        {{-- ABOUT --}}
        <div id="about" class="reveal-modal small" data-reveal aria-labelledby="about_title" aria-hidden="true" role="dialog">
        </div>

        {{-- FORMS --}}
        @include('forms.master', ['form' => 'feedback', 'class' => 'small']) {{-- feedback --}}
        @yield('forms')

        {{-- SCRIPTS --}}
        {{-- <script src="/js/foundation.min.js"></script> --}}
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <script src="/js/foundation/foundation.js"></script>
        <script src="/js/foundation/foundation.reveal.js"></script>
        <script src="/js/foundation/foundation.abide.js"></script>
        <script src="/js/cookie.js"></script>
        <script src="/js/app.js?v=@version"></script>

        <script>
            $(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
                var modal = $(this);
                $(document).foundation('equalizer', 'reflow');
                if(modal.hasClass('slide')){
                    modal.animate({right:'0%'},500);
                }
            });

            $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
                var modal = $(this);
                if(modal.hasClass('slide')){
                    modal.animate({right:'-50%'},500);
                }
            });

            $(document).ready(function(){
                $('*[data-dropdown]').on("click", function(){
                    $('.dropdown').hide();
                    var id = $(this).data('dropdown');
                    if($(this).data('dropdown-position') == 'anchor') $('.dropdown#'+id).css({left: $(this).offset().left - 8});
                    $('.dropdown#'+id).toggle();
                    console.log($('#'+id));
                });
                $('.dropdown').find('.close').on('click', function(){
                    $(this).parents('.dropdown').hide();
                });
            });
        </script>

        @yield('scripts')

        <script src="/js/help.js?v=@version"></script>

    </body>
</html>
