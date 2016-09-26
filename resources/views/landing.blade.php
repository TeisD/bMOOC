@extends('master_simple')

@section('content')

        <button class="giant centered" data-reveal-id="signin" data-reveal-ajax="/auth/login">Sign in</button>

        <div class="landing_controls">
            <button class="tubular-play-pause tubular-pause"><i class="fi-pause"></i></button>
            <button class="tubular-play-pause tubular-play" style="display: none"><i class="fi-play"></i></button>
            <button class="tubular-mute"><i class="fi-volume-strike"></i></button>
        </div>
@endsection

@section('scripts')
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
@endsection
