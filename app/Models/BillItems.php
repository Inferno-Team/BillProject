<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItems extends Model
{
    use HasFactory;
    protected $table = 'bill_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bill_id',
        'item_id',
        'count',
        'created_at',
        'updated_at'
    ];
    public function bill()
    {
        return $this->belongsTo(BillTable::class, 'bill_id');
    }
    public function catItem()
    {
        return $this->belongsTo(ShopeCategory::class, 'item_id');
    }
}
