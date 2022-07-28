<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddShopeRequest extends Model
{
    use HasFactory;
    protected $table = 'add_shope_requests';

    protected $primaryKey = 'id';
    protected $fillable = [
        'owner_id',
        'admin_id',
        'shope_id',
        'name',
        'location',
        'approved',
        'request_type',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'approved' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
}
