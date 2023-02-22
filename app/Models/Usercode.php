<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usercode extends Model
{
    use HasFactory;

    protected $table = 'usercode';

    protected $fillable = [
        'code','admin_id','employee_id','is_used'
    ]; 

}
