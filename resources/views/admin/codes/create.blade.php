@extends('admin.layouts.master')

@section('content')
    @if($users)
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

        {!! Form::open(array('url' => "admin/codes/generate", 'class' => 'form-horizontal',"method"=>"get")) !!}
        <div class="form-group">
            {!! Form::label('Codes',  'Codes Count*', array('class'=>'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::number('count', old('count'), array('class'=>'form-control')) !!}

            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Package', 'Package*', array('class'=>'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::select('packageId', $packages,null, array('class'=>'form-control','placeholder' => 'Select Package')) !!}

            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Users', 'Users*', array('class'=>'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::select('panelUserId', $users,null, array('class'=>'form-control','placeholder' => 'Select User')) !!}

            </div>
        </div>
        <div class="form-group">
            {!! Form::label('User Name', 'User Name*', array('class'=>'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::text('userName', old('userName'), array('class'=>'form-control')) !!}

            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                {!! Form::submit('Create' , array('class' => 'btn btn-primary')) !!}
            </div>
        </div>

        {!! Form::close() !!}
    @else
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <a class="btn btn-danger" href="{{url("admin/users_api/create")}}">Create Users First </a>
            </div>
        </div>

    @endif

@endsection