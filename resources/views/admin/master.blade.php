<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bMOOC | @yield('title')</title>

    {{-- STYLESHEETS --}}
    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" href="/css/foundation.min.css">
    <link rel="stylesheet" href="/css/app.css?v=@version">

    <script src="/js/vendor/modernizr.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>

   <header style="max-width: 900px; width=100%; margin: 0 auto; display: block;">
      <div class="row">
          <div class="columns medium-2 large-2">
              <h1>bMOOC</h1>
               {{--<nav>
                  <div class="icon-bar two-up">
                      <a href="/admin/data" class="item @menu('data')">
                        <i title="video" class="fa fa-area-chart"></i>
                        <label>Data</label>
                      </a>
                      <a href="/admin/actions" class="item @menu('actions')">
                        <i title="video" class="fa fa-cog"></i>
                        <label>Actions</label>
                      </a>
                    </div>
               </nav>--}}
          </div>
           <div class="columns medium-10 medium-10">
               <nav>
                   <ul class="inline slash center">
                       @yield('nav_secondary')
                    </ul>
               </nav>
           </div>
       </div>
    </header>

    <div style="max-width: 900px; width=100%; margin: 0 auto;">
            @yield('content')
    </div>

    <script src="/js/vendor/jquery.min.js"></script>
    <script>
        var host = "{{ URL::to('/') }}";
    </script>
    @yield('scripts')
</body>
</html>
