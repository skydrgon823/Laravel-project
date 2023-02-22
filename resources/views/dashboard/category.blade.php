@extends('layouts.admin_app')

@section('content')   


<div class="nk-content ">

                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block">
                                    <div class="card card-bordered">
                                        <div class="card-aside-wrap">
                                            <div class="card-inner card-inner-lg">
                                                <div class="nk-block-head nk-block-head-lg">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <h4 class="nk-block-title">Category</h4>
                                                            <div class="nk-block-des">
                                                                @if (session('success'))
                                                                    <div class="alert alert-success" role="alert">
                                                                        {{ session('success') }}
                                                                    </div>
                                                                @endif
                                                                @if (session('error'))
                                                                    <div class="alert alert-danger" role="alert">
                                                                        {{ session('error') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="" style="text-align: right;"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewCategory">New Category</button></div>
                                                        </div>
                                                    </div>
                                                </div><!-- .nk-block-head -->

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ empty(request()->get('tabName')) || request()->get('tabName') == 'jobwall' ? 'active' : '' }}" data-toggle="tab" href="#jobwall"><span>JOBwall</span></a>
    </li>    
    <li class="nav-item">
        <a class="nav-link {{ !empty(request()->get('tabName')) && request()->get('tabName') == 'jobdrawer' ? 'active' : '' }}" data-toggle="tab" href="#jobdrawer"><span>JOBdrawer</span></a>
    </li>
