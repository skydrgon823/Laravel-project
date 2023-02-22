@extends('layouts.auth_app')

@section('content')

                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                        <div class="brand-logo pb-4 text-center">
                            <a href="{{url('/')}}" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logos/logo.png') }}" srcset="{{asset('images/logos/logo.png').' 320w,'.asset('images/logos/logo.png').' 800w,'.asset('images/logos/logo.png').' 1200w'}}" alt="logo">
                                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logos/logo.png') }}" srcset="{{asset('images/logos/logo.png').' 320w,'.asset('images/logos/logo.png').' 800w,'.asset('images/logos/logo.png').' 1200w'}}" alt="logo-dark">
                            </a>
                        </div>
                        <div class="card card-bordered">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">Sign-In</h4>

                                        @if(session('message'))
                                            <span style="color: red;" role="alert">
                                                <strong>{{ session('message') }}</strong>
                                            </span>
                                        @endif                                          
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('signin') }}">

                                    @csrf
                                    <div class="form-group">

                                        <div class="form-label-group">
                                            <label class="form-label" for="default-01">{{ __('E-Mail Address') }}</label>
                                        </div>
                                        <input  placeholder="Enter your email address or username" id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror                                        
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">{{ __('Password') }}</label>
                                            <!-- @if (Route::has('password.request'))
                                            <a class="link link-primary link-sm" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                            @endif -->
                                        </div>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input id="password" placeholder="Enter your password" id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror                                              
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block">{{ __('Login') }}</button>
                                    </div>
                                </form>
                                <!-- <div class="form-note-s2 text-center pt-4"> New on our platform? <a href="{{route('register')}}">Create an account</a>
                                </div> -->
                              
                                <!-- <ul class="nav justify-center gx-4">
                                    <li class="nav-item"><a class="nav-link" href="#">Facebook</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">Google</a></li>
                                </ul> -->
                            </div>
                        </div>
                    </div>
                    <div class="nk-footer nk-auth-footer-full">
                        <div class="container wide-lg">
                            <div class="row g-3">
                                <!-- <div class="col-lg-6 order-lg-last">
                                    <ul class="nav nav-sm justify-content-center justify-content-lg-end">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Terms & Condition</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Privacy Policy</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Help</a>
                                        </li>
                                        <li class="nav-item dropup">
                                            <a class="dropdown-toggle dropdown-indicator has-indicator nav-link" data-toggle="dropdown" data-offset="0,10"><span>English</span></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                <ul class="language-list">
                                                    <li>
                                                        <a href="#" class="language-item">
                                                            <img src="{{ asset('images/flags/english.png') }}" alt="" class="language-flag">
                                                            <span class="language-name">English</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="language-item">
                                                            <img src="{{ asset('images/flags/spain.png') }}" alt="" class="language-flag">
                                                            <span class="language-name">Español</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="language-item">
                                                            <img src="{{ asset('images/flags/french.png') }}" alt="" class="language-flag">
                                                            <span class="language-name">Français</span>
                                                        </a>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div> -->
                                <div class="col-lg-6">
                                    <div class="nk-block-content text-center text-lg-left">
                                        <p class="text-soft">&copy; WhatsJob. All Rights Reserved.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
