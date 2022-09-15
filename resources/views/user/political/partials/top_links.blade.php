<div class="wrap">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-12 col-md d-flex align-items-center">
                <p class="mb-0 phone"><span class="mailus">Phone no:</span> <a href="#">+00 1234 567</a> or <span class="mailus">email us:</span> <a href="#">emailsample@email.com</a></p>
            </div>
            <div class="col-12 col-md d-flex justify-content-md-end">
                <div class="social-media">
                    <p class="mb-0 d-flex">
                        <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
                        <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
                        <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
                        <a href="#" class="d-flex align-items-center justify-content-center login"><span ><i class="fa fa-lock fa-lg"></i></span></a>
                        {{--<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-dribbble"><i class="sr-only">Dribbble</i></span></a>--}}

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


{{--<ul class="topLinks">
    <nav class="site-nav-menu-up">
        <ul>
            <li><a href="#">
                    <img id="lselected" src="{{ asset('flat/'.config('locale.languages')[App::getLocale()][3]) }}"
                         style="width: 30px;">
                </a>
                <ul class="sub-menu">
                    @foreach(config('locale.languages') as $lang)
                        @if($lang[0] != App::getLocale())
                            <li>
                        @else
                            <li class="active">
                                @endif
                                <a class="lang" href="{{ route('lang.swap',$lang[0]) }}">
                                    <img src="{{ asset('flat/'.$lang[3]) }}" style="width: 30px;"/>
                                    <span>{{$lang[4]}}</span>
                                </a>
                            </li>
                            @endforeach
                </ul>
            </li>
            <li> <a href="#" class="login"><i class="fa fa-lock fa-lg"></i></a></li>
            --}}{{--<a href="#"><i class="xv-basic_lock"></i></a>--}}{{--
        </ul>
    </nav>
</ul>--}}

{{--
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <input type="text" name="email" placeholder="{{trans('user.email_address')}}"
           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
    <input type="password" name="password" placeholder="{{trans('user.password')}}">
    <button type="submit" class="btn btn-default btn-block btn-login">
        <i class="entypo-login"></i>
        {{trans('admin.login')}}
    </button>
    <a class="resetPass" href="#"> {{trans('user.or_register_here')}}</a>
</form>--}}
