@extends('admin.layout')
@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}"><i class="fa fa-home"></i>{{trans('admin.home')}}</a>
    </li>
    <li class="active">
        <strong>{{trans('admin.edit_profile')}}</strong>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div align="center">
                    <img src="{{asset('storage/app/'.Auth::user()->avatar )}}" class="img-responsive img-circle"
                         width="100px">
                </div>
            </div>
            <br/>
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-body">
                    <form method="POST" action="{{ route('update_profile') }}" class="form-horizontal form-groups-bordered" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if ($errors->any())
                            <div class="row">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-sm-4 control-label">{{trans('admin.name')}}</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="entypo-user"></i></span>
                                    <input type="text" value="{{Auth::user()->name}}" name="name" id="name" class="form-control" placeholder="{{trans('admin.name')}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">{{trans('admin.email')}}</label>

                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="entypo-mail"></i></span>
                                    <input type="text"  value="{{Auth::user()->email}}" name="email" id="email" class="form-control" placeholder="{{trans('admin.email')}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">{{trans('admin.password')}}</label>

                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="entypo-key"></i></span>
                                    <input type="password" class="form-control" name="password" id="password" value="" autofocus />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">{{trans('admin.confirm_password')}}</label>

                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="entypo-key"></i></span>
                                    <input type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password_confirmation" id="password-confirm"
                                           />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">{{trans('admin.image')}}</label>

                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="entypo-camera"></i></span>
                                    <input class="form-control" name="avatar" id="field-file" placeholder="{{trans('admin.image')}}" type="file" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-8 col-sm-5">
                                <button type="submit" class="btn btn-default">{{trans('admin.send')}}</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
@endsection