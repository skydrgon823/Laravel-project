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
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use Session;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;
use App\Mail\EmailAllocateDids; 
use App\Mail\EmailDeleteDid;
use Illuminate\Support\Facades\DB;
use Response;
use File;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;


class AdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $current_user = auth()->user();

        $employees = Employee::where('admin_id',$current_user->id)->get();
        return view('dashboard.employee', compact('current_user','employees'));
    }

    public function employee(Request $request){
                
        $current_user = auth()->user();
        $employee_datas = Employee::where('admin_id',$current_user->id)->get();
        $no = 1;
        $employees = [];
        foreach($employee_datas as $employee_data){
            $employees[] = (object) array(
                'no'        => $no++,
                'id'        => $employee_data->id,
                'name'      => $employee_data->name,
                'email'      => $employee_data->email,
                'surname'   => $employee_data->surname,
                'phone'     => $employee_data->phone,
                'created_at'=> date_format(date_create($employee_data->created_at),'d/m/Y H:i'),
                'updated_at'=> date_format(date_create($employee_data->updated_at),'d/m/Y H:i'),
            );
        }
        return view('dashboard.employee', compact('current_user','employees'));
    }

    public function search_employee(Request $request){
                
        $current_user = auth()->user();
        $employee_datas = Employee::where('name' ,'LIKE','%'.$request->incoming_did."%")->get();
        $no = 1;
        foreach($employee_datas as $employee_data){
            $employees[] = (object) array(
                'no'        => $no++,
                'id'        => $employee_data->id,
                'name'      => $employee_data->name,
                'email'      => $employee_data->email,
                'surname'   => $employee_data->surname,
                'phone'     => $employee_data->phone,
                'created_at'=> date_format(date_create($employee_data->created_at),'d/m/Y H:i'),
                'updated_at'=> date_format(date_create($employee_data->updated_at),'d/m/Y H:i'),
            );
        }
        return view('dashboard.employee', compact('current_user','employees'));
    }

    public function view_employee(Request $request,$id){
        $current_user = auth()->user();
        $employee = Employee::whereId($id)->first();
        $type = 'View';
        $desc = 'View Employee details';
        $disable = true;
        return view('dashboard.employee-new', compact('current_user','employee','type','desc','disable'));
    }   

    public function store_employee(Request $request){
        $current_user = auth()->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required'],            
        ]);
        if (!is_numeric($request->phone)) {
            return back()->with('error', " The Phone number is correct! Please enter again!")->withInput($request->all());
        } 
        $phone = $request->phone;
        $existphone = Employee::where('phone',$request->phone)->first();
        if($existphone){
            return back()->with('error', " The Phone you entered is already assigned to an account.  You must either enter a new phone or login to your existing account.")->withInput($request->all());
        } 

        $existemail = Employee::where('email',$request->email)->first();
        
        if($existemail){
            return back()->with('error', " The Email you entered is already assigned to an account.  You must either enter a new Email or login to your existing account.")->withInput($request->all());
        } 

        $code = Usercode::whereNull('employee_id')->first();
        if($code==null){
            return back()->with('error', " Company Code donest exist, Please create Comapny code!")->withInput($request->all());
        }
                    $data = [
                        "admin_id"          => $current_user->id,
                        'name'              => $request->name,
                        'surname'              => $request->surname,
                        'email'              => $request->email,
                        'phone'           => $request->phone,
                    ];
        $newEmployee = Employee::create($data);
        Usercode::whereId($code->id)->update(array('employee_id'=>$newEmployee->id,'is_used'=>1));
        $employees = Employee::get();
        return back()->with('success', " Create Successfull!")->withInput($request->all());
        // return view('dashboard.employee', compact('current_user','employees'));   	
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
                        'surname'           => $request->surname,
                        'email'             => $request->email,
                        'phone'             => $request->phone,
                    ];
        Employee::whereId($request->id)->update($data);
        return back()->with('success', " Successfully updated")->withInput($request->all());                 	
    }

    public function delete_employee(Request $request){

        $employee = Employee::find($request->id);
        $employee->delete();
        $message_headers = Message_header::where('creator_id',$request->id)->orWhere('receiver_id',$request->id)->delete();
        $usercode = Usercode::where('employee_id',$request->id)->delete();
        return back()->with('success', " Successfully deleted")->withInput($request->all());         
    }

    public function category(Request $request){
                
        $current_user = auth()->user();
            $jobwall_data_categorys = Category::where('job_type','jobwall')->get();
            foreach($jobwall_data_categorys as $jobwall_data_category){
                
                $jobwall_categorys[] = (object) array(
                    'id'=> $jobwall_data_category->id,
                    'name'=>$jobwall_data_category->name,
                    'job_type'=>$jobwall_data_category->job_type,
                    'num' => Message_header::where('creator_id',$current_user->id)->where('category_id',$jobwall_data_category->id)->count(),
                );
            }
            $jobdrawer_data_categorys = Category::where('job_type','jobdrawer')->get();
            foreach($jobdrawer_data_categorys as $jobdrawer_data_category){
                
                $jobdrawer_categorys[] = (object) array(
                    'id'=> $jobdrawer_data_category->id,
                    'name'=>$jobdrawer_data_category->name,
                    'job_type'=>$jobwall_data_category->job_type,
                    'num' => Message_header::where('creator_id',$current_user->id)->where('category_id',$jobdrawer_data_category->id)->count(),
                );
            }
            return view('dashboard.category', compact('current_user','jobwall_categorys','jobdrawer_categorys'));
    }   

    public function store_category(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255'],           
        ]);

                    $data = [
                        'name'              => $request->name,
                        'job_type'          => $request->job_type,
                    ];
        Category::create($data);
        return back()->with('success', " Successfully created")->withInput($request->all());                 	
    } 

    public function update_category(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255'],         
        ]);

                    $data = [
                        'name'              => $request->name,
                        'job_type'          => $request->job_type,
                    ];
        Category::whereId($request->id)->update($data);
        return back()->with('success', " Successfully updated")->withInput($request->all());                 	
    }

    public function delete_category(Request $request){

        $category = Category::find($request->id);
        $category->delete();
        return back()->with('success', " Successfully deleted")->withInput($request->all());     
    }

    public function jobchat(Request $request){
        $current_user = auth()->user();
        $employees = EMployee::where("admin_id", $current_user->id)->get();
        $jobwall_categorys = Category::where('job_type','jobwall')->get();
        $jobdrawer_categorys = Category::where('job_type','jobdrawer')->get();
        $headers = Message_header::where('creator_id', $current_user->id)->where('owner_type','admin')->where('category_id','<>',0)->get();
        $no = 1;
        $message_headers = [];
        foreach($headers as $header){
            $message_headers[] = (object) array(
                'no'=>$no++,
                'id'=>$header->id,
                'receiver_id' =>$header->receiver_id,
                'title' =>$header->title,
                'object' =>$header->object,
                'created_at'=> date_format(date_create($header->created_at),'d/m/Y H:i'),
                'updated_at'=> date_format(date_create($header->updated_at),'d/m/Y H:i'),
                'number'=>Message::get_message_number($header->id),
                'read_status' =>Message::get_read_stataus($header->id)->is_read,
                'owner_status' =>Message::get_read_stataus($header->id)->owner_type,
            );
        }
        return view('dashboard.jobchat', compact('current_user','jobwall_categorys','jobdrawer_categorys','employees', 'message_headers'));
    }

    public function store_message_header(Request $request){
        $current_user = auth()->user();

        $employees = $request->employee;
        foreach ($employees as $i => $employee) {
            if($request->urgent == "on"){
                $data = [
                                'title'              => $request->title,
                                'object'             => $request->object,
                                'category_id'        => $request->category,
                                'is_urgent'          => 1,
                                'creator_id'         => $current_user->id,
                                'receiver_id'        => $employee,     
                                'owner_type'         => $request->owner_type                   
                        ]; 
            } else {
                $data = [
                                'title'              => $request->title,
                                'object'             => $request->object,
                                'category_id'        => $request->category,
                                'is_urgent'          => 0,
                                'creator_id'         => $current_user->id,
                                'receiver_id'        => $employee,     
                                'owner_type'         => $request->owner_type                   
                        ]; 
            }

            $category = Category::where('id',$request->category)->first();
            $employee_name = Employee::find($employee)->name;
            $message_header = Message_header::create($data);

            if($message_header){
                $data_message = [
                    'content'  =>  $request->message,
                    'header_id' => $message_header->id,
                    'creator_id' => $current_user->id,
                    'owner_type'         => $request->owner_type,
                    'is_read'   => 0
                ];
                Message::create($data_message);
                if($i == 0){
                    if($request->hasFile('attached'))
                    {
                        $allowedfileExtension=['pdf','jpg','png','docx'];
                        $files = $request->file('attached');
                        foreach($files as $key=>$file){

                    // File Details 
                    
                            $no = $key+1;
                            $filename = $file->getClientOriginalName();
                            $filenames[$key] = $filename;
                            $extension = $file->getClientOriginalExtension();
                            $extensions[$key] = $extension;
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

                                    if($category->job_type == "jobwall"){
                                        $path = 'uploads/jobwall/'.$category->name;
                                            if(!File::isDirectory(public_path($path))){

                                                File::makeDirectory($path, 0777, true, true);

                                            }
                                    } else{
                                        $path = 'uploads/jobdrawer/'.$category->name;
                                            if(!File::isDirectory(public_path($path))){

                                                File::makeDirectory($path, 0777, true, true);

                                            }                                    
                                    }

                                    $file_name = $message_header->id.'_'.date('dmYHis').'.'.$extension;
                                    //remove image before upload

                                    $file_path = $file->move(public_path($path), $file_name);
                                    $file_paths[$key] = $file_path;
                                    $radio_name = 'attach_sign_'.$no;

                                    $data_message_attachment = [
                                        'name'   =>  $file_name,
                                        'path'   =>  $path.'/'.$file_name,
                                        'header_id'   =>  $message_header->id,
                                        'is_sign' => $request->$radio_name == 'yes'? 1 : 0,
                                    ];
                                    Message_attachment::create($data_message_attachment);

                                } else{
                                    Session::flash('error','File too large. File must be less than 2MB.');
                                }
                            } else{
                                Session::flash('error','Invalid File Extension.');
                            } 
                        }
                    } else{
                        Session::flash('error','upload file');
                    }
                }
                else{
                    foreach($file_paths as $key => $file_path){
                        $file_name = $employee_name.'_'.time().'_'.$filenames[$key];
                        $second_path = $path.'/'.$file_name;
                        Log::info($second_path);
                        if(copy($file_path, $second_path)){
                            $data_message_attachment = [
                                'name'   =>  $file_name,
                                'path'   =>  $second_path,
                                'header_id'   =>  $message_header->id,
                                'is_sign' => $request->$radio_name == 'yes'? 1 : 0,
                            ];
                            Message_attachment::create($data_message_attachment);
                        }
                    }
                }            
            }
        }

        return back()->with('success', " Successfully sent")->withInput($request->all()); 
    }

    public function message_list(Request $request, $id, $lang){
        $current_user = auth()->user();
        $message_header = Message_header::where('id', $id)->first();
        $message_datas = Message::where('header_id',$id)->get();
        $update = Message::where('header_id',$id)->where('owner_type','employee')->where('is_read',0)->update(array('is_read'=>1));
        $message_attachments = Message_attachment::where('header_id', $id)->get();

        $no = 0;
        foreach($message_datas as $message_data){
            $messages[] = (object) array(
                'no'=>$no++,
                'id'=>$message_data->id,
                'content' =>$message_data->content,
                'owner_type' =>$message_data->owner_type,
                'is_read' =>$message_data->is_read,
                'created_at'=> date_format(date_create($message_data->created_at),'d/m/Y H:i'),
                'updated_at'=> date_format(date_create($message_data->updated_at),'d/m/Y H:i'),
            );
        }

        // if($request->lang != null){
        //     Session::put('locale', $request->lang );
        // }
        // if( Session::get('locale') == 'it') {
        //     $tr = new GoogleTranslate('it');
        // } else {
        //     Session::put('locale', 'en');
        //     $tr = new GoogleTranslate('en');
        // }

        $tr = new GoogleTranslate($lang); // Translates to 'en' from auto-detected language by default
        return view('dashboard.message-list', compact('current_user','message_header','messages', 'message_attachments','tr', 'id'));        

    }

    public function store_message(Request $request){
        $request->validate([
            'message' => ['required', 'string'],         
        ]);
        $current_user = auth()->user();
                    $data = [
                        'content'              => $request->message,  
                        'owner_type'           => $request->owner_type,
                        'header_id'            => $request->header_id,
                        'creator_id'           => $current_user->id,     
                        'is_read'   => 0             
                    ];      
        Message::create($data);               
        return back()->with('success', " Successfully sent")->withInput($request->all()); 
    }

    public function generation_usercode(Request $request){

            $current_user = auth()->user();
            $usercodes = Usercode::where('admin_id',$current_user->id)->whereNotNull('employee_id')->get();
            $freecodes = Usercode::where('admin_id',$current_user->id)->whereNull('employee_id')->get();

        return view('dashboard.generation-usercode', compact('current_user', 'usercodes','freecodes'));  
    }

    public function generate_usercode(Request $request){
        $current_user = auth()->user();
        $validator = $request->validate([
            'number' => ['required','numeric'],         
        ]);

        $company = substr($current_user->company, 0, 2);
        
        for($i=0; $i< $request->number; $i++){
            $found = 1;
            while($found > 0){
                
                if($found==0){
                    break;
                }
                $six_digit_random_number = random_int(100000, 999999);
                $code = $company.'_'.$six_digit_random_number;
                $found = Usercode::where('code', $code)->count();
            }   
            $data = [
                'code'            => $code,  
                'admin_id'        => $current_user->id,       
            ];
            Usercode::create($data);     
        }
              
        return back()->with('success', " Successfully Created")->withInput($request->all());         
    }
    
    public function delete_freecode(Request $request){
        $usercode = Usercode::find($request->id);
        $usercode->delete();
        return back()->with('success', " Successfully deleted")->withInput($request->all());         
    }
    
    public function search_usercode(Request $request){

        $current_user = auth()->user();

        $employee_datas = Employee::where('name' ,'LIKE','%'.$request->incoming_did."%")->get();
        foreach($employee_datas as $employee_data){
            if(Usercode::where('employee_id',$employee_data->id)->first()!=null){
                $usercodes[] = Usercode::where('admin_id',$current_user->id)->where('employee_id',$employee_data->id)->first();
            }
        }
        $freecodes = Usercode::where('admin_id',$current_user->id)->whereNull('employee_id')->get();

        return view('dashboard.generation-usercode', compact('current_user', 'usercodes','freecodes'));         
    }

    public function view_profile(Request $request){
        $current_user = auth()->user();
        return view('dashboard.view-profile', compact('current_user'));  
    }
    
    public function update_profile(Request $request){
        var_dump($request->all());
        $current_user = auth()->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        if($request->hasFile('attached')){

            
            $file = $request->file('attached');
            $extension = $file->getClientOriginalExtension();
            $filename = 'logo'.time().'.'.$extension;
            
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            $path = 'uploads/logos/';
            if(!File::isDirectory(public_path($path))){
                
                File::makeDirectory($path, 0777, true, true);
                
            }
            $file_path = $file->move(public_path($path), $filename);
            $data = [
                'name'              => $request->name,
                'company'           => $request->cname,
                'logo_path'         => $path.$filename,
            ];        
            
        } else {
            $data = [
                'name'              => $request->name,
                'company'           => $request->cname,
                'logo_path'         => 'uploads/logos/logo.png',
            ];        
        }
        User::whereId($current_user->id)->update($data);
        return back()->with('success', " Successfully updated")->withInput($request->all());
    }
    
    public function view_password(Request $request){
        $current_user = auth()->user();
        return view('dashboard.view-password', compact('current_user'));  
    }
    
    public function update_password(Request $request){
        $current_user = auth()->user();
        if (!(Hash::check($request->current_password, $current_user->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
    
        if(strcmp($request->current_password, $request->new_password) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        if(strcmp($request->new_password, $request->new_password_confirmation) !== 0){
            //Current password and new password are same
            return redirect()->back()->with("error","Password doesnt match. Please enter password again.");
        }
    
        $data = [
            'password'              => Hash::make($request->new_password),
        ];        
        User::whereId($current_user->id)->update($data);
        return back()->with('success', " Successfully updated")->withInput($request->all());
    }

    public function view_push_notification(){
        return view('dashboard.push-notification');
    }

    public function saveToken(Request $request){
        $current_user = auth()->user();
        User::whereId($current_user->id)->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }

    public function sendNotification(Request $request){
        $employees = Employee::get();
        foreach($employees as $employee){
            $message_datas = [];
            $employee_id = $employee->id;
            $from_unread_ids = DB::table('message_header')
            ->select('message_header.id')
            ->join('message', 'message_header.id', '=', 'message.header_id')
            ->where('message_header.receiver_id',$employee_id)
            ->where('message_header.owner_type', 'admin')
            ->where('message.is_read', '=', 0)
            ->groupby('message_header.id')
            ->get();
            
            $from_unsigned_ids = DB::table('message_header')
            ->select('message_header.id')
            ->join('message_attachment', 'message_header.id', '=', 'message_attachment.header_id')
            ->where('message_header.receiver_id',$employee_id)
            ->where('message_header.owner_type', 'admin')
            ->where('message_attachment.is_sign', '=', 0)
            ->groupby('message_header.id')
            ->get();
            
            $to_unread_ids = DB::table('message_header')
            ->select('message_header.id')
            ->join('message', 'message_header.id', '=', 'message.header_id')
            ->where('message_header.creator_id',$employee_id)
            ->where('message_header.owner_type', 'employee')
            ->where('message.is_read', '=', 0)
            ->get();

            $to_unsigned_ids = DB::table('message_header')
            ->select('message_header.id')
            ->join('message_attachment', 'message_header.id', '=', 'message_attachment.header_id')
            ->where('message_header.creator_id',$employee_id)
            ->where('message_header.owner_type', 'employee')
            ->where('message_attachment.is_sign', '=', 0)
            ->get();
            
            
            foreach($from_unread_ids as $from_unread_id){
                $message = Message::where('header_id',$from_unread_id->id)->where('owner_type','admin')->orderBy('updated_at', 'desc')->first();
                if($message!=null){
                    $message_datas[] = array(
                        'title' => 'Unread Message',
                        'body' => 'URGENT '.$message->content,
                    );
                }
            }
            
            foreach($from_unsigned_ids as $from_unsigned_id){
                $message = Message_attachment::where('header_id',$from_unsigned_id->id)->orderBy('updated_at', 'desc')->first();
                if($message!=null){
                    $message_datas[] = array(
                        'title' => 'Unsigned Attachment',
                        'body' => $message->name,
                    );
                }
            }
            
            foreach($to_unread_ids as $to_unread_id){
                $message = Message::where('header_id',$to_unread_id->id)->where('owner_type','employee')->orderBy('updated_at', 'desc')->first();
                if($message!=null){
                    $message_datas[] = array(
                        'title' => 'Unread Message',
                        'body' => 'URGENT '.$message->content,
                    );
                }
            }
            
            foreach($to_unsigned_ids as $to_unsigned_id){
                $message = Message_attachment::where('header_id',$to_unsigned_id->id)->orderBy('updated_at', 'desc')->first();
                if($message!=null){
                    $message_datas[] = array(
                        'title' => 'Unsigned Attachment',
                        'body' => $message->name,
                    );
                }
            }
            $SERVER_API_KEY = 'AAAAlVr_Xyk:APA91bFy0HNJewtxwcxIFP0WVdNWoPk-ZrkkrlJp3QYsCpYZ_ixhJOFHP6Zy0tHh1ChGXxVlKMqrgGSOFwIKMesG9h8YaUnnyfr5bxRGKXipzM6JqB3q_RKaeSvRGyZi1uCaUCLCfsp5';
            $firebaseToken = Employee::whereId($employee_id)->pluck('device_token')->first();
            if($firebaseToken!=null){
                foreach($message_datas as $message_data){
                    $data = [
                            "registration_ids" => [$firebaseToken],
                            "notification" => [
                                "title" => $message_data['title'],
                                "body" => 'simple'.$message_data['body'],
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
                }
            }
        }
        
    }

    public function downloadfile(Request $request,$id) {
        $file= public_path().'/'.Message_attachment::whereId($id)->first()->path;

        $headers = array(
                'Content-Type: application/pdf',
                );
                return response()->download($file);
    }

    public function changeLanguage(Request $request) {
        App::setLocale($request->lang);
        session()->put('locale', $request->lang);
  
        return redirect()->back();
    }
    
}    