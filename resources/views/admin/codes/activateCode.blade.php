
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 3/27/18
 * Time: 12:47 PM
 */

@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>Add New</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {!! Form::open(array('url' => "admin/code/activate_hma", 'class' => 'form-horizontal',"method"=>"post")) !!}

    <div class="form-group">
        {!! Form::label('MAC', 'MAC*', array('class'=>'col-sm-2 control-label')) !!}
        <div class="col-sm-10">
            {!! Form::text('mac', old('mac'), array('class'=>'form-control')) !!}
            {!! Form::hidden('code', $code, array('class'=>'form-control')) !!}
            {!! Form::hidden('api', $api, array('class'=>'form-control')) !!}

        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit('Create' , array('class' => 'btn btn-primary')) !!}
        </div>
    </div>

    {!! Form::close() !!}


@endsection