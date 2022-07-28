<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $fillable = [
        'name',
        'shop_id'
    ];
    public function cats()
    {
        return $this->hasMany(ShopeCategory::class, 'comp_id');
    }
    public function shop(){
        return $this->belongsTo(Shope::class,'shop_id');
    }
}
