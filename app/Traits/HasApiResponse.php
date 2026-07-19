<?php
namespace App\Traits;
/**
 * HasApiResponse – Trait untuk standarisasi JSON response
 * TODO (Backend Phase): Gunakan pada ApiController
 */
trait HasApiResponse {
    protected function success(mixed $data, string $msg = 'OK', int $code = 200): \Illuminate\Http\JsonResponse {
        return response()->json(['success'=>true,'message'=>$msg,'data'=>$data], $code);
    }
    protected function error(string $msg, int $code = 400): \Illuminate\Http\JsonResponse {
        return response()->json(['success'=>false,'message'=>$msg,'data'=>null], $code);
    }
}
