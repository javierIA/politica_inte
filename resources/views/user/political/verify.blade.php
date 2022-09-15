@extends('user.political.layout')

@section('script_up')
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API','')}}&callback=initMap"></script>
@endsection


@section('content')
    <form role="form" id="save_verity_form" class="form-horizontal form-groups-bordered methods-form" method="POST" >
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

        <input type="hidden" name="_method" value="POST"/>
        <div class="categories-area section-padding27">
            <div class="container">
                <div class="row justify-content-sm-center" >
                    <div class="cl-xl-7 col-lg-8 col-md-10">
                        <!-- Section Tittle -->
                        <div class="section-tittle text-center mb-70">
                            <h2 style="font-size: 25px; margin-top: 20px">{{trans('user.verify_information')}}</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-sm-center">
                    <div class="col-md-8" >
                        <div class="row ">
                            <label for="person_name" class="col-sm-3 control-label">{{trans('admin.name').'(s)'}}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{$person->get_full_name()}}" readonly autofocus/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="birth_date" class="col-sm-3 control-label">{{trans('admin.birth_date')}}</label>
                            <div class='col-sm-9' >
                                <input type='text' class="form-control" value="{{$person->birth_date}}" readonly autofocus/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="person_sex" class="col-sm-3 control-label">{{trans('admin.person_sex')}}</label>
                            <div class='col-sm-9'>
                                <input type='text' class="form-control" value="{{$person->person_sex}}" readonly autofocus/>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 20px">
                            <label for="email" class="col-sm-3 control-label">{{trans('admin.email')}}</label>
                            <div class='col-sm-9'>
                                <input type='text' class="form-control" name="email" id="email"  value="{{$user->email}}" autofocus/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="phone_code_id" class="col-sm-3 control-label">{{trans('admin.person_cellphone')}}</label>
                            <div class="col-sm-2">
                                <select class="form-control select2" name="phone_code_id" id="phone_code_id" required autofocus>
                                    @foreach($phone_codes as $pc)
                                        <option value="{{ $pc->id }}" @if($cellphone->phone_code_id == $pc->id) selected @endif>{{ $pc->phone_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="tel" class="form-control check_data data_user" id="lada" name="lada" placeholder="Lada"  value="{{$cellphone->lada}}" autofocus/>
                            </div>
                            <div class="col-sm-5">
                                <input type="tel" class="form-control check_data recorder data_user" id="info" name="info" pattern="[0-9]{7}" value="{{$cellphone->info}}" autofocus/>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 20px">
                            <label for="user" class="col-sm-3 control-label">{{trans('admin.user')}}</label>
                            <div class='col-sm-9'>
                                <input type='text' class="form-control" value="{{$user->name}}" readonly autofocus/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="user" class="col-sm-3 control-label">{{trans('user.password')}}</label>
                            <div class='col-sm-9'>
                                <input type='password' class="form-control" name="password" id="password"  autofocus/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="user" class="col-sm-3 control-label">{{trans('user.password_check')}}</label>
                            <div class='col-sm-9'>
                                <input type='password' class="form-control" id="password_verify" autofocus/>
                            </div>
                        </div><br><br>
                        <div class="text-center" style="alignment: right">
                            <button type="submit" class="btn btn-default submit_form">{{ trans('admin.send') }}</button>
                        </div>
                        <br><br><br><br>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

@section('script_down')
    <script type="text/javascript">
        (function($){
            $(window).on('load',function() {
                $('#save_verity_form').submit(function (event) {
                    event.preventDefault();
                    if($('#password_verify').val() != $('#password').val())
                        $.notify("{!! trans('user.different_passwords') !!}", "warning");
                    else{
                        $.post("{{ route('person.saveVerify', $person) }}",
                            $('#save_verity_form').serialize(),
                            function (data, status) {
                                if(status){
                                    var decoded_data = jQuery.parseJSON(data);
                                    if(decoded_data.status){
                                        $.notify("{!! trans('user.info_verified') !!}", "info");
                                        $(location).attr('href', '{{route('dashboard')}}');
                                    }
                                }
                            });
                    }
                });
            });
        })(jQuery);
    </script>
@endsection