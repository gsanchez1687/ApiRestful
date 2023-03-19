<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::has('products')->get();
        return response()->json(['data'=>$sellers],200);
    }

    public function show($id)
    {
        $sellers = Seller::has('products')->findOrFail($id);
        return response()->json(['data'=>$sellers],200);
    }
}
