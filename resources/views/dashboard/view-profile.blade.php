@extends('layouts.admin_app1')

@section('content')   

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
                <div class="nk-block-des">

                    @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                    @endif
                    @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                    @endif  
                </div>
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                    
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{$current_user->name}}</h3>
                            <div class="nk-block-des text-soft">

                                @if(session('message'))
                                        <p>
                                            {{ session('message') }}
                                        </p>
                                @endif                                                
                            </div>
                            
                        </div><!-- .nk-block-head-content -->
                        
                        <div class="nk-block-head-content">

                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                    
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner">                                        
                            
                            <div class="card-inner p-0">
                            <form action="{{route('update-profile')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                @csrf 
                                <div class="form-group">
                                    <label class="form-label" for="name">Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" name="name" value="{{$current_user->name}}" class="form-control" id="name" placeholder="Enter Boss Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="cname">Company Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" name="cname" value="{{$current_user->company}}" class="form-control" id="cname" placeholder="Enter Company Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="attached">Company Logo</label>
                                    <div class="form-control-wrap">
                                        <div class="custom-file">
                                            <div class="image-input image-input-outline" id="kt_image_1">
                                                <div class="image-input-wrapper" style="background-image: url({{asset('public/'.$current_user->logo_path)}}); background-size: contain !important;"></div>

                                                <!-- <div class="image-input-wrapper" style=" background-size: contain !important;">
                                                    <img src="{{$current_user->logo_path}}" />
                                                </div> -->
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="attached" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="text-align: right;">
                                    <button type="submit" class="btn btn-lg btn-primary">Save Profile</button>
                                </div>
                            </form>                                        
                            <form action="{{route('update-password')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                @csrf 

                                <div class="form-group">
                                    <label class="form-label" for="current_password">{{ __('Current Password') }}</label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="current_password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input id="current_password" placeholder="Enter your Current password" id="current_password" type="password" class="form-control" name="current_password" required>
                                    @if ($errors->has('current_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                                    @endif                                    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="new_password">{{ __('New Password') }}</label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="new_password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input id="new_password" placeholder="Enter your New password" id="new_password" type="password" class="form-control" name="new_password" required>
                                    @if ($errors->has('new_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                    @endif                                    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="new_password_confirmation">{{ __('Confirm Password') }}</label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="new_password_confirmation">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input id="new_password_confirmation" placeholder="Enter your Confirm password" id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
                                    @if ($errors->has('new_password_confirmation'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                                    </span>
                                    @endif                                    
                                    </div>
                                </div>
                                <div class="form-group" style="text-align: right;">
                                    <button type="submit" class="btn btn-lg btn-primary">Save Password</button>
                                </div>
                            </form>                                        
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

@endsection('content')  