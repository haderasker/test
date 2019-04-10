{{--<!doctype html>--}}
{{--<html lang="{{ app()->getLocale() }}">--}}
{{--<head>--}}
{{--<meta charset="utf-8">--}}
{{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
{{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}

{{--<!-- Fonts -->--}}
{{--<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}

{{--</head>--}}
{{--<body>--}}
{{----}}
{{----}}
{{--</body>--}}
{{--</html>--}}

@include('admin.partials.header')

<div class="clearfix"></div>
<div class="page-container">

    <div class="page-content-wrapper">
        <div class="page-content">


            <div class="row">
                <div class="col-md-12">
                    @if (Session::has('message'))
                        <div class="note note-info">
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif
                    <div>
                        <div class="col-md-3">
                            <b>Server Not Found Please Refresh Again</b>
                        </div>
                        <div class="col-md-9">
                            <a href="{{url("/")}}" class="btn btn-success">Refresh Again</a>
                        </div>
                    </div>
                    @if(Auth::user()->id != 4)
                        <div class="col-md-12" style="margin-bottom: 20px"><b>OR Change Middleware Url</b></div>
                        <form action="{{url('admin/change/api/url')}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="col-md-2">
                                    {!! Form::select('urlApi', config("app.api_url") , config("app.api_url") ,array('class'=>'form-control' , 'id'=>'MyApiButton' , 'placeholder' => 'Select Api URL')) !!}
                                    {!! Form::hidden('url', null , array('class'=>'form-control' , 'id' =>'myApiValue')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    {!! Form::submit('Change' , array('class' => 'btn btn-success')) !!}
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@include('admin.partials.javascripts')
@include('admin.partials.footer')
