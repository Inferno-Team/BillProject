<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItems extends Model
{
    use HasFactory;

    protected $table = 'category_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'barcode',
        'expire_date',
        'createion_date',
        'expire_period',
        'img_url',
        'price',
        'created_at',
        'updated_at'
    ];
    public function category()
    {
        return  $this->belongsTo(ShopeCategory::class);
    }
    
}
