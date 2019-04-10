<ul id="sortable">
    @foreach($list as $key=>$data)
        <li class="ui-state-default"><input type="hidden" name="{{$name}}" value="{{$key."_".$data}}">{{$data}}</li>
        @endforeach

</ul>