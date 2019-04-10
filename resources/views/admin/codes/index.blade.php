@extends('admin.layouts.master')

@section('content')

    <form action="{{url('admin/codes')}}" method="post">
        {{csrf_field()}}

        <div class="form-group">
            <div class="col-md-1">
                {!! Form::checkbox('active',1,false) !!} <span> Activate </span>
            </div>
            <div class="col-md-1">
                {!! Form::checkbox('unActive',1,false) !!} <span> Un Activate </span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-10">
                {!! Form::submit( 'Filter' , array('class' => 'btn btn-success')) !!}
            </div>
        </div>
    </form>

    <a href="{{url("admin/code/create")}}" class="btn btn-success ">Create Code</a>
    <a href="{{url("admin/codes/export")}}" class="btn btn-success ">Export Codes</a>

    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">List</div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-hover table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Package</th>
                    <th>Duration</th>
                    <th>Device</th>
                    <th>API</th>
                    <th>Panel User Name</th>
                    <th>UserName</th>
                    <th>Password</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($codes as $key=>$code)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ $code->activeCode }}</td>
                        <td>{{ $code->start}}</td>
                        <td>{{ $code->end }}</td>
                        <td>{{ $code->packageId }}</td>
                        <td>{{ $code->duration }}</td>
                        <td>{{ $code->deviceId }}</td>
                        <td>{{ $code->api }}</td>
                        <td>{{ $code->panelUserId }}</td>
                        <td>{{ $code->userName }}</td>
                        <td>{{ $code->password  }}</td>
                        <td>{{ $code->createdAt  }}</td>
                        @if($code->deviceId == '-')
                            <td><a href="{{url("admin/code/activate_hma/$code->activeCode/$code->api")}}">Activate
                                    HMA</a>
                            </td>
                            <td>
                                <a href="{{url("admin/code/activate_global/$code->activeCode")}}">Activate Global</a>
                            </td>
                        @else
                            <td></td>
                            <td></td>
                        @endif
                        {{--<td><a href="{{url("admin/bouquet/edit/$code->id")}}">Edit</a>--}}
                        {{--<a href="{{url("admin/bouquet/delete/$code->id")}}">Delete</a>--}}
                        {{--</td>--}}

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$codes->appends(request()->except(['page','_token']))}}
            <form action="" method="post" id="paginateForm">
                {{csrf_field()}}

                <div class="form-group">
                    <div class="col-md-2 noPadding">
                        {{ Form::number('paginate', null, array('class' => 'form-control' , 'placeholder' => 'Pagination Number')) }}
                    </div>
                </div>
            </form>
            <a href="#" class="btn btn-success" id="paginate">Go</a>
        </div>
    </div>

@endsection