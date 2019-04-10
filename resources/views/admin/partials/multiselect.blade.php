<div class="form-group">
    {!! Form::label($selectId, $labelText, array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::select($selectName, $values,$selected, array("id"=>"$selectId","multiple"=>"multiple",'class'=>'form-control')) !!}
    </div>
</div>
