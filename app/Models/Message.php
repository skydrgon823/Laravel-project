<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'message';

    protected $fillable = [
        'content','header_id','creator_id','owner_type','is_read'
    ]; 

    public function message_header(){
        return $this->belongsTo('App\Message_header');
    }

    protected function get_message_number($id){
    	return self::where('header_id',$id)->get()->count();
    }
    protected function get_read_stataus($id){
    	return self::where('header_id',$id)->orderBy('id', 'desc')->first();
    }
}
