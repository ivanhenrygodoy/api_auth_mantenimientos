<?php

namespace App\Http\Controllers;

use App\Models\MntProduct;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;

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

    public function post_product(Request $request) {
        $newProduct = MntProduct::create([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return response()->json([
            'status' => 200,
            'data' => [
               'message' => ['Product creado exitosamente.'],
               'Agrupador' => $newProduct
            ],
            'error' => []
        ], 200);
    }

    public function put_product(Request $request, $idProduct){
        $busqueProduct = MntProduct::where('id', $idProduct)->first();

        if (!$busqueProduct) {
            return Response()->json([
                'status' => HttpResponse::HTTP_NOT_FOUND,
                'data' => [],
                'errors' => [
                    'message' => ['No se encontró el product.']
                ]
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        if ($busqueProduct->name !== $request->name) {
            // Verificar si el nuevo nombre ya existe en otro registro
            $exists = MntProduct::where('name', $request->name)
                ->where('id', '<>', $idProduct)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => '400',
                    'data' => [],
                    'errors' => [
                        'message' => ['El nombre del product ya existe.']
                    ]
                ], 400);
            }
        }

        $busqueProduct->name = $request->name;
        $busqueProduct->save();


        return response()->json([
            'status' => 200,
            'data' => [
               'message' => ['Product actualiazado exitosamente.'],
               'Agrupador' => $busqueProduct
            ],
            'error' => []
        ], 200);
    }

    public function change_product_state($idProduct) {
        DB::beginTransaction();
        try {
            $busqProduct = MntProduct::where('id', $idProduct)->first();
            if (!$busqProduct) {
                return response()->json(
                    [
                        'status' => '404',
                        'data' => [],
                        'errors' => [
                            'message' => 'Ocurrio un problema, no se encuentra el registro.'
                        ]
                    ],
                    404
                );
            }


            $busqProduct->activo = !$busqProduct->activo;
            $busqProduct->save();

            $message = $busqProduct->activo ? 'activó' : 'desactivó';

            DB::commit();

            return response()->json([
                'status' => '200',
                'data' => [
                    'message' => ["Se $message correctamente."]
                ],
                'errors' => []
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'data' => [],
                'errors' => [
                    'message' => $e->getMessage()
                ]
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}