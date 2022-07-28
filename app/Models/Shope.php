<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shope extends Model
{
    use HasFactory;
    protected $table = "shope";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'owner_id',
        'location',
        'approved',
        'created_at',
        'updated_at'

    ];
    protected $casts = [
        'approved' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function cats()
    {
        return $this->hasMany(ShopeCategory::class, 'shope_id');
    }
    public function bills()
    {
        return $this->hasMany(BillTable::class);
    }
    public function shopeStaff()
    {
        return $this->hasMany(ShopeStaff::class, 'shope_id');
    }
}
