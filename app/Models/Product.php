<?php

namespace App\Models;

use App\Http\Controllers\Seller\SellerController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    const PRODUCT_AVAILABLE = 'available';
    const PRODUCT_UNAVAILABLE = 'unavailable';
    protected $filllable = [
    'name',
    'description',
    'quantity',
    'status',
    'image',
    'seller_id',
    ];

    public function productAvailable(){
        return $this->status = self::PRODUCT_AVAILABLE;
    }
    public function productUnavailable(){
        return $this->status = self::PRODUCT_UNAVAILABLE;
    }

    public function transaccions(){
        return $this->hasMany(Transaction::class);
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function categories(){
        return  $this->belongsToMany(Category::class);
    }

}
