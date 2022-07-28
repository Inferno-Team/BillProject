<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopeCategory extends Model
{
    use HasFactory;
    protected $table = 'shope_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'shope_id',
        'comp_id',
        'category_name',
        'stock_count',
        'price',
        'expire_date',
        'createion_date',
        'barcode',
        'created_at',
        'updated_at'
    ];
    public function compName()
    {
        return $this->belongsTo(company::class, 'comp_id');
    }
    // public function items()
    // {
    //     return $this->hasMany(CategoryItems::class, 'category_id');
    // }
}
