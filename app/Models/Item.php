<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'categories_id',
        'quantity',
        'locations_id',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class, 'locations_id');
    }
    
}

