<?php

namespace App\Models;

use App\Scopes\BuyerScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScopes);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    
}
