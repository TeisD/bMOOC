@extends('modals.master')
  @section('modal_content')
   <h2 id="about_title">bMOOC</h2>
    <h3>A Massive, Open, Online Course to think with eyes and hands</h3>

    <p>The point of departure and finality of <strong>b</strong>MOOC is that, whether you are a teacher or a student, you are intrigued by 'images'.</p>

    <p>The structure of bMOOC is simple: the course consists of topics. A topic is a collection of online artefacts that are placed next to each other. A topic opens a space for gathering. The first question is: how to relate to this topic?</p>

    <p>Topics may have specific instructions. They do not determine the contribution, but ask the contributor to disclose the gaze and to become attentive for (some)thing(s).</p>

    <p>Login/register in order to join. Feel free to contribute to any topic. Click <a href="#" class="emphasis" help-show="index">help</a> for assistance and <a href="#" class="emphasis" data-reveal-id="about">about</a> for more information.</p>

    @foreach($videos as $video)
    <div class="videoWrapper">
        <iframe src="https://www.youtube.com/embed/{{ $video->url }}" frameborder="0"></iframe>
    </div>
    @endforeach
    @endsection
