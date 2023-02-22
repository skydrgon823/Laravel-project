<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'name','job_type'
    ]; 
    protected function get_category_name($id){
        return self::find($id)->job_type.' '.self::find($id)->name;
    }

}
