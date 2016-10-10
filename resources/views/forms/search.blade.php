<form class="sort">
    <div class="row">
      {{--
       <div class="medium-4 columns form-inline">
        <label for="auteurs">Authors</label>
           <span class="field">
             <select name="auteurs" id="auteurs">
              <option value ="all">All</option>
                <option disabled>──────────</option>
                @foreach ($auteurs as $auteur)
                  @if(isset($search))
                       @if($auteur->id == $search['author'])
                        <option value="{{ $auteur->id }}" selected>{{ $auteur->name }}</option>
                        @else
                        <option value="{{ $auteur->id }}">{{ $auteur->name }}</option>
                        @endif
                    @else
                    <option value="{{ $auteur->id }}">{{ $auteur->name }}</option>
                    @endif
                @endforeach
               </select>
           </span>
       </div>
       --}}

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="author">Authors</label>
               <div class="field">
                   <div class="awesomplete">
                   @if(isset($currentAuthor) && $currentAuthor != 'all')
                    <input class="dropdown-input" data-log="10" data-log-keyboard autocomplete="off" aria-autocomplete="list" id="author" list="authors-list" value="{{$currentAuthor}}" />
                    @else
                    <input class="dropdown-input" data-log="10" data-log-keyboard autocomplete="off" aria-autocomplete="list" id="author" list="authors-list" />
                    @endif
                   </div>
               </div>
               <datalist id="authors-list">
                    @foreach ($authors as $author)
                        <option>{{ $author->name }}</option>
                    @endforeach
                </datalist>
                <button class="dropdown-btn form-input" data-log="11" type="button">&darr;</button>
           </div>
       </div>

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="tag">Tags</label>
               <div class="field">
                   <div class="awesomplete">
                   @if(isset($currentTag) && $currentTag != 'all')
                    <input class="dropdown-input" data-log="12" data-log-keyboard autocomplete="off" aria-autocomplete="list" id="tag" list="tags-list" value="{{$currentTag}}" />
                    @else
                    <input class="dropdown-input" data-log="12" data-log-keyboard autocomplete="off" aria-autocomplete="list" id="tag" list="tags-list" />
                    @endif
                   </div>
               </div>
               <datalist id="tags-list">
                    @foreach ($tags as $tag)
                        <option>{{ $tag->tag }}</option>
                    @endforeach
                </datalist>
                <button class="dropdown-btn form-input" data-log="13" type="button">&darr;</button>
           </div>
       </div>

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="keyword">Search</label>
               <div class="field">
                @if(isset($currentKeyword))
                    <input type="text" id="keyword" data-log="14" data-log-keyboard value="{{ $currentKeyword }}"/>
                @else
                    <input type="text" id="keyword" data-log="14" data-log-keyboard />
                @endif
               </div>
           </div>
       </div>
    </div>
</form>

<script src="/js/awesomplete.min.js" async onload="awesomplete_init();"></script>
<script>
    function awesomplete_init(){
        $('input.dropdown-input').each(function(){
            var comboplete = new Awesomplete('#' + $(this).attr('id'), {
               minChars: 0
            });
            var el = $(this).parents('.form-inline').children('button')[0];
            el.addEventListener("click", function(e) {
                if (comboplete.ul.childNodes.length === 0) {
                    comboplete.evaluate();
                }
                else if (comboplete.ul.hasAttribute('hidden')) {
                    comboplete.open();
                }
                else {
                    comboplete.close();
                }
            });
            $(this).on('awesomplete-open', function(){
                $(el).html('&uarr;');
            });
            $(this).on('awesomplete-close',function(){
                $(el).html("&darr;");
            });
            $(this).on('awesomplete-selectcomplete', function(){
                $(".sort").submit();
            });
        });
    }

    $(".sort input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $(".sort").submit();
        }
    });

    $(".sort").submit(function(e){
        e.preventDefault();
        search();
    });

    function search(){
        var author = $(".sort input#author").val();
        if (author.trim() == "") author = "all";
        var tag = $(".sort input#tag").val();
        if (tag.trim() == "") tag = "all";
        var keyword = $(".sort input#keyword").val();
        window.location = "{{ URL::to('/') }}" + '/search/' + author + '/' + tag + (keyword?'/' + keyword:'');
    }
</script>
