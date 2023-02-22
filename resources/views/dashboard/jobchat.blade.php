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
                                            <h3 class="nk-block-title page-title">Chat List</h3>
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
                                        <div class="" style="text-align: right;"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewChat">New Message</button></div>
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
                                                                        <span>title</span>
                                                                    </span>
                                                                </th>
                                                                <th class="nk-tb-col tb-col-md">
                                                                    <span class="sub-text">
                                                                        <span>Object</span>
                                                                    </span>
                                                                </th>
                                                                
                                                                <th class="nk-tb-col tb-col-md">
                                                                    <span class="sub-text">
                                                                        <span>Last Message</span>
                                                                    </span>
                                                                </th>
                                                                
                                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(!empty($message_headers))
                                                                @foreach($message_headers as $message_header)
                                                                    @csrf
                                                                <tr class="nk-tb-item">
                                                                    <td class="nk-tb-col tb-col-mb">
                                                                        <span class="tb-amount">{{$message_header->no}}</span>
                                                                    </td>
                                                                    <td class="nk-tb-col nk-tb-col-mb">
                                                                        <div class="user-card">
                                                                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                                                <span>{{strtoupper(substr(App\Models\Employee::get_employee_name($message_header->receiver_id), 0, 1))}}{{strtoupper(substr(App\Models\Employee::get_employee_surname($message_header->receiver_id), 0, 1))}}</span>
                                                                            </div>
                                                                            <a class="user-info" href="{{route('message-list', [$message_header->id, 'en'])}}">
                                                                                <span class="tb-lead">{{App\Models\Employee::get_employee_name($message_header->receiver_id)}} {{App\Models\Employee::get_employee_surname($message_header->receiver_id)}}<span class="dot dot-success d-md-none ml-1"></span></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>

                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <div class = "">
                                                                            <a href="{{route('message-list', [$message_header->id, 'en'])}}" ><span style = "color:#364a63">{{ $message_header->title }}</span></a>
                                                                        </div>
                                                                    </td>
                                                                    
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <div class = "">
                                                                            <a href="{{route('message-list', [$message_header->id, 'en'])}}" ><span style = "color:#364a63">{{ $message_header->object }}</span></a>
                                                                        </div>
                                                                    </td>
                                                                    
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <div class = "">    
                                                                            <a href="{{route('message-list', [$message_header->id, 'en'])}}" ><span style = "color:#364a63">{{ $message_header->updated_at }}</span></a>
                                                                        </div>
                                                                    </td>

                                                                    <td class="nk-tb-col nk-tb-col-tools">
                                                                        <div class="">
                                                                        @if(App\Models\Message::get_read_stataus($message_header->id)->is_read==1)
                                                                                <div class="unread"><span class="badge badge-lg badge-primary">{{ App\Models\Message::get_message_number($message_header->id) }}</span></div>
                                                                            @else
                                                                                @if(App\Models\Message::get_read_stataus($message_header->id)->owner_type=='employee')
                                                                                <div class="unread"><span class="badge badge-lg badge-danger">{{ App\Models\Message::get_message_number($message_header->id) }}</span></div>
                                                                                @else 
                                                                                <div class="unread"><span class="badge badge-lg badge-primary">{{ App\Models\Message::get_message_number($message_header->id) }}</span></div>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                                </form>
                                                            @endif
                                                        </tbody>
                                                    </table>


                                                

                                                </div><!-- .card-inner -->
                                            </div>                                                                               
                                        </div><!-- .card-inner-group -->
                                        
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>

                                                            <div class="modal fade" tabindex="-1" id="modalNewChat">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <em class="icon ni ni-cross"></em>
                                                                        </a>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Create New Message</h5>

                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>
                                                                                
                                                                            </p>
                                                                            <form action="{{route('store-message-header')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                @csrf   
                                                                                <input type="hidden" name="owner_type" value="admin" >
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="address-l2">Employee</label>
                                                                                        <div class="form-control-wrap ">
                                                                                            <select  id="multiple-checkboxes" multiple="multiple" tabindex="-1" name="employee[]" data-placeholder="Select Employees" required>
                                                                                                
                                                                                                @foreach($employees as $employee)
                                                                                                    <option value="{{$employee->id}}" >{{ $employee->name }} {{ $employee->surname }}</option>
                                                                                                @endforeach
                                                                                                
                                                                                            </select>
                                                                                        </div>
                                                                                    </div> 
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">Title</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text" name="title" class="form-control" id="title" placeholder="Enter title" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class = "form-group">
                                                                                        <div class="custom-control custom-checkbox">
                                                                                            <input type="checkbox" class="custom-control-input" name="urgent" id="fv-com-urgent">
                                                                                            <label class="custom-control-label" for="fv-com-urgent">URGENT</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">Object</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text" name="object" class="form-control" id="object" placeholder="Enter object" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="default-01">Message</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <textarea name="message" class="form-control" required></textarea> 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="address-l2">Category</label>
                                                                                        <div class="form-control-wrap ">
                                                                                            <div class="form-control-select">
                                                                                                <select class="form-control" id="category" name="category">

                                                                                                        <option value="0" selected>select Category</option>
                                                                                                        <optgroup label="Jobwall">
                                                                                                            @foreach($jobwall_categorys as $jobwall_category)
                                                                                                                <option value="{{$jobwall_category->id}}" >{{ $jobwall_category->name }}</option>
                                                                                                            @endforeach
                                                                                                        </optgroup>
                                                                                                        <optgroup label="Jobdrawer">
                                                                                                            @foreach($jobdrawer_categorys as $jobdrawer_category)
                                                                                                                <option value="{{$jobdrawer_category->id}}" >{{ $jobdrawer_category->name }}</option>
                                                                                                            @endforeach              
                                                                                                        </optgroup>    
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div> 

                                                                                    <div class="attach_list">
                                                                                        <div class="form-group file-item">
                                                                                            <div class="form-control-wrap" style="padding-bottom: 5px;">
                                                                                                <div class="custom-file">
                                                                                                    <input type="file" class="custom-file-input" name="attached[]">
                                                                                                    <label class="custom-file-label" for="customFile">Attached 1</label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="custom-control custom-radio" style = "padding-right:20px;">
                                                                                                    <span>signature</span>
                                                                                            </div>                            
                                                                                            <div class="custom-control custom-radio" style = "padding-right:20px;">
                                                                                                <input type="radio" id="attach_sign_1_yes" name="attach_sign_1" value="yes" class="custom-control-input" checked>
                                                                                                <label class="custom-control-label" for="attach_sign_1_yes">Yes</label>
                                                                                            </div>
                                                                                            <div class="custom-control custom-radio" style = "padding-right:20px;">
                                                                                                <input type="radio" id="attach_sign_1_no" name="attach_sign_1" value="no" class="custom-control-input">
                                                                                                <label class="custom-control-label" for="attach_sign_1_no">No</label>
                                                                                            </div>                        
                                                                                            <div class="custom-control float-right">
                                                                                                <button type = "button" class="remove_attach btn btn-primary">Remove</button>
                                                                                            </div>                        
                                                                                        </div>  
                                                                                    </div>
                                                                                    <div class="form-group" style="padding-top: 10px;">
                                                                                        <button type = "button" class="add_attach btn btn-primary">Add attachment +</button>
                                                                                    </div>


                                                                                    <span class="form-text text-muted">Max file size is 5MB and max number of files is 5.</span>


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


