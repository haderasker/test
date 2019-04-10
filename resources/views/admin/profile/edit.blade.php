@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>Edit User</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('
                        <li class="error">:message</li>
                        ')) !!}
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {!! Form::open(['url' => ['user/update'], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
    {!! Form::hidden('user_id', old('user_id', $user->id), ['class'=>'form-control']) !!}
    {{--<div class="form-group">--}}
        {{--{!! Form::label('name', trans('quickadmin::admin.users-edit-name'), ['class'=>'col-sm-2 control-label']) !!}--}}
        {{--<div class="col-sm-10">--}}
            {{--{!! Form::text('name', old('name', $user->name), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::admin.users-edit-name_placeholder')]) !!}--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="form-group">--}}
        {{--{!! Form::label('email', trans('quickadmin::admin.users-edit-email'), ['class'=>'col-sm-2 control-label']) !!}--}}
        {{--<div class="col-sm-10">--}}
            {{--{!! Form::email('email', old('email', $user->email), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::admin.users-edit-email_placeholder')]) !!}--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="form-group">
        {!! Form::label('password','Password', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> 'Password']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('confirm_password', 'Confirm Password', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::password('confirm_password', ['class'=>'form-control', 'placeholder'=> 'Confirm Password']) !!}
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection


