<div class="row dropdown" id="topic_edit_{{$id}}">
      <a href="javascript:;" class="close" aria-label="Close">&#215;</a>
   <div class="columns">
        <ul class="list">
            <li><a href="/topic/{{$id}}?action=edit">Edit</a></li>
            @if($topic->archived)
                <li><a href="/topic/{{$id}}?action=unarchive">Unarchive</a></li>
            @else
                <li><a href="/topic/{{$id}}?action=archive">Archive</a></li>
            @endif
            <li><a href="/topic/{{$id}}?action=delete" onclick="var d = confirm('WARNING: YOU ARE ABOUT TO DELETE THE TOPIC\n\nClick \'OK\' to delete and \'cancel\' to abort. This action cannot be undone.\n\nIf you would like to archive the topic, click cancel and then select \'archive\' from the drop-down menu.'); return d;">Delete</a></li>
        </ul>
   </div>
</div>
