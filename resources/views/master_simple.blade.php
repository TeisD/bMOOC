<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bMOOC - LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="/img/favicon.ico">

    <link rel="preload" href="/css/foundation-icons.min.css" as="style" onload="this.rel='stylesheet'">

    {{-- js/loadCSS.js --}}
    <script type="text/javascript">
      !function(e){"use strict";var n=function(n,t,o){function i(e){return a.body?e():void setTimeout(function(){i(e)})}function r(){l.addEventListener&&l.removeEventListener("load",r),l.media=o||"all"}var d,a=e.document,l=a.createElement("link");if(t)d=t;else{var s=(a.body||a.getElementsByTagName("head")[0]).childNodes;d=s[s.length-1]}var f=a.styleSheets;l.rel="stylesheet",l.href=n,l.media="only x",i(function(){d.parentNode.insertBefore(l,t?d:d.nextSibling)});var u=function(e){for(var n=l.href,t=f.length;t--;)if(f[t].href===n)return e();setTimeout(function(){u(e)})};return l.addEventListener&&l.addEventListener("load",r),l.onloadcssdefined=u,u(r),l};"undefined"!=typeof exports?exports.loadCSS=n:e.loadCSS=n}("undefined"!=typeof global?global:this);
    </script>
    {{-- js/cssrelpreload.js --}}
    <script type="text/javascript">
        !function(t){if(t.loadCSS){var e=loadCSS.relpreload={};if(e.support=function(){try{return t.document.createElement("link").relList.supports("preload")}catch(e){return!1}},e.poly=function(){for(var e=t.document.getElementsByTagName("link"),n=0;n<e.length;n++){var r=e[n];"preload"===r.rel&&"style"===r.getAttribute("as")&&(t.loadCSS(r.href,r),r.rel=null)}},!e.support()){e.poly();var n=t.setInterval(e.poly,300);t.addEventListener&&t.addEventListener("load",function(){t.clearInterval(n)}),t.attachEvent&&t.attachEvent("onload",function(){t.clearInterval(n)})}}}(this);
    </script>

    {{-- STYLESHEETS --}}
    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" href="/css/foundation.min.css">
    <link rel="stylesheet" href="/css/app.css?v=@version">


    {{-- SCRIPTS --}}
    <script src="/js/vendor/modernizr.js"></script>

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
                                <a href="javascript:;" help-show>help</a>
                            </li>
                            <li>
                               <a href="/about" data-reveal-id="about" data-reveal-ajax="true">about</a>
                            </li>
                            <li>
                               <a href="javacript;" data-reveal-id="feedback">feedback</a>
                            </li>
                            <li>
                                <a href="/login">Sign in</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

       @yield('content')

       {{-- MODALS --}}
        {{-- ABOUT --}}
        <div id="about" class="reveal-modal small" data-reveal aria-labelledby="about_title" aria-hidden="true" role="dialog">
        </div>
        {{-- FORMS --}}
        @include('forms.master', ['form' => 'feedback', 'class' => 'small']) {{-- feedback --}}
        </div>

        {{-- SCRIPTS --}}
        {{-- <script src="/js/foundation.min.js"></script> --}}
        <script src="/js/vendor/jquery.min.js"></script>
        <script src="/js/foundation/foundation.js"></script>
        <script src="/js/foundation/foundation.reveal.js"></script>
        <script src="/js/foundation/foundation.abide.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>

        <script src="/js/cookie.js"></script>
        <script src="/js/app.js?v=@version"></script>

        @yield('scripts')

        <script src="/js/help.js?v=@version"></script>

    </body>
</html>

