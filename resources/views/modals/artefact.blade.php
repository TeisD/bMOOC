<div class="row full">
    <div class="columns large-3">
        <h2>{{ $artefact->title }}</h2>
        <dl class="details">
           <div class="row">
               <div class="small-6 medium-3 large-12 columns">
                <dt>Added</dt>
                <dd>{{date('d/m/Y H:i', strtotime($artefact->created_at))}}</dd>
               </div>
               <div class="small-6 medium-3 large-12 columns">
                <dt>By</dt>
                <dd><a href="/search/{{$artefact->author->id}}">{{$artefact->author->name}}</a></dd>
               </div>
                <div class="small-6 medium-3 large-12 columns">
                <dt>Tags</dt>
                <dd>
                    <ul class="inline slash">
                       @foreach($artefact->tags as $tag)
                        <li><a href="/search/all/{{$tag->id}}">{{$tag->tag}}</a></li>
                        @endforeach
                    </ul>
                </dd>
               </div>
               @if(isset($artefact->copyright))
               <div class="small-6 medium-3 large-12 columns">
                <dt>Copyright</dt>
                <dd>{{$artefact->copyright}}</dd>
               </div>
               @endif
            </div>
        </dl>
    </div>
    <div class="columns large-9" id="artefact">
        <div class="loader">
            <img src="/img/loader_overlay_big.gif" alt="loading..." />
        </div>
        <div class="artefact"></div>
    </div>
</div>

{{--
<script>
    var artefact = JSON.parse('{!! addslashes(json_encode($artefact)) !!}');
    render($('#artefact'), artefact);
</script>
--}}
