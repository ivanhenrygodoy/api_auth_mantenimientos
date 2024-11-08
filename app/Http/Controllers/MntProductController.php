<?php

namespace App\Http\Controllers;

use App\Models\MntProduct;
use Illuminate\Http\Request;

class MntProductController extends Controller
{
    public function search_product() {
        $listProduct = MntProduct::select('id', 'name')->where('activo', true)->get();
        return response()->json([
            'status'=>'200',
            'data'=> $listProduct,
            'errors'=>[],
        ],200);
    }
}
