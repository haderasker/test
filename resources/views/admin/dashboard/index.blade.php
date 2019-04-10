@extends('admin.layouts.master')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
            </ul>
        </div>
    @endif

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12" style="margin-bottom: 20px">
                <form action="{{url('admin/change/all_config')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <div class="col-md-3">
                            @foreach($allConfigurations as $config)
                                <input type="checkbox" name="myConfig[{{$config->id}}][{{$config->configKey}}]"
                                       value="true"
                                       @if($config->configValue == 'true') checked @endif > {{ $config->configKey }}
                                <input type="hidden" name="config[{{$config->id}}][{{$config->configKey}}]" value="false">

                                <b style="margin-right: 10px"></b>
                            @endforeach

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            {!! Form::submit('Change Config' , array('class' => 'btn btn-success')) !!}
                        </div>
                    </div>
                </form>
            </div>
            @if(Auth::user()->id != 4)
                <form action="{{url('admin/change/api/url')}}" method="post" id="changeUrl">
                    {{csrf_field()}}
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Form::select('urlApi', config("app.api_url") , config("app.api_url") ,array('class'=>'form-control' , 'id'=>'MyApiButton' , 'placeholder' => 'Select Api URL')) !!}
                            {!! Form::hidden('url', null , array('class'=>'form-control' , 'id' =>'myApiValue')) !!}
                        </div>
                    </div>
                </form>
                <a href="#" class="btn btn-success" id="changeUrlButton">Change Api Url</a>
            @endif
            <a href="{{url("admin/server/sync_streaming_servers")}}" class="btn btn-success">Sync Streaming Servers</a>
            <div id="servers-table">

                <div class="col-xs-9 col-xs-offset-1" style="margin-top: 5px">
                    <div class="span12 text-center span-text-header">
                        Primary Server
                        <a id="0" href="{{$primaryServerUrl}}"></a>
                    </div>
                    {{--<div class='span12 text-center text-grey' id="serverChannelsCount0"> Streams:--}}
                    {{--</div>--}}
                    <div class='pull-left text-grey'>CPU</div>
                    <div class='pull-right text-grey' id="serverCpuText0"> %</div>
                    <br>
                    <div class='progress'>
                        <div id="serverCpuWidth0" class='progress-bar' role='progressbar'
                             aria-valuenow='' aria-valuemin='0'
                             aria-valuemax='100'></div>
                    </div>
                    <div class='pull-left text-grey'>RAM</div>
                    <div class='pull-right text-grey' id="serverRamText0"> %</div>
                    <br>
                    <div class='progress'>
                        <div id="serverRamWidth0" class='progress-bar' role='progressbar'
                             aria-valuenow='' aria-valuemin='0'
                             aria-valuemax='100' style="background-color: #ffc539;width:0%"></div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"
                                style='width: 100%;text-align: left;padding: 10px;color: #448aff;margin-bottom: 20px'>
                            <span class="caret"></span>
                            <span id="networkCardNo0">0 </span> Network
                        </button>
                        <ul id="networkCard0" class="dropdown-menu" style="width: 100%">
                            {{----}}
                        </ul>
                    </div>
                    {{----}}

                </div>
                @foreach($servers as $server)

                    <div class="col-xs-3 col-xs-offset-1 bg-white" style="margin-top: 5px">
                        <div class="span12 text-center span-text-header">
                            {{$server->name}}
                            <a id="{{$server->id}}" href="{{$server->ipUrl}}"></a>
                        </div>
                        <div class='span12 text-center text-grey' id="serverChannelsCount{{$server->id}}"> Streams:
                        </div>
                        <div class='pull-left text-grey'>CPU</div>
                        <div class='pull-right text-grey' id="serverCpuText{{$server->id}}"> %</div>
                        <br>
                        <div class='progress'>
                            <div id="serverCpuWidth{{$server->id}}" class='progress-bar' role='progressbar'
                                 aria-valuenow='' aria-valuemin='0'
                                 aria-valuemax='100'></div>
                        </div>
                        <div class='pull-left text-grey'>RAM</div>
                        <div class='pull-right text-grey' id="serverRamText{{$server->id}}"> %</div>
                        <br>
                        <div class='progress'>
                            <div id="serverRamWidth{{$server->id}}" class='progress-bar' role='progressbar'
                                 aria-valuenow='' aria-valuemin='0'
                                 aria-valuemax='100' style="width:0%"></div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"
                                    style='width: 100%;text-align: left;padding: 10px;color: #448aff;margin-bottom: 20px'>
                                <span class="caret"></span>
                                <span id="networkCardNo{{$server->id}}">0 </span> Network
                            </button>
                            <ul id="networkCard{{$server->id}}" class="dropdown-menu" style="width: 100%">

                            </ul>
                        </div>

                        <div style="margin-bottom: 10px">
                            <div class='col-xs-2 col-xs-offset-1'><h4 class='text-grey'
                                                                      style='text-align: center;vertical-align: middle;display: inline-block;margin-bottom: 5px'>
                                    <span id="serverOnlineChannels{{$server->id}}"></span>

                                    <br><span class='text-blue' style='margin-top: 5px'>Online</span></h4>
                            </div>
                            <div class='col-xs-2 col-xs-offset-1'><h4 class='text-grey'
                                                                      style='text-align: center;vertical-align: middle;display: inline-block;margin-bottom: 5px'>
                                    <span id="serverOfflineChannels{{$server->id}}"></span>

                                    <br><span class='text-grey' style='margin-top: 5px'>Offline</span></h4>
                            </div>
                            <div class='col-xs-2 col-xs-offset-1'><h4 class='text-grey'
                                                                      style='text-align: center;vertical-align: middle;display: inline-block;margin-bottom: 20px'>
                                    <span id="serverStoppedChannel{{$server->id}}"></span>
                                    <br><span class='text-red'
                                              style='margin-top: 5px;margin-bottom: 10px'>Stopped</span></h4>
                            </div>
                            <div class='col-xs-2 col-xs-offset-1'><h4 class='text-grey'
                                                                      style='text-align: center;vertical-align: middle;display: inline-block;margin-bottom: 20px'>
                                    <a href="{{url("admin/server/online-devices/$server->id/$server->name")}}">
                                        <span id="serverOnlineUsers{{$server->id}}"></span> </a>
                                    <br><span class='text-blue' style='margin-top: 5px;margin-bottom: 10px'>OnlineUsers</span></h4>
                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>


@endsection