</ul>
<div class="tab-content">


    <div class="tab-pane {{ empty(request()->get('tabName')) || request()->get('tabName') == 'jobwall' ? 'active' : '' }}" id="jobwall">
                                                <div class="nk-block">
                                                    <div class="nk-data data-list">
                                                        <div class="card-inner p-0">

                                                            <table class="table table-tranx table-dids">

                                                                <tbody>
                                                                    @if(!empty($jobwall_categorys))
                                                                        @foreach($jobwall_categorys as $category)
                                                                            @csrf
                                                                        <tr class="tb-tnx-item">
                                                                            
                                                                            <td class="tb-tnx-info">
                                                                                <div class="">
                                                                                    <span class="title">{{$category->name}}</span>
                                                                                </div>
                                                                            </td>

                                                                            <td class="tb-tnx-info">
                                                                                <div class="">
                                                                                    <span class="title">{{$category->num}}</span>
                                                                                </div>
                                                                            </td>
                                                                            
                                                                            <td class="tb-tnx-action noExl">
                                                                                
                                                                                <div class="dropdown">
                                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                        <ul class="link-list-plain">
                                                                                            
                                                                                            <li><a href="#" data-toggle="modal" data-target="#modalEditCategory{{$category->id}}">Edit</a></li>
                                                                                            @if($category->num==0)
                                                                                                <li><a href="#" data-toggle="modal" data-target="#modalDeleteCategory{{$category->id}}">Delete</a></li>
                                                                                            @else
                                                                                                <li><a href="#" data-toggle="modal" data-target="#modalDeleteCategory{{$category->id}}" hidden>Delete</a></li>
                                                                                            @endif
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>

                                                                            </td>
                                                                        </tr><!-- tb-tnx-item -->
                                                                        
                                                                        <!-- Modal Content Code -->
                                                                        <div class="modal fade" tabindex="-1" id="modalEditCategory{{$category->id}}">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <em class="icon ni ni-cross"></em>
                                                                                    </a>
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">Update Category</h5>

                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <p>
                                                                                            
                                                                                        </p>
                                                                                        <form action="{{route('update-category')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                            @csrf 
                                                                                            <input type="hidden" name="id" value="{{$category->id}}">  
                                                                                                <div class="form-group">
                                                                                                    <label class="form-label" for="address-l2">Job Type</label>
                                                                                                    <div class="form-control-wrap ">
                                                                                                        <div class="form-control-select">
                                                                                                            <select class="form-control" id="job_type" name="job_type">

                                                                                                                    <option value="jobwall" {{ $category->job_type == "jobwall" ? 'selected' : ''}}>JOBwall</option>
                                                                                                                    <option value="jobdrawer" {{ $category->job_type == "jobdrawer" ? 'selected' : ''}}>JOBdrawer</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div> 

                                                                                                <div class="form-group">
                                                                                                    <label class="form-label" for="default-01">Name</label>
                                                                                                    <div class="form-control-wrap">
                                                                                                        <input type="text" name="name" value="{{$category->name}}" class="form-control" id="name" placeholder="Enter name" required>
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

                                                                                    <div class="modal fade" tabindex="-1" id="modalDeleteCategory{{$category->id}}">
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
                                                                                                        you will not be able to retrieve it
                                                                                                    </p>
                                                                                                    <form action="{{route('delete-category')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                                        @csrf   
                                                                                                            <input type="hidden" name="id" value="{{$category->id}}">
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
                                             
                                                    </div><!-- data-list -->

                                                </div><!-- .nk-block -->
    </div>
    <div class="tab-pane {{ !empty(request()->get('tabName')) && request()->get('tabName') == 'jobdrawer' ? 'active' : '' }}" id="jobdrawer">
                                                <div class="nk-block">
                                                    <div class="nk-data data-list">
                                                        <div class="card-inner p-0">

                                                            <table class="table table-tranx table-dids">

                                                                <tbody>
                                                                    @if(!empty($jobdrawer_categorys))
                                                                        @foreach($jobdrawer_categorys as $category)
                                                                            @csrf
                                                                        <tr class="tb-tnx-item">
                                                                            
                                                                            <td class="tb-tnx-info">
                                                                                <div class="">
                                                                                    <span class="title">{{$category->name}}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="tb-tnx-info">
                                                                                <div class="">
                                                                                    <span class="title">{{$category->num}}</span>
                                                                                </div>
                                                                            </td>
                                                                            
                                                                            <td class="tb-tnx-action noExl">
                                                                                
                                                                                <div class="dropdown">
                                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                        <ul class="link-list-plain">
                                                                                            
                                                                                            <li><a href="#" data-toggle="modal" data-target="#modalEditCategory{{$category->id}}">Edit</a></li>
                                                                                            @if($category->num==0)
                                                                                                <li><a href="#" data-toggle="modal" data-target="#modalDeleteCategory{{$category->id}}">Delete</a></li>
                                                                                            @else
                                                                                                <li><a href="#" data-toggle="modal" data-target="#modalDeleteCategory{{$category->id}}" hidden>Delete</a></li>
                                                                                            @endif
                                                                                            
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>

                                                                            </td>
                                                                        </tr><!-- tb-tnx-item -->
                                                                        
                                                                        <!-- Modal Content Code -->
                                                                        <div class="modal fade" tabindex="-1" id="modalEditCategory{{$category->id}}">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <em class="icon ni ni-cross"></em>
                                                                                    </a>
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">Update Category</h5>

                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <p>
                                                                                            
                                                                                        </p>
                                                                                        <form action="{{route('update-category')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                            @csrf 
                                                                                            <input type="hidden" name="id" value="{{$category->id}}">  
                                                                                                <div class="form-group">
                                                                                                    <label class="form-label" for="address-l2">Job Type</label>
                                                                                                    <div class="form-control-wrap ">
                                                                                                        <div class="form-control-select">
                                                                                                            <select class="form-control" id="job_type" name="job_type">

                                                                                                                    <option value="jobwall" {{ $category->job_type == "jobwall" ? 'selected' : ''}}>JOBwall</option>
                                                                                                                    <option value="jobdrawer" {{ $category->job_type == "jobdrawer" ? 'selected' : ''}}>JOBdrawer</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div> 

                                                                                                <div class="form-group">
                                                                                                    <label class="form-label" for="default-01">Name</label>
                                                                                                    <div class="form-control-wrap">
                                                                                                        <input type="text" name="name" value="{{$category->name}}" class="form-control" id="name" placeholder="Enter name">
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

                                                                                    <div class="modal fade" tabindex="-1" id="modalDeleteCategory{{$category->id}}">
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
                                                                                                        you will not be able to retrieve it
                                                                                                    </p>
                                                                                                    <form action="{{route('delete-category')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                                        @csrf   
                                                                                                            <input type="hidden" name="id" value="{{$category->id}}">
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
                                                    </div><!-- data-list -->

                                                </div><!-- .nk-block -->
    </div>


</div>

                                            </div>

                                        </div><!-- .card-aside-wrap -->
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>

    </div>

                                                            <div class="modal fade" tabindex="-1" id="modalNewCategory">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <em class="icon ni ni-cross"></em>
                                                                        </a>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Create New Category</h5>

                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>
                                                                                
                                                                            </p>
                                                                            <form action="{{route('store-category')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                @csrf  

                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="address-l2">Job Type</label>
                                                                                        <div class="form-control-wrap ">
                                                                                            <div class="form-control-select">
                                                                                                <select class="form-control" id="job_type" name="job_type">

                                                                                                        <option value="jobwall" selected>JOBwall</option>
                                                                                                        <option value="jobdrawer" >JOBdrawer</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                  
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">Name</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" required>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <script>
    function validateSippyPassword() {
        var validator = $("#sippyForm").validate({
            rules: {
                name: {
                    required: true,
                }
            },
            messages: {
                first_name: "Please enter name",
            }
        });
        if(validator.form()){
            $("#sippyForm").submit();
        }
    }

    </script>

<script>

</script>    
@endsection('content')  