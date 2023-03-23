<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerController extends ApiController
{
   
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();
        return $this->showAll($buyers,200);
    }

    //Uso de inyeccion de dependencias implicita del modelo
    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer,200);
    }
}
