<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bMOOC | @yield('title')</title>

    <script src="css/foundation.css"></script>
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/vendor/modernizr.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>

   <header>
      <div class="row">
          <div class="columns">
              <h1>bMOOC</h1>
               <nav>
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
               </nav>
          </div>
      </div>
   </header>

   <div class="row">
       <div class="columns">
           <nav class="inline slash center">
               <ul>
                   @yield('nav_secondary')
                </ul>
           </nav>
       </div>
   </div>

    <div class="container">
            @yield('content')
    </div>

    <script src="js/vendor/jquery.js"></script>
    <script>
        var host = "{{ URL::to('/') }}";
    </script>
    @yield('scripts')
</body>
</html>
