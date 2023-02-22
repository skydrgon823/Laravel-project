@extends('layouts.admin_app2')

@section('content')   
<style>
    .row{
        margin:5px;
    }
</style>
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">

        <div class="nk-content-body">
                <div class="nk-chat">
                    <div class="nk-chat-body profile-shown">
                        <div class="nk-chat-head">
                            <ul class="nk-chat-head-info">
                                <li class="nk-chat-head-user">
                                    <div class="user-card">
                                        <div class="user-avatar bg-purple">
                                            <span>{{strtoupper(substr(App\Models\Employee::get_employee_name($message_header->receiver_id), 0, 1))}}{{strtoupper(substr(App\Models\Employee::get_employee_surname($message_header->receiver_id), 0, 1))}}</span>
                                        </div>
                                        <div class="user-info">
                                            <div class="lead-text">{{App\Models\Employee::get_employee_name($message_header->receiver_id)}} {{App\Models\Employee::get_employee_surname($message_header->receiver_id)}}</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <ul class="nk-chat-head-tools">
                                <li class="mr-n1 mr-md-n2"><a href="#" class="btn btn-icon btn-trigger text-primary chat-profile-toggle"><em class="icon ni ni-alert-circle-fill"></em></a></li>
                            </ul>
                        </div><!-- .nk-chat-head -->
                        <div class="nk-chat-panel" data-simplebar>
                        @foreach($messages as $message)
                            @if($message->owner_type == "employee")
                                <div class="chat is-you">
                                    <div class="chat-avatar">
                                        <div class="user-avatar bg-purple">
                                            <span>{{strtoupper(substr(App\Models\Employee::get_employee_name($message_header->receiver_id), 0, 1))}}{{strtoupper(substr(App\Models\Employee::get_employee_surname($message_header->receiver_id), 0, 1))}}</span>
                                        </div>
                                    </div>
                                    <div class="chat-content">
                                        <div class="chat-bubbles">
                                            <div class="chat-bubble">
                                                <div class="chat-msg chat-flex-left"> {{$tr->translate($message->content)}} </div>
                                            </div>
                                        </div>
                                        <ul class="chat-meta">
                                            <!-- <li>Iliash Hossain</li> -->
                                            <li>{{$message->updated_at}}</li>
                                        </ul>
                                    </div>
                                </div>
                            @else
                                @if($message->is_read == 1)
                                    <div class="chat is-me">
                                        <div class="chat-content">
                                            <div class="chat-bubbles">
                                                <div class="chat-bubble">
                                                    <div class="chat-msg chat-flex-right"> {{$tr->translate($message->content)}} </div>
                                                </div>
                                            </div>
                                            <ul class="chat-meta">
                                                <li>READ ON {{$message->updated_at}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <div class="chat is-me">
                                        <div class="chat-content">
                                            <div class="chat-bubbles">
                                                <div class="chat-bubble">
                                                    <div class="chat-msg chat-flex-right" style = "background : red;"> {{$tr->translate($message->content)}} </div>
                                                </div>
                                            </div>
                                            <ul class="chat-meta">
                                                <li>NOT READ</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        </div><!-- .nk-chat-panel -->
                        <form class="nk-chat-editor" action="{{route('store-message')}}" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                            @csrf
                            <div  class="nk-chat-editor-form">
                                <input type="hidden" name="owner_type" value="admin"> 
                                <input type="hidden" name="header_id" value="{{$message_header->id}}"> 
                                <div class="form-control-wrap">
                                    <textarea name = "message" class="form-control form-control-simple no-resize" rows="1" id="default-textarea" placeholder="Type your message..."></textarea>
                                </div>
                            </div>
                            <ul class="nk-chat-editor-tools g-2">
                                <button type = "submit" class="btn btn-round btn-primary btn-icon"><em class="icon ni ni-send-alt"></em></button>
                            </ul>
                        </form><!-- .nk-chat-editor -->
                        <div class="nk-chat-profile visible" data-simplebar>
                            <div class="user-card user-card-s2 my-4">
                                <div class="user-avatar md bg-purple">
                                    <span>{{strtoupper(substr(App\Models\Employee::get_employee_name($message_header->receiver_id), 0, 1))}}{{strtoupper(substr(App\Models\Employee::get_employee_surname($message_header->receiver_id), 0, 1))}}</span>
                                </div>
                                <div class="user-info">
                                    <h5>{{App\Models\Employee::get_employee_name($message_header->receiver_id)}} {{App\Models\Employee::get_employee_surname($message_header->receiver_id)}}</h5>
                                    <!-- <span class="sub-text">Active 35m ago</span> -->
                                </div>
                            </div>
                            <a class="btn btn-success me-2 togglebutton" href="{{route('message-list', [$id, 'it'])}}">Translate to italian</a>
                            <div class="chat-profile">
                                <div class="chat-profile-group">
                                    <a href="#" class="chat-profile-head" data-toggle="collapse" data-target="#chat-settings">
                                        <h6 class="title overline-title">Details</h6>
                                        <span class="indicator-icon"><em class="icon ni ni-chevron-down"></em></span>
                                    </a>
                                    <div class="chat-profile-body collapse show" id="chat-settings">
                                        <div class="chat-profile-body-inner">
                                            <ul class="chat-profile-settings">
                                                @if($message_header->category_id!=0)
                                                <li>
                                                    <div class="chat-option-link">
                                                        <div>
                                                            <span class="lead-text">Category</span>
                                                            <span class="sub-text">{{App\Models\Category::get_category_name($message_header->category_id)}}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                <li>
                                                    <div class="chat-option-link">
                                                        <div>
                                                            <span class="lead-text">title</span>
                                                            <span class="sub-text">{{$tr->translate($message_header->title)}}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="chat-option-link">
                                                        <div>
                                                            <span class="lead-text">object</span>
                                                            <span class="sub-text">{{$tr->translate($message_header->object)}}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- .chat-profile-group -->
                                <div class="chat-profile-group">
                                    <a href="#" class="chat-profile-head" data-toggle="collapse" data-target="#chat-photos">
                                        <h6 class="title overline-title">Shared Attachments</h6>
                                        <span class="indicator-icon"><em class="icon ni ni-chevron-down"></em></span>
                                    </a>
                                    <div class="chat-profile-body collapse show" id="chat-photos">
                                        <div class="chat-profile-body-inner">
                                            <ul class="chat-profile-settings">
                                                @foreach($message_attachments as $attachment)
                                                <li>
                                                    <a class="chat-option-link" href="{{route('downloadfile',$attachment->id)}}">
                                                        <em class="icon icon-circle bg-light ni ni-file"></em>
                                                        <div>
                                                            <span class="lead-text">{{$attachment->name}}</span>
                                                            @if($attachment->is_sign ==1)
                                                                <span class="sub-text">{{date_format(date_create($attachment->updated_at),'d/m/Y H:i')}}  signed</span>
                                                            @else
                                                                <span class="sub-text">unsigned</span>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- .chat-profile-group -->
                            </div> <!-- .chat-profile -->
                        </div><!-- .nk-chat-profile -->
                    </div><!-- .nk-chat-body -->
                </div><!-- .nk-chat -->
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

    // $(".togglebutton").click(function(e){
    //     e.preventDefault();
    //     // alert(222);

    // });    

    $("body").on("click",".remove_attach",function(){
        attach_number--;
        var parent = this.parentNode;
        console.log(parent);
        $(this).parents(".file-item").remove();
    });
});    
</script>     
                                                    
@endsection('content')  