@extends('layouts.admin_app')

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
                                            <h3 class="nk-block-title page-title">Generation of User Codes</h3>
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
                                            <div class="card-inner">
                                                <div class="card-title-group">
                                                    <div class="card-title">
                                                        <h5 class="title"></h5>

                                                    </div>
                                                    
                                                    <div class="card-tools mr-n1">
                                                        <ul class="btn-toolbar">
                                                            <li>
                                                                <a href="#" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                                            </li><!-- li -->
                                                        </ul><!-- .btn-toolbar -->
                                                    </div><!-- card-tools -->
                                                    
                                                    <div class="card-search search-wrap" data-search="search">
                                                        <div class="search-content">
                                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                                            <form action="{{ route('search-usercode') }}" method="POST" role="search" style="display: flex;">
                                                                @csrf 
                                                                <input type="text" class="form-control form-control-sm border-transparent form-focus-none" name="incoming_did" placeholder="Quick search by Name">
                                                                <!--<input type="text" class="form-control form-control-sm border-transparent form-focus-none" name="description" placeholder="Quick search by Location Name">-->
                                                                <button type="submit" class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                                            </form>
                                                        </div>
                                                    </div><!-- card-search -->
                                                </div><!-- .card-title-group -->
                                            </div><!-- .card-inner -->
                                            
                                            <div class="card-inner p-0">
                                            <form action="{{route('generate-usercode')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                    @csrf 
                                                    <input type="hidden" name="code_type" value="">  
                                                    <div class="form-group">
                                                        <label class="form-label" for="default-01"></label>
                                                        <div class="form-control-wrap">
                                                            <input type="number" name="number" value="" class="form-control" id="code" placeholder="Enter User Code" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="text-align: right;">
                                                        <button type="submit" class="btn btn-lg btn-danger">Generate Codes</button>
                                                        <a href = "#"  data-toggle="modal" data-target="#modalfreecode" class="btn btn-lg btn-danger">Free Codes</a>
                                                    </div>                                                                                    
                                            </form>                                        
                                                <table class="table table-tranx table-dids">
                                                    <thead>
                                                        <tr class="">
                                                            
                                                            <th class="tb-tnx-info">
                                                                <span class="">
                                                                    <span>Code</span>
                                                                </span>
                                                            </th>
                                                            <th class="tb-tnx-info">
                                                                <span class="">
                                                                    <span>Employee</span>
                                                                </span>
                                                            </th>
                                                            
                                                        </tr><!-- tb-tnx-item -->
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($usercodes))
                                                            @foreach($usercodes as $usercode)
                                                                @csrf
                                                            <tr class="tb-tnx-item">
                                                                
                                                                <td class="tb-tnx-info">
                                                                    <div class="">
                                                                        <span class="title">{{$usercode->code}}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="tb-tnx-info">
                                                                    <div class="">
                                                                        <span class="title">@if(($usercode->employee_id)&&($usercode->is_used)) {{App\Models\Employee::get_employee_name($usercode->employee_id)}} {{App\Models\Employee::get_employee_surname($usercode->employee_id)}} @endif</span>
                                                                    </div>
                                                                </td>
                                                            </tr><!-- tb-tnx-item -->
                                                          
                                                            @endforeach
                                                            </form>
                                                        @endif
                                                    </tbody>
                                                </table>

                                            </div><!-- .card-inner -->
                                                                                                                               
                                        </div><!-- .card-inner-group -->
                                        
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="modalfreecode">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                <em class="icon ni ni-cross"></em>
                            </a>
                            <div class="modal-header">
                                <h5 class="modal-title">Free Code</h5>

                            </div>
                            <div class="modal-body">
                                <p>
                                    
                                </p>
                                    <table class="table table-tranx table-dids">
                                        <thead>
                                            <tr class="">
                                                
                                                <th class="tb-tnx-info">
                                                    <span class="">
                                                        <span>Code</span>
                                                    </span>
                                                </th>
                                                
                                                <th class="tb-tnx-action noExl">
                                                    <span>&nbsp;</span>
                                                </th>
                                            </tr><!-- tb-tnx-item -->
                                        </thead>
                                        <tbody>
                                            @if(!empty($freecodes))
                                                @foreach($freecodes as $freecode)
                                                    @csrf
                                                <tr class="tb-tnx-item">
                                                    
                                                    <td class="tb-tnx-info">
                                                        <div class="">
                                                            <span class="title">{{$freecode->code}}</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-tnx-action noExl">
                                                        
                                                        <div class="dropdown">
                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                <ul class="link-list-plain">
                                                                    
                                                                    <li><a href="#" data-toggle="modal" data-target="#modalDeleteFreecode{{$freecode->id}}">Delete</a></li>
                                                                    
                                                                </ul>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr><!-- tb-tnx-item -->
                                                    <div class="modal fade" tabindex="-1" id="modalDeleteFreecode{{$freecode->id}}">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <em class="icon ni ni-cross"></em>
                                                                </a>
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">are you sure you want to delete this freecode?</h5>

                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>
                                                                        The operation cannot be undone. Continue?
                                                                    </p>
                                                                    <form action="{{route('delete-freecode')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                        @csrf   
                                                                            <input type="hidden" name="id" value="{{$freecode->id}}">
                                                                            <div class="form-group" style="text-align: right;">
                                                                                <button type="submit" class="btn btn-lg btn-danger">Delete</button>
                                                                            </div>
                                                                        </form>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <span class="sub-text"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                
                                                @endforeach
                                                </form>
                                            @endif
                                        </tbody>
                                    </table> 
                            </div>
                            <div class="modal-footer bg-light">
                                <span class="sub-text"></span>
                            </div>
                        </div>
                    </div>
                </div>
                

@endsection('content')  