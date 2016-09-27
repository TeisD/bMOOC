@extends('modals.master')
  @section('modal_content')
   <h2 id="about_title">bMOOC</h2>
    <h3>A Massive, Open, Online Course to think with eyes and hands</h3>

<p>bMOOC is an educational, visual and digital platform designed for studio art education in the bachelor’s and master’s programs in visual arts. The platform aims to assist the development of a visual language.</p>

<p>These introductory videos introduce the platform and can help you get started.</p>

<p>See <a href="#" class="emphasis">our manual</a> for details on how to use the platform and for context and clarification.</p>

    @foreach($videos as $video)
    <div class="videoWrapper">
        <iframe src="https://www.youtube.com/embed/{{ $video->url }}" frameborder="0"></iframe>
    </div>
    @endforeach
    @endsection
