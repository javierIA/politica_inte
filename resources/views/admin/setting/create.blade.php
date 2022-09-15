@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('setting.index') }}">{{ trans('admin.setting') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('setting.store') }}">

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

        <div class="form-group{{ $errors->any() ? ' has-error' : '' }}">

            <div class="row">
                <label for="address" class="col-sm-4 control-label">{{ trans('admin.address') }}</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="address" id="address" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="email" class="col-sm-4 control-label">{{ trans('admin.email') }}</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control input-lg" name="email" id="email" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="phone" class="col-sm-4 control-label">{{ trans('admin.phone') }}</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="phone" id="phone" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="fax" class="col-sm-4 control-label">Fax</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="fax" id="fax" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="facebook" class="col-sm-4 control-label">Facebook</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="facebook" id="facebook" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="instagram" class="col-sm-4 control-label">Instagram</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="instagram" id="instagram" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="twitter" class="col-sm-4 control-label">Twitter</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="twitter" id="twitter" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="pinterest" class="col-sm-4 control-label">Pinterest</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="pinterest" id="pinterest" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="shipping" class="col-sm-4 control-label">{{ trans('admin.shipping') }}</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="shipping" id="shipping" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="gifts" class="col-sm-4 control-label">{{ trans('admin.gifts') }}</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="gifts" id="gifts" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <label for="policy" class="col-sm-4 control-label">{{ trans('user.privacy_policy') }}</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-lg" name="policy" id="policy" autofocus/>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-sm-offset-8 col-sm-2 text-right">
                    <button type="submit" class="btn btn-default">{{trans('admin.send')}}</button>
                </div>
            </div>
        </div>

    </form>
@endsection