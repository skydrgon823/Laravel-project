<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'name','surname','email','phone','admin_id','code_id','is_reg','is_login',
    ]; 

    public function admin(){
        return $this->belongsTo('App\users');
    }

    protected function get_employee_name($id){
        if(self::find($id)==null)
            return null;
        return self::find($id)->name;
    }

    protected function get_employee_surname($id){
        if(self::find($id)==null)
            return null;
        return self::find($id)->surname;
    }
}