<script src="{{ asset('admin/js/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/js/bootstrap-multiselect.js') }}"></script>
<script src="{{ asset('admin/js/popper.js') }}"></script>

<script>
$(document).ready(function() {
    var attach_number = 1;
    var max_attach = 5;
    var wrapper         = $(".attach_list");

    $(".add_attach").click(function(e){
        e.preventDefault();
        if(attach_number < max_attach){
            attach_number++;
            var radio_yes_id = 'attach_sign_'+attach_number+'_yes';
            var radio_no_id = 'attach_sign_'+attach_number+'_no';
            var radio_name = 'attach_sign_'+attach_number;
            var html = '<div class="form-group file-item">';
            html +=  '<div class="form-control-wrap" style="padding-bottom: 5px;">';
            html += '<div class="custom-file">';
            html += '<input type="file" class="custom-file-input" name="attached[]" id="">';
            html += '<label class="custom-file-label" for="customFile">Attached ' + attach_number +'</label>';
            html += '</div></div>';
            html += '<div class="custom-control custom-radio" style = "padding-right:20px;"><span>signature</span></div>';
            html += '<div class="custom-control custom-radio" style = "padding-right:20px;"><input type="radio" id="'+radio_yes_id+'" name="'+radio_name+'" class="custom-control-input" checked><label class="custom-control-label" for="'+radio_yes_id+'">Yes</label></div>';
            html += '<div class="custom-control custom-radio" style = "padding-right:20px;"><input type="radio" id="'+radio_no_id+'" name="'+radio_name+'" class="custom-control-input"><label class="custom-control-label" for="'+radio_no_id+'">No</label></div>';
            html += '<div class="custom-control float-right"><button type = "button" class="remove_attach btn btn-primary">Remove</button></div></div>';
           $(wrapper).append(html); //add input box
        }
  else
  {
  alert('You Reached the limits')
  }
    });    

    $("body").on("click",".remove_attach",function(){
        attach_number--;
        var parent = this.parentNode;
        console.log(parent);
        $(this).parents(".file-item").remove();
    });
});    
(function($) {

"use strict";

 $(document).ready(function() {
    $('#multiple-checkboxes').multiselect({
      includeSelectAllOption: true,
    });
});
 
})(jQuery);

</script>                                                        
@endsection('content')  