<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopeStaff extends Model
{
    use HasFactory;
    protected $table = 'shope_staff';
    protected $fillable = [
        'postion',
        'shope_id',
        'worker_id',
        'created_at',
        'updated_at'
    ];
    public function worker(){
        return $this->belongsTo(User::class,'worker_id');
    }
    public function shope(){
        return $this->belongsTo(Shope::class , 'shope_id');
    }
}
