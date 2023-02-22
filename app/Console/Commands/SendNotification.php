<?php

namespace App\Console\Commands;
use App\Models\User;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Message;
use App\Models\Message_attachment;
use App\Models\Message_header;
use App\Models\Usercode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Response;
use File;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
                                "body"  => $message_data['body'],
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
}
