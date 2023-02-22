<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message_attachment extends Model
{
    use HasFactory;

    protected $table = 'message_attachment';

    protected $fillable = [
        'name','path','header_id','is_sign'
    ]; 

    public function message_header(){
        return $this->belongsTo('App\Message_header');
    }
}
