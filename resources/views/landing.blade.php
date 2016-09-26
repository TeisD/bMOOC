<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bMOOC - LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="/img/favicon.ico">

    <link rel="preload" href="/css/foundation-icons.min.css" as="style" onload="this.rel='stylesheet'">
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
<div id="wrapper">
            <header>
            <div class="row space">
                <div class="small-12 medium-6 columns">
					<h1 class="inline"><a href="/">bMOOC</a></h1>
				</div>
			    <div class="small-12 medium-6 columns text-right">
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
                                <a href="/auth/login" data-reveal-id="signin" data-reveal-ajax="true">Sign in</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <button class="giant centered" data-reveal-id="signin" data-reveal-ajax="/auth/login">Sign in</button>

        <div class="landing_controls">
            <button class="tubular-play-pause tubular-pause"><i class="fi-pause"></i></button>
            <button class="tubular-play-pause tubular-play" style="display: none"><i class="fi-play"></i></button>
            <button class="tubular-mute"><i class="fi-volume-strike"></i></button>
        </div>

        {{-- MODALS --}}
        {{-- ABOUT --}}
        <div id="about" class="reveal-modal small" data-reveal aria-labelledby="about_title" aria-hidden="true" role="dialog">
            @include('modals.about')
        </div>
        <div id="signin" class="reveal-modal small" aria-hidden="true" role="dialog">
        </div>

        {{-- FORMS --}}
        @include('forms.master', ['form' => 'feedback', 'class' => 'small']) {{-- feedback --}}
        </div>

        {{-- SCRIPTS --}}
        {{-- <script src="/js/foundation.min.js"></script> --}}
        <script src="/js/foundation/foundation.js"></script>
        <script src="/js/foundation/foundation.reveal.js"></script>
        <script src="/js/foundation/foundation.abide.js"></script>
        <script src="/js/app.js?v=@version"></script>
        <script src="/js/help.js?v=@version"></script>
        <script src="/js/jquery.tubular.js"></script>

        <script>

            var videos = JSON.parse('{!! addslashes(json_encode($videos)) !!}');
            videoId = videos[Math.floor(Math.random() * videos.length)].url;

            $('document').ready(function() {
                var options = {
                    videoId: videoId,
                    start: 3
                };
                $('#wrapper').tubular(options);

                $(".tubular-play-pause").on('click', function(){
                    $(".tubular-play-pause").toggle();
                })
                $(".tubular-mute").on('click', function(){
                    $('i', $(this)).toggleClass("fi-volume-strike");
                    $('i', $(this)).toggleClass("fi-volume");
                })
            });
        </script>

    </body>
</html>
