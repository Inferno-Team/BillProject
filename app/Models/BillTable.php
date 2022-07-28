<?php

namespace App\Models;

use App\Http\Controllers\cashier\CatItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillTable extends Model
{
    use HasFactory;
    protected $table = 'bill_table';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'cashier_id',
        'shope_id',
        'created_at',
        'updated_at',
        'check'
    ];
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function shope()
    {
        return $this->belongsTo(Shope::class, 'shope_id');
    }
    public function items()
    {
        return $this->hasMany(BillItems::class,'bill_id');
    }
    public function cats(){
        return $this->hasManyThrough(ShopeCategory::class,
        BillItems::class,'bill_id','id');
    }
    
}
