<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message_header extends Model
{
    use HasFactory;

    protected $table = 'message_header';

    protected $fillable = [
        'title','object','is_urgent','description','category_id','creator_id','receiver_id','owner_type'
    ]; 

    public function messages(){
        return $this->hasMany('App\Message');
    }

    public function message_attachments(){
        return $this->hasMany('App\Message_attachment');
    }    
}

