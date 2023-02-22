<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Message;
use App\Models\Message_attachment;
use App\Models\Message_header;
use App\Models\Usercode;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Models\buy_rates_australia;
use App\Models\buy_rates_newzealand;
use Session;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;
use App\Mail\EmailAllocateDids; 
use App\Mail\EmailDeleteDid;
use Illuminate\Support\Facades\DB;
use File;
use URL;
use Response;


class ApiController extends Controller
{

    public function index(Request $request){
        $current_user = auth()->user();
        return view('dashboard.index', compact('current_user'));
    }

    public function employee(Request $request){
                
        $current_user = auth()->user();
        $employees = Employee::get();
        return response()->json([
              'data' => $employees
             ], 200);
    }   

    public function store_employee(Request $request){
        $current_user = auth()->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],            
        ]);

                    $data = [
                        "admin_id"          => $current_user->id,
                        'name'              => $request->name,
                        'surname'              => $request->surname,
                        'email'              => $request->email,
                        'phone'           => $request->phone,
                    ];
        $employee = Employee::create($data);
        if ($employee) {
              return response()->json([
                        'data' => [
                            'type' => 'employee',
                            'message' => 'Success',
                            'employee' => $employee
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'employee',
                        'message' => 'Fail'
                    ], 400);
             }               	
    } 

    public function update_employee(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],            
        ]);

                    $data = [
                        'name'              => $request->name,
                        'surname'              => $request->surname,
                        'email'              => $request->email,
                        'phone'           => $request->phone,
                    ];
        $employee = Employee::whereId($request->id)->update($data);
        if ($employee) {
              return response()->json([
                        'data' => [
                            'type' => 'employee',
                            'message' => 'Success',
                            'employee' => $employee
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'employee',
                        'message' => 'Fail'
                    ], 400);
             }                 	
    }

    public function delete_employee(Request $request){

        $employee = Employee::find($request->id);

        if ($employee) {
                $employee->delete();
                return response()->json(['data' => [
                            'type' => 'category',
                            'message' => 'Success',
                        ]
                    ], 204);
                } else {
                    return response()->json([
                        'type' => 'employee',
                        'message' => 'Not Found'
                    ], 404);
        }   
    }

    public function category(Request $request){
                
        $current_user = auth()->user();
        $jobwall_categorys = Category::where('job_type','jobwall')->get();
        $jobdrawer_categorys = Category::where('job_type','jobdrawer')->get();
        return response()->json([
              'data' => [
                            'jobwall_categorys' => $jobwall_categorys,
                            'jobdrawer_categorys' => $jobdrawer_categorys
                        ]
             ], 200);
    }   

    public function store_category(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255'],           
        ]);

                    $data = [
                        'name'              => $request->name,
                        'job_type'              => $request->job_type,
                    ];
        $category = Category::create($data);
        if ($category) {
              return response()->json([
                        'data' => [
                            'type' => 'category',
                            'message' => 'Success',
                            'category' => $category
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'category',
                        'message' => 'Fail'
                    ], 400);
             }                  
    } 

    public function update_category(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255'],         
        ]);

                    $data = [
                        'name'              => $request->name,
                        'job_type'              => $request->job_type,
                    ];
        $category = Category::whereId($request->id)->update($data);
        if ($category) {
              return response()->json([
                        'data' => [
                            'type' => 'category',
                            'message' => 'Success',
                            'category' => $category
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'category',
                        'message' => 'Fail'
                    ], 400);
             }                    
    }

    public function delete_category(Request $request){

        $category = Category::find($request->id);
        if ($category) {
                $category->delete();
                return response()->json(['data' => [
                            'type' => 'category',
                            'message' => 'Success',
                        ]
                    ], 204);
                } else {
                    return response()->json([
                        'type' => 'category',
                        'message' => 'Not Found'
                    ], 404);
        }    
    }    

    public function dashboard(Request $request){
        $request_data = $request->all();
        $message_headers = Message_header::where('creator_id', $request_data['employee_id'])->where('owner_type','admin')->get();
        
        $joballdata = [];
        $jobdwalls = [];
        $jobdrawers = [];
        $index_all = 0;$index_wall = 0;$index_drawer = 0;

        
        $message_headers_byAdmin = Message_header::where('receiver_id', $request_data['employee_id'])->where('owner_type','admin')->get();
        foreach($message_headers_byAdmin as $message_header_byAdmin){
            $is_read = Message::where('header_id',$message_header_byAdmin['id'])->orderBy('id', 'desc')->first();
            if($is_read!=null){
                if($is_read->is_read == 1) {
                    $color = 'blue';
                } else {
                    if($is_read->owner_type=='admin'){
                        $color = 'red';
                    } else {
                        $color = 'blue';
                    }
                }
            }
            if($index_all< 4 ) {
                $joballdata []= array(
                    'id'=>$message_header_byAdmin->id,
                    'employee_name'=>Employee::get_employee_name($message_header_byAdmin->receiver_id),
                    'title'=>$message_header_byAdmin->title,
                    'object'=>$message_header_byAdmin->object,
                    'msg_num'=>count(Message::where('header_id',$message_header_byAdmin['id'])->get()),
                    'color'=>$color,
                    'is_read'=>0,
                );
                $index_all++;
            }
        }

        $message_headers_byEmployee = Message_header::where('creator_id', $request_data['employee_id'])->where('owner_type','employee')->get();
        foreach($message_headers_byEmployee as $message_header_byEmployee){
            $is_read = Message::where('header_id',$message_header_byEmployee['id'])->orderBy('id', 'desc')->first();
            if($is_read!=null){
                if($is_read->is_read == 1) {
                    $color = 'blue';
                } else {
                    if($is_read->owner_type=='admin'){
                        $color = 'red';
                    } else {
                        $color = 'blue';
                    }
                }
            }
            if($index_all< 4) {
                $joballdata []= array(
                    'id'=>$message_header_byEmployee->id,
                    'employee_name'=>Employee::get_employee_name($message_header_byEmployee->creator_id),
                    'title'=>$message_header_byEmployee->title,
                    'object'=>$message_header_byEmployee->object,
                    'msg_num'=>count(Message::where('header_id',$message_header_byEmployee['id'])->get()),
                    'color'=>$color,
                    'is_read'=>0,
                );
                $index_all++;
            }
        }
            
        


        foreach($message_headers as $message_header){
            $is_read = Message::where('header_id',$message_header['id'])->orderBy('id', 'desc')->first();
            if($is_read->is_read == 1) {
                $color = 'blue';
            } else {
                if($is_read->owner_type=='admin'){
                    $color = 'red';
                } else {
                    $color = 'blue';
                }
            }
            
            if($index_wall<2) {
                $color = 'blue';
                if(Category::where('id',$message_header->category_id)->first()->job_type=='jobwall'){
                    $message_attachments = Message_attachment::where('header_id', $message_header->id)->get();
                    
                    $color = 'red';
                    $flag = false;
                    foreach($message_attachments as $message_attachment){
                        if($message_attachment->is_sign == 0){
                            $flag = true;
                        }
                    }
                    if(!$flag) $color = 'blue';
                    $jobwalls []= array(
                        'id'=>$message_header->id,
                        'employee_name'=>Employee::get_employee_name($message_header->receiver_id),
                        'title'=>$message_header->title,
                        'object'=>$message_header->object,
                        'attach_num'=>count(Message_attachment::where('header_id',$message_header->id)->get()),
                        'color'=>$color,
                    );
                    $index_wall++;
                }
                
            }
            if($index_drawer<2) {
                $color = 'blue';
                if(Category::find($message_header->category_id)->job_type=='jobdrawer'){
                    $message_attachments = Message_attachment::where('header_id', $message_header->id)->get();
                    $color = 'red';
                    $flag = false;
                    foreach($message_attachments as $message_attachment){
                        if($message_attachment->is_sign == 0){
                            $flag = true;
                        }
                    }
                    if(!$flag) $color = 'blue';
                    $jobdrawers []= array(
                        'id'=>$message_header->id,
                        'employee_name'=>Employee::get_employee_name($message_header->receiver_id),
                        'title'=>$message_header->title,
                        'object'=>$message_header->object,
                        'attach_num'=>count(Message_attachment::where('header_id',$message_header->id)->get()),
                        'color'=>$color,
                        'is_read'=>0,
                    );
                    $index_drawer++;
                }
            }
        }
       
        return response()->json([
            'is_success'=>true,
            'message'=>'sucessfully update',
            'data' => [
                          'joballdata' => $joballdata,
                          'jobwalldata' => $jobwalls,
                          'jobdrawerdata' => $jobdrawers,
                      ]
           ], 200);
    }

    public function joballchat(Request $request){
        $request_data = $request->all();
        $message_headers_byAdmin = Message_header::where('receiver_id', $request_data['employee_id'])->where('owner_type','admin')->get();
        $jsondata = [];
        foreach($message_headers_byAdmin as $message_header_byAdmin){
            $is_read = Message::where('header_id',$message_header_byAdmin['id'])->orderBy('id', 'desc')->first();
            if($is_read!=null){
                if($is_read->is_read == 1) {
                    $color = 'blue';
                } else {
                    if($is_read->owner_type=='admin'){
                        $color = 'red';
                    } else {
                        $color = 'blue';
                    }
                }
            }
            
            $joballdata []= array(
                'id'=>$message_header_byAdmin->id,
                'employee_name'=>Employee::get_employee_name($message_header_byAdmin->receiver_id),
                'title'=>$message_header_byAdmin->title,
                'object'=>$message_header_byAdmin->object,
                'msg_num'=>count(Message::where('header_id',$message_header_byAdmin['id'])->get()),
                'color'=>$color,
                'is_read'=>0,
            );
        }

        $message_headers_byEmployee = Message_header::where('creator_id', $request_data['employee_id'])->where('owner_type','employee')->get();
        foreach($message_headers_byEmployee as $message_header_byEmployee){
            $is_read = Message::where('header_id',$message_header_byEmployee['id'])->orderBy('id', 'desc')->first();
            if($is_read!=null){
                if($is_read->is_read == 1) {
                    $color = 'blue';
                } else {
                    if($is_read->owner_type=='admin'){
                        $color = 'red';
                    } else {
                        $color = 'blue';
                    }
                }
            }
            
            $joballdata []= array(
                'id'=>$message_header_byEmployee->id,
                'employee_name'=>Employee::get_employee_name($message_header_byEmployee->creator_id),
                'title'=>$message_header_byEmployee->title,
                'object'=>$message_header_byEmployee->object,
                'msg_num'=>count(Message::where('header_id',$message_header_byEmployee['id'])->get()),
                'color'=>$color,
                'is_read'=>0,
            );
        }
       
        return response()->json([
            'is_success'=>true,
            'message'=>'sucessfully update',
            'data' => [
                          'joballdata' => $joballdata,
                      ]
           ], 200);
    }

    public function jobdrawer(Request $request){
        $request_data = $request->all();
        $message_headers = Message_header::where('creator_id', $request_data['employee_id'])->where('owner_type','admin')->get();
        $jobdrawers = [];
        foreach($message_headers as $message_header){
            if(Category::where('id',$message_header->category_id)->first()->job_type=='jobdrawer'){
                $is_read = Message::where('header_id',$message_header->id)->orderBy('id', 'desc')->first();
                if($is_read->is_read == 0) {
                    $color = 'red';
                } else if ($is_read->is_read == 1 && (Message_attachment::where('header_id', $message_header->id)->latest('updated_at')->first())) {
                    if(Message_attachment::where('header_id', $message_header->id)->latest('updated_at')->first()->is_sign == 0){
                        $color = 'red';
                    } else {
                        $color = 'blue';
                    }
                } else {
                    $color = 'blue';
                }
                
                $jobdrawers []= array(
                    'id'=>$message_header->id,
                    'employee_name'=>Employee::get_employee_name($message_header->receiver_id),
                    'title'=>$message_header->title,
                    'object'=>$message_header->object,
                    'attach_num'=>count(Message_attachment::where('header_id',$message_header->id)->get()),
                    'color'=>$color,
                    'is_read'=>0,
                );
            }
        }
       
        return response()->json([
            'is_success'=>true,
            'message'=>'sucessfully update',
            'data' => [
                          'jobdrawerdata' => $jobdrawers,
                      ]
           ], 200);
    }

    public function jobwall(Request $request){
        $request_data = $request->all();
        $message_headers = Message_header::where('creator_id', $request_data['employee_id'])->where('owner_type','admin')->get();
        $jobwalls = [];
        foreach($message_headers as $message_header){
            if(Category::where('id',$message_header->category_id)->first()->job_type=='jobwall'){
                $is_read = Message::where('header_id',$message_header->id)->orderBy('id', 'desc')->first();
                if($is_read->is_read == 0) {
                    $color = 'red';
                } else if ($is_read->is_read == 1 && (Message_attachment::where('header_id', $message_header->id)->latest('updated_at')->first())) {
                    if(Message_attachment::where('header_id', $message_header->id)->latest('updated_at')->first()->is_sign == 0){
                        $color = 'red';
                    } else {
                        $color = 'blue';
                    }
                } else {
                    $color = 'blue';
                }
                
                $jobwalls []= array(
                    'id'=>$message_header->id,
                    'employee_name'=>Employee::get_employee_name($message_header->receiver_id),
                    'title'=>$message_header->title,
                    'object'=>$message_header->object,
                    'attach_num'=>count(Message_attachment::where('header_id',$message_header->id)->get()),
                    'color'=>$color,
                );
            }
        }
       
        return response()->json([
            'is_success'=>true,
            'message'=>'sucessfully update',
            'data' => [
                          'jobwalldata' => $jobwalls,
                      ]
           ], 200);
    }

    public function message_list(Request $request){
        $request_data = $request->all();
        Message_header::where('id',$request_data['id'])->update(['updated_at'=>date("Y-m-d H:i:s ")]);
        $message_header = Message_header::where('id', $request_data['id'])->first();
        $messages = Message::where('header_id',$request_data['id'])->get();
        $message_attachments = Message_attachment::where('header_id', $request_data['id'])->get();

        $data = [
            'is_read'  => 1,
        ];
        Message::where('header_id',$request_data['id'])->where('owner_type','admin')->where('is_read',0)->update($data);
        
        foreach($messages as $message){
            if($message->owner_type=='admin'){
                $type = 'received';
            } else {
                $type = 'sent';
            }
            $messages_data[] = array(
                'id'=>$message->id,
                'creator'=>Employee::get_employee_name($message->creator_id),
                'text'=>$message->content,
                'type'=>$type,
                'is_read'=>$message->is_read,
                'created_at'=>date_format(date_create($message->created_at),'d/m/Y H:i'),
                'updated_at'=>date_format(date_create($message->updated_at),'d/m/Y H:i'),
            );
        }
        $message_attachments_data=[];
        foreach($message_attachments as $message_attachment){
            $message_attachments_data[] = array(
                'id'=>$message_attachment->id,
                'name'=>$message_attachment->name,
                'path'=>$message_attachment->path,
                'is_sign'=>$message_attachment->is_sign,
                'created_at'=>date_format(date_create($message_attachment->created_at),'d/m/Y H:i'),
                'updated_at'=>date_format(date_create($message_attachment->updated_at),'d/m/Y H:i'),
            );
        }

        return response()->json([
            'is_success'=>true,
            'message'=>'sucessfully update',
            'data' => [
                            'category_name'=>Category::get_category_name($message_header->category_id),
                            'title'=>$message_header->title,
                            'object'=>$message_header->object,
                            'created_at'=>date_format(date_create($message_header->created_at),'d/m/Y H:i'),
                            'updated_at'=>date_format(date_create($message_header->updated_at),'d/m/Y H:i'),
                            'owner_type'=>$message_header->owner_type,
                            'msg_data'=>$messages_data,
                            'msg_attachments'=>$message_attachments_data,          
                      ]
           ], 200);
    }   

    public function attachment_list(Request $request){
        $request_data = $request->all();
        Message_header::where('id',$request_data['id'])->update(['updated_at'=>date("Y-m-d H:i:s ")]);
        $message_header = Message_header::where('id', $request_data['id'])->first();
        $message_attachments = Message_attachment::where('header_id', $request_data['id'])->get();


        $message_attachments_data=[];
        foreach($message_attachments as $message_attachment){
            $message_attachments_data[] = array(
                'id'=>$message_attachment->id,
                'name'=>$message_attachment->name,
                'path'=>$message_attachment->path,
                'is_sign'=>$message_attachment->is_sign,
                'created_at'=>date_format(date_create($message_attachment->created_at),'d/m/Y H:i'),
                'updated_at'=>date_format(date_create($message_attachment->updated_at),'d/m/Y H:i'),
            );
        }

        return response()->json([
            'is_success'=>true,
            'message'=>'sucessfully update',
            'data' => [
                            'title'=>$message_header->title,
                            'object'=>$message_header->object,
                            'created_at'=>date_format(date_create($message_header->created_at),'d/m/Y H:i'),
                            'updated_at'=>date_format(date_create($message_header->updated_at),'d/m/Y H:i'),
                            'owner_type'=>$message_header->owner_type,
                            'attachments'=>$message_attachments_data,          
                      ]
           ], 200);
    }   

    public function send_code_signattachment(Request $request){

        $employee = Employee::where('id',$request->employee_id)->first();
        $code = Usercode::where('employee_id',$employee->id)->orderBy('updated_at', 'desc')->first();

        $client = new Client();
        $res = $client->request('POST', 'https://app.whatsjob.it/send_sms_api.php', [
            'form_params' => [
                'usr' => 'whatsjob_sms_API',
                'pwd' => 'a8534JHGu99$&sx6sbcF93mjfkjf478y&%hnc',
                'txt' =>  $code->code,
                'dest' => $employee->phone,               
            ]
        ]);

        
        if($res) {
            return response()->json([
                'is_success'=>true,
                'message' => 'Sent Successfull',
                'data' => [
                    'code'     => $code->code,

                ]
               ], 200);

        } else {
            return response()->json([
                'is_success'=>false,
                'message' => 'Try again!',
                'data' => [
                ]
            ], 200);
        }

        // $request_data = $request->all();
        // //is_read =1;
        // $data = [
        //     'is_sign'              => 1,
        // ];
        // Message_attachment::where('id',$request_data['id'])->update($data);
        
        // return response()->json([
        //     'is_success'=>true,
        //     'message'=>'sucessfully update',
        //     'data'=>[
        //     ]
        // ], 200);  
    }  

    public function verify_code_signattachment(Request $request){

        $code = Usercode::where('employee_id',$request->employee_id)->orderBy('updated_at', 'desc')->first();
        if($code->code == $request->code) {
            $data = [
                'is_sign'              => 1,
            ];
            Message_attachment::where('id',$request->id)->update($data);
            return response()->json([
                'is_success'=>true,
                'message' => 'Sign Successfull',
                'data' => [
                ]
               ], 200);

        } else {
            return response()->json([
                'is_success'=>false,
                'message' => 'Try again!',
                'data' => [
                ]
            ], 200);
        }

    }  

    public function send_newmsg(Request $request){

        $data = [
            'title'              => $request['text'],
            'object'             => $request['object'],
            'creator_id'         => $request['creator_id'],
            'receiver_id'        => $request['receiver_id'],     
            'category_id'        => 0,
            'owner_type'         => $request['owner_type'],                   
            ]; 
            $employee_name = Employee::find($request['creator_id'])->name;
            $message_header = Message_header::create($data);
            if($message_header){
                $issuccess = true;
                $msg = 'Success Create Message!';
                for($key=0;$key<$request['attachnum'];$key++){

                if($request->hasFile('attached_'.$key))
                {
                    $allowedfileExtension=['pdf','jpg','png','docx'];
                    $file = $request->file('attached_'.$key);
                    $no = $key+1;
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $tempPath = $file->getRealPath();
                    $fileSize = $file->getSize();
                    $mimeType = $file->getMimeType();
                    // Valid File Extensions
                    $valid_extension = array("pdf","docx","jpg" , "jpeg" , "jfif" , "pjpeg" , "pjp","png");
                    // 2MB in Bytes
                    $maxFileSize = 10097152; 
                    // Check file extension
                    if(in_array(strtolower($extension),$valid_extension)){

                        // Check file size
                        if($fileSize <= $maxFileSize){
                            $path = 'uploads/others/';
                            if(!File::isDirectory(public_path($path))){
                                File::makeDirectory($path, 0777, true, true);
                            }
                            
                            $file_name = $message_header->id.'_'.date('dmYHis').'.'.$extension;
                            $file_name = preg_replace('/\s+/', '', $file_name);
                            
                            //remove image before upload
                            $file_path = $file->move(public_path($path), $file_name);
                            $file_path = $path.'/'.$file_name;
                            // var_dump('host',URL::to('/').'/'.$path.'/'.$file_name);

                            $radio_name = 'attach_sign_'.$no;
                            // $radio_name = 'attach_sign_'.$no;

                            $data_message_attachment = [
                                'name'   =>  $file_name,
                                'path'   =>  $path.'/'.$file_name,
                                'header_id'   =>  $message_header->id,
                                'is_sign' => $request->$radio_name == 'yes'? 1 : 0,
                            ];
                            Message_attachment::create($data_message_attachment);
                            $issuccess = true;
                            $msg = 'Success Create Message!';

                        }else{
                            $issuccess = false;
                            $msg = 'File too large. File must be less than 2MB.';
                        }
                        $issuccess = true;
                    }
                    else{
                        $issuccess = false;
                        $msg = 'Invalid File Extension.';
                    } 
                }else{
                    $issuccess = false;
                    $msg = 'upload file';
                }
            } 
         
        }
        if($issuccess) {
            return response()->json([
                'is_success'=>true,
                'message'=>$msg,
                'data' => [
                          ]
               ], 200);
            } else {
            return response()->json([
                'is_success'=>false,
                'message'=>$msg,
                'data' => [
                          ]
               ], 200);
        }
    }

    public function store_message(Request $request){
        $data = [
            'content'           => $request->content,  
            'owner_type'        => $request->owner_type,
            'header_id'         => $request->header_id,
            'creator_id'        => $request->creator_id,     
            'is_read'           => $request->is_read,             
        ];
        $createMessage =  Message::create($data);               
        if($createMessage){
            return response()->json([
                'is_success'=>true,
                'message' => 'Sent Message to Admin',
                'data'=>[
                ]
            ], 200);    
        } else {
            return response()->json([
                'is_success'=>false,
                'message' => 'Dont Sent Message to Admin',
                'data'=>[
                ]
            ], 200);    
        }
    }

    public function send_regccode(Request $request){
        $existccode = Usercode::where('code',$request->ccode)->first();
        if($existccode){
            $existcompany = User::where('id',$existccode->admin_id)->first();

                return response()->json([
                    'is_success'=>true,
                    'message' => 'Company Code exist, please continue to register your account!',
                    'data'=>[
                        'cname'    => $existcompany->company,
                        'admin_id' => $existcompany->id,
                    ]
                ], 200);
                
            } else {
                
                return response()->json([
                    'is_success'=>false,
                    'message' => 'Company Code doesnt exist, please enter your correct company code again!',
                    'data'=>[
                    ]
                ], 200);
        }
    }

    public function send_regsms(Request $request){
        $existphone = Employee::where('phone',$request->phone)->first();
        $phone = $request->phone;
        
        if($existphone){
                return response()->json([
                    'is_success'=>false,
                    'message' => 'The Phone you entered is already assigned to an account.  You must either enter a new phone or login to your existing account.',
                    'data'=>[
                    ]
                ], 200);
            
        } 

        $existemail = Employee::where('email',$request->email)->first();
        
        if($existemail){
                return response()->json([
                    'is_success'=>false,
                    'message' => 'The Email you entered is already assigned to an account.  You must either enter a new Email or login to your existing account.',
                    'data'=>[
                    ]
                ], 200);
            
        } 

        $code = Usercode::whereNull('employee_id')->first();
        if($code == null){
            return response()->json([
                'is_success'=>false,
                'message' => 'Company Code donest exist, You cant register!',
                'data'=>[
                ]
            ], 200);
        }
        
        
        $client = new Client();
        $res = $client->request('POST', 'https://app.whatsjob.it/send_sms_api.php', [
            'form_params' => [
                'usr' => 'whatsjob_sms_API',
                'pwd' => 'a8534JHGu99$&sx6sbcF93mjfkjf478y&%hnc',
                'txt' =>  $code->code,
                'dest' => $phone,               
                ]
            ]);
            // $body = $res->getBody()->getContents(); 

            // return response()->json([
            //     'data' => $body
            //    ], $res->getStatusCode());
            $randonid = random_int(1000, 9999);
            if($res->getStatusCode()==200){
                Usercode::whereId($code->id)->update(array(
                    'employee_id' => $randonid,
                    'is_used' => 0,
                ));
            
            return response()->json([
                'is_success'=>true,
                'message'=>'Sent SMS!',
                'data' => [
                    'codeid'=> $randonid,
                    'code' => $code['code'],
                ]
                ], 200);
            } else {
                return response()->json([
                    'is_success'=>false,
                    'message'=>'Not Sent SMS!',
                    'data' => [
                        'codeid'=> $randonid,
                        'code'  => $code['code'],
                    ]
                    ], 200);
        }
    }

    public function send_regcode(Request $request){
        $code = Usercode::where('employee_id',$request->codeid)->orderBy('updated_at', 'desc')->first();
        if($code->code == $request->code) {
            $data = [
                'admin_id'           => $request->admin_id,
                'name'               => $request->name,
                'surname'            => $request->surname,
                'email'              => $request->email,
                'phone'              => $request->phone,
            ];
            $employee = Employee::create($data);
            Usercode::whereId($code->id)->update(array(
                'employee_id' => $employee->id,
                'is_used' => 1,
            ));

            return response()->json([
                'is_success'=>true,
                'message' => 'Register Successfull',
                'data' => [
                ]
               ], 200);

        } else {
            return response()->json([
                'is_success'=>false,
                'message' => 'Try again!',
                'data' => [
                ]
               ], 200);
        }
    }

    public function send_loginsms(Request $request){
        $phone = $request->phone;

        $employee = Employee::where('phone',$phone)->first();
        if($employee){
            $code = Usercode::where('employee_id',$employee->id)->first();
            if($code==null){
                return response()->json([
                    'is_success'=>false,
                    'message' => 'WhatJob Code donest exist, You cant register!',
                    'data'=>[
                        ]
                    ], 200);
                }
            $client = new Client();
            $res = $client->request('POST', 'https://app.whatsjob.it/send_sms_api.php', [
                'form_params' => [
                    'usr' => 'whatsjob_sms_API',
                    'pwd' => 'a8534JHGu99$&sx6sbcF93mjfkjf478y&%hnc',
                'txt' =>  $code->code,
                'dest' => $phone,               
                ]
            ]);
            // $body = $res->getBody()->getContents(); 
            
            // return response()->json([
            //     'data' => $body
            //    ], $res->getStatusCode());
            
            if($res->getStatusCode()==200){
                return response()->json([
                    'is_success'=>true,
                    'message'=>'Sent SMS!',
                    'data' => [
                        'code' => $code->code,
                    ]
                ], 200);
            } else {
                return response()->json([
                    'is_success'=>false,
                    'message'=>'Not Sent SMS!',
                    'data' => [
                        // 'code' => $code->code,
                    ]
                ], 200);
            }
        } else {
            return response()->json([
                'is_success'=>false,
                'message'=>'Phone doesnt exist, Please register phone',
                'data' => [
                    ]
            ], 200);
        }
    }

    public function send_logincode(Request $request){
        $employee = Employee::where('phone',$request->phone)->first();
        $code = Usercode::where('employee_id',$employee->id)->orderBy('updated_at', 'desc')->first();
        if($code->code == $request->code) {
            return response()->json([
                'is_success'=>true,
                'message' => 'Login Successfull',
                'data' => [
                    'employee_id'=>$employee->id,
                    'admin_id'=>$employee->admin_id,

                ]
               ], 200);

        } else {
            return response()->json([
                'is_success'=>false,
                'message' => 'Try again!',
                'data' => [
                ]
            ], 200);
        }
    }

    public function save_token(Request $request){
        $updateUser = Employee::whereId($request->employee_id)->update(['device_token'=>$request->token]);
        if($updateUser){
            return response()->json([
                'is_success'=>true,
                'message' => 'Token saved successfully.',
                'data' => [
    
                ]
               ], 200);
        } else {
            return response()->json([
                'is_success'=>true,
                'message' => 'Token saved successfully.',
                'data' => [
                    ]
                ], 200);
        }
    }

    public function send_notification(Request $request){
        $firebaseToken = Employee::whereNotNull('device_token')->pluck('device_token')->all();
        
        $SERVER_API_KEY = 'AAAAlVr_Xyk:APA91bFy0HNJewtxwcxIFP0WVdNWoPk-ZrkkrlJp3QYsCpYZ_ixhJOFHP6Zy0tHh1ChGXxVlKMqrgGSOFwIKMesG9h8YaUnnyfr5bxRGKXipzM6JqB3q_RKaeSvRGyZi1uCaUCLCfsp5';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "content_available" => true,
                "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        if($response){
            return response()->json([
                'is_success'=>true,
                'message' => 'Token saved successfully.',
                'data' => [
    
                ]
               ], 200);
        } else {
            return response()->json([
                'is_success'=>true,
                'message' => 'Token saved successfully.',
                'data' => [
                    ]
                ], 200);
        }

        return back()->with('success', " Successfully updated")->withInput($request->all());
    }

    public function get_data(Request $request){
        $message_data = [];
        $employee_id = $request->employee_id;
        $message_headers1 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message.id as message_id','message.content','message_header.owner_type')
        ->join('message', 'message_header.id', '=', 'message.header_id')
        ->where('message_header.receiver_id',$employee_id)
        ->where('message_header.owner_type', 'admin')
        ->where('message.is_read', '=', 0)
        ->get();

        $message_headers2 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message_attachment.id as message_id','message_header.owner_type')
        ->join('message_attachment', 'message_header.id', '=', 'message_attachment.header_id')
        ->where('message_header.receiver_id',$employee_id)
        ->where('message_header.owner_type', 'admin')
        ->where('message_attachment.is_sign', '=', 0)
        ->get();

        $message_data[] = $message_headers1;
        
        $message_headers3 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message.id as message_id','message.content','message_header.owner_type')
        ->join('message', 'message_header.id', '=', 'message.header_id')
        ->where('message_header.creator_id',$employee_id)
        ->where('message_header.owner_type', 'employee')
        ->where('message.is_read', '=', 0)
        ->get();

        $message_headers4 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message_attachment.id as message_attachment_id','message_header.owner_type')
        ->join('message_attachment', 'message_header.id', '=', 'message_attachment.header_id')
        ->where('message_header.creator_id',$employee_id)
        ->where('message_header.owner_type', 'employee')
        ->where('message_attachment.is_sign', '=', 0)
        ->get();

        $message_data[] = $message_headers2;
        
        if ( sizeof($message_headers1) ) { // If more than 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message1'=>'message1',
                    'message'=>$message_headers1,
                ]
               ], 200);
         } else if(sizeof($message_headers2)) { // If 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message2'=>'message2',
                    'message'=>$message_headers2,
                ]
               ], 200);
         } else if(sizeof($message_headers3)) { // If 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message3'=>'message3',
                    'message'=>$message_headers3,
                ]
               ], 200);
         } else if(sizeof($message_headers4)) { // If 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message4'=>'message4',
                    'message'=>$message_headers4,
                ]
               ], 200);
         } else {
            return response()->json([
                'is_success'=>false,
                'message' => 'notification sent',
                'data' => [
                    'message'=> "empty",
                ]
               ], 200);           
         }


        // $message_headers = Message_header::where('receiver_id', $employee_id)->where('owner_type', 'admin')->get();
    }

    public function send_notification_testing(Request $request){
        $message_data = [];
        $employee_id = $request->employee_id;
        $message_headers1 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message.id as message_id','message.content','message_header.owner_type')
        ->join('message', 'message_header.id', '=', 'message.header_id')
        ->where('message_header.receiver_id',$employee_id)
        ->where('message_header.owner_type', 'admin')
        ->where('message.is_read', '=', 0)
        ->get();

        $message_headers2 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message_attachment.id as message_id','message_header.owner_type')
        ->join('message_attachment', 'message_header.id', '=', 'message_attachment.header_id')
        ->where('message_header.receiver_id',$employee_id)
        ->where('message_header.owner_type', 'admin')
        ->where('message_attachment.is_sign', '=', 0)
        ->get();

        $message_data[] = $message_headers1;
        
        $message_headers3 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message.id as message_id','message.content','message_header.owner_type')
        ->join('message', 'message_header.id', '=', 'message.header_id')
        ->where('message_header.creator_id',$employee_id)
        ->where('message_header.owner_type', 'employee')
        ->where('message.is_read', '=', 0)
        ->get();

        $message_headers4 = DB::table('message_header')
        ->select('message_header.id as message_header_id','message_attachment.id as message_attachment_id','message_header.owner_type')
        ->join('message_attachment', 'message_header.id', '=', 'message_attachment.header_id')
        ->where('message_header.creator_id',$employee_id)
        ->where('message_header.owner_type', 'employee')
        ->where('message_attachment.is_sign', '=', 0)
        ->get();

        $message_data[] = $message_headers2;
        
        if ( sizeof($message_headers1) ) { // If more than 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message1'=>'message1',
                    'message'=>$message_headers1,
                ]
               ], 200);
         } else if(sizeof($message_headers2)) { // If 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message2'=>'message2',
                    'message'=>$message_headers2,
                ]
               ], 200);
         } else if(sizeof($message_headers3)) { // If 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message3'=>'message3',
                    'message'=>$message_headers3,
                ]
               ], 200);
         } else if(sizeof($message_headers4)) { // If 0
            return response()->json([
                'is_success'=>true,
                'message' => 'notification sent',
                'data' => [
                    'message4'=>'message4',
                    'message'=>$message_headers4,
                ]
               ], 200);
         } else {
            return response()->json([
                'is_success'=>false,
                'message' => 'notification sent',
                'data' => [
                    'message'=> "empty",
                ]
               ], 200);           
         }


        // $message_headers = Message_header::where('receiver_id', $employee_id)->where('owner_type', 'admin')->get();
    }
    
    public function downloadfile(Request $request){
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path().'/'.Message_attachment::whereId($request->id)->first()->path;
        // $file= public_path(). "/uploads/download.pdf";
       
        $headers = array(
                'Content-Type: application/pdf',
                );
        return response()->download($file);

        // return response()->download($file, 'filename.pdf', $headers);
    }
}    
    