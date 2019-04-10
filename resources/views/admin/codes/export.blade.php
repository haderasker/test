@extends('admin.layouts.master')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>Export</h1>
        </div>
    </div>

    {!! Form::open(array('url' => 'excelview' , 'class' => 'form-horizontal',"method"=>"get")) !!}

    <div class="form-group">
        {!! Form::label('Users', 'Users*', array('class'=>'col-sm-2 control-label')) !!}
        <div class="col-sm-10">
            {!! Form::select('id', $users,null, array('class'=>'form-control','placeholder' => 'Select User' , 'id'=>'myUser')) !!}
            {!! Form::hidden('name',null, array('class'=>'form-control' , 'id'=>'myName')) !!}

        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit( 'Export' , array('class' => 'btn btn-primary')) !!}
        </div>
    </div>

    {!! Form::close() !!}


@endsection