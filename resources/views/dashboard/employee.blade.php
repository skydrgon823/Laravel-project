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
                                            <h3 class="nk-block-title page-title">Employee List</h3>
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
                                    
                                    <div>
                                        <div class="" style="text-align: right;"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewEmployee">New employee</button></div>
                                    </div>
                                    
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card card-bordered card-stretch">
                                        <div class="card-inner-group">
                                            <div class="card-inner">                                        
                                            
                                            <div class="card-inner p-0">
                                                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
                                                            
                                                            <th class="nk-tb-col nk-tb-col-check">
                                                                <span class="sub-text">
                                                                    <span>No</span>
                                                                </span>
                                                            </th>

                                                            <th class="nk-tb-col tb-col-mb">
                                                                <span class="sub-text">
                                                                    <span>Employee</span>
                                                                </span>
                                                            </th>
                                                            <th class="nk-tb-col tb-col-md">
                                                                <span class="sub-text">
                                                                    <span>Phone</span>
                                                                </span>
                                                            </th>
                                                            
                                                            <th class="nk-tb-col tb-col-md">
                                                                <span class="sub-text">
                                                                    <span>Last Updated</span>
                                                                </span>
                                                            </th>
                                                            
                                                            <th class="nk-tb-col nk-tb-col-tools text-right">
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($employees))
                                                            @foreach($employees as $employee)
                                                                @csrf
                                                            <tr class="nk-tb-item">
                                                                <td class="nk-tb-col tb-col-mb">
                                                                    <span class="tb-amount">{{$employee->no}}</span>
                                                                </td>
                                                                <td class="nk-tb-col nk-tb-col-mb">
                                                                    <div class="user-card">
                                                                        <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                                            <span>{{strtoupper(substr($employee->name, 0, 1))}}{{strtoupper(substr($employee->surname, 0, 1))}}</span>
                                                                        </div>
                                                                        <a class="user-info" href = "#" data-toggle="modal" data-target="#modalEditEmployee{{$employee->id}}">
                                                                            <span class="tb-lead">{{$employee->name}} {{$employee->surname}}<span class="dot dot-success d-md-none ml-1"></span></span>
                                                                            <span>{{$employee->email}}</span>
                                                                        </a>
                                                                    </div>
                                                                </td>

                                                                <td class="nk-tb-col tb-col-md">
                                                                    <div class="">
                                                                        <a href = "#" data-toggle="modal" data-target="#modalEditEmployee{{$employee->id}}" class="tb-lead">{{$employee->phone}}</a>
                                                                    </div>
                                                                </td>
                                                                
                                                                <td class="nk-tb-col tb-col-md">
                                                                    <div class="">
                                                                        <a href = "#" data-toggle="modal" data-target="#modalEditEmployee{{$employee->id}}" class="tb-lead"><span>{{$employee->updated_at}}</span></a>
                                                                    </div>
                                                                </td>
                                                                
                                                                <td class="nk-tb-col nk-tb-col-tools">
                                                                    
                                                                    <div class="dropdown">
                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                            <ul class="link-list-plain">
                                                                                
                                                                                <li><a href="#" data-toggle="modal" data-target="#modalEditEmployee{{$employee->id}}">Edit</a></li>
                                                                                <li><a href="#" data-toggle="modal" data-target="#modalDeleteEmployee{{$employee->id}}">Delete</a></li>
                                                                                
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                            
                                                            <div class="modal fade" tabindex="-1" id="modalEditEmployee{{$employee->id}}">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <em class="icon ni ni-cross"></em>
                                                                        </a>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Update Employee</h5>

                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>
                                                                                
                                                                            </p>
                                                                            <form action="{{route('update-employee')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                @csrf 
                                                                                <input type="hidden" name="id" value="{{$employee->id}}">  
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">Name</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text" name="name" value="{{$employee->name}}" class="form-control" id="name" placeholder="Enter name">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">SurName</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text" name="surname" value="{{$employee->surname}}" class="form-control" id="surname" placeholder="Enter Surname">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">Email</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text" name="email" value="{{$employee->email}}" class="form-control" id="email" placeholder="Enter Email">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">Phone</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="number" name="phone" value="{{$employee->phone}}" class="form-control" id="phone" placeholder="Enter Phone">
                                                                                        </div>
                                                                                    </div>                                                 
                                                                                    <div class="form-group" style="text-align: right;">
                                                                                        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                                                                                    </div>
                                                                                </form>
                                                                        </div>
                                                                        <div class="modal-footer bg-light">
                                                                            <span class="sub-text"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade" tabindex="-1" id="modalDeleteEmployee{{$employee->id}}">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <em class="icon ni ni-cross"></em>
                                                                        </a>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">are you sure you want to delete this employee?</h5>

                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>
                                                                                All related messages and documents will be deleted. The operation cannot be undone. Continue?
                                                                            </p>
                                                                            <form action="{{route('delete-employee')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                @csrf   
                                                                                    <input type="hidden" name="id" value="{{$employee->id}}">
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

                                            </div><!-- .card-inner -->
                                                                                                                               
                                        </div><!-- .card-inner-group -->
                                        
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>

                                                            <div class="modal fade" tabindex="-1" id="modalNewEmployee">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <em class="icon ni ni-cross"></em>
                                                                        </a>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Create New Employee</h5>

                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>
                                                                                
                                                                            </p>
                                                                            <form action="{{route('store-employee')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                @csrf   
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="default-01">Name</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="default-01">SurName</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="text" name="surname" class="form-control" id="surname" placeholder="Enter Surname" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="default-01">Email</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="default-01">Phone</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="number" name="phone" class="form-control" id="phone" placeholder="Enter Phone" required>
                                                                                    </div>
                                                                                </div>                                                 
                                                                                <div class="form-group" style="text-align: right;">
                                                                                    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer bg-light">
                                                                            <span class="sub-text"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
@endsection('content')  