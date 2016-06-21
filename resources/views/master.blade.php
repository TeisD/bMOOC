<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - bMOOC - LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="/img/favicon.ico">
    {{-- NON BLOCKING STYLESHEETS --}}
    <link rel="preload" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="//cdn.quilljs.com/0.20.1/quill.snow.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="//cdn.quilljs.com/0.20.1/quill.snow.css"></noscript>
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
	<body>
        {{-- JS: Google Analytics --}}
	    <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-71362622-1', 'auto');
          ga('send', 'pageview');
        </script>

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
                                <a href="#" help-show="index">help</a>
                            </li>
                            <li>
                               <a href="#" data-reveal-id="about">about</a>
                            </li>
                            <li>
                               <a href="#" data-reveal-id="feedback">feedback</a>
                            </li>
                            <li>
                                @if (isset($user))
                                   <a href="/auth/logout" class="logout">Sign out {{$user->name}}</a>
                                @else
                                   <a href="/auth/login" class="logout" data-reveal-id="signin" data-reveal-ajax>Sign in</a>
                                @endif
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row space">
				<div class="large-5 columns">
					<h1 class="inline"><a href="/">bMOOC</a></h1>
                    <span id="vis-menu">
                        <button class="tertiary inline" data-vis="list"><img src="/img/vis_list_white.png" />list</button>
                        <button class="tertiary inline" data-vis="grid"><img src="/img/vis_grid_white.png" />grid</button>
                        <button class="tertiary inline" data-vis="tree" data-svg><img src="/img/vis_tree_white.png" />tree</button>
                        <button class="tertiary inline" data-vis="network" data-svg><img src="/img/vis_network_white.png"/>network</button>
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
            <h2 id="about_title">bMOOC</h2>
            <h3>A Massive, Open, Online Course to think with eyes and hands</h3>

            <p>The point of departure and finality of <strong>b</strong>MOOC is that, whether you are a teacher or a student, you are intrigued by 'images'.</p>

            <p>The structure of bMOOC is simple: the course consists of topics. A topic is a collection of online artefacts that are placed next to each other. A topic opens a space for gathering. The first question is: how to relate to this topic?</p>

            <p>Topics may have specific instructions. They do not determine the contribution, but ask the contributor to disclose the gaze and to become attentive for (some)thing(s).</p>

            <p>Login/register in order to join. Feel free to contribute to any topic. Click <a href="#" class="emphasis" help-show="index">help</a> for assistance and <a href="#" class="emphasis" data-reveal-id="about">about</a> for more information.</p>

            <div class="deep">
                <h3>Massive</h3>
                <p>The course is the embodiment of a common commitment, it is a collective affair. A contribution never stands on its own, but is always related to other contributions within a topic. In their mutual relationship the different contributions bring something collectively to life: a massif is formed and takes shape.</p>

                <h3>Open</h3>
                <p>Nobody knows in advance the final destination of his/her contribution(s): to what it contributes and what it brings about. The direction and the content of the course is not fixed, or pre-conceived, but is formed and shaped by everyone's contribution.</p>

                <h3>Online</h3>
                <p>The word 'topic' derives from the Greek ta topica, and means commonplace. Several contributions are placed in the same space. The online space of topics collects individuals around "something". The linear narrative structure of a classic course is interrupted. No program, but a network shows itself. The contributions represent a shared research practice where possible connections and interests become visible.</p>

                <h3>Course</h3>
                <p>Contributions are not random, they imply a certain care for images. Images exist and never stand alone. They always have a context in which they are embedded and from which they make sense. Therefore the course creates structures that urge us to become attentive for something and to create new meanings (as non sense).</p>

                <p class="small"><em>bMOOC is a OOF- Research project by LUCA School of Arts (Art, Practices &amp; Education) and KU Leuven (Laboratory for Education and Society), commissioned by Association KU Leuven.</em></p>

                <p class="small"><strong>bMOOC is a constant test-run prototype: please <a href="#" class="emphasis" data-reveal-id="feedback">contact us</a> with your suggestions.</strong></p>
            </div>
              <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
        </div>

        {{-- FEEDBACK --}}
        <div id="feedback" class="reveal-modal small" data-reveal aria-labelledby="feedback_title" aria-hidden="true" role="dialog">
            <h2 id="feedback_title">Feedback</h2>
            <p>Remarks, problems or suggestions? Please fill in the form below.</p>
            @include('forms.feedback')
            <a class="close-reveal-modal close" aria-label="Close">&#215;</a>
        </div>

        {{-- SCRIPTS --}}
        {{-- <script src="/js/foundation.min.js"></script> --}}
        <script src="/js/foundation/foundation.js"></script>
        <script src="/js/foundation/foundation.reveal.js"></script>
        <script src="/js/app.js?v=@version"></script>
        <script src="/js/help.js?v=@version"></script>

        <script>
            $(document).foundation();
            $(document).ready(function(){
                $('*[data-dropdown]').on("click", function(){
                    var id = $(this).data('dropdown');
                    $('.dropdown#'+id).toggle();
                    console.log($('#'+id));
                });
                $('.dropdown').find('.close').on('click', function(){
                    $(this).parents('.dropdown').hide();
                });
            });
        </script>

        @yield('scripts')

    </body>
</html>
