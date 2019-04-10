<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-header-inner">
            <div class="navbar-header">
                <a href="{{ url('admin/dashboard') }}" class="navbar-brand">
                    <img src="{{asset("logo.png")}}" width="150">
                </a>
                <button type="button" id="sidebarCollapse" class="navbar-btn">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="top-menu">
                <ul class="nav navbar-nav navbar-right pull-right">
                    @if (Auth::user())
                        <li class="dropdown top-menu-item-xs">
                            <a href="" class="background text-purple" data-toggle="dropdown"
                               aria-expanded="true">
                                <i class="fa fa-user"></i>
                                {{strtoupper(auth()->user()->name)}} </a>
                            <ul class="dropdown-menu">
                                <li>
                                    {!! Form::open(['url' => 'user/edit' , 'method' =>'get']) !!}
                                    <button type="submit" class="logout">
                                        <i class="fa fa-lock"></i>
                                        <span class="text-purple">Change Password</span>
                                    </button>
                                    {!! Form::close() !!}
                                </li>
                                <li>
                                    {!! Form::open(['url' => 'logout']) !!}
                                    <button type="submit" class="logout">
                                        <i class="fa fa-sign-out fa-fw text-purple"></i>
                                        <span class="text-purple">Logout</span>
                                    </button>
                                    {!! Form::close() !!}
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>


            </div>
        </div>
    </div>
</div>