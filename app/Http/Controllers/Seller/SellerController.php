<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    public function index()
    {
        $sellers = Seller::has('products')->get();
        return $this->showAll($sellers,200);
    }

    public function show($id)
    {
        $sellers = Seller::has('products')->findOrFail($id);
        return $this->showOne($sellers,200);
    }
}