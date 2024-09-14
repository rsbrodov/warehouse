<?php

namespace App\Services;

use App\Http\Requests\PaymentsRequest;
use App\Http\Resources\PaymentsResource;
use App\Models\Payments;
use Illuminate\Support\Facades\Auth;

class PaymentsService
{
    public function store(PaymentsRequest $request)
    {
        try {
                $newPayments = Payments::create([
                    'user_id' => $request->user_id,
                    'amount' => $request->amount,
                    'type' => $request->type,
                    'up_down' => $request->up_down,
                    'params' => $request->params ? $request->params : NULL,
                    'date' => Date('Y-m-d H:i:s', strtotime($request->date)),
                    'created_at' => Date('Y-m-d H:i:s'),
                ]);
                return new PaymentsResource($newPayments);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionary = Payments::findOrFail($id);
                $dictionary->delete();
                return response()->noContent();
            }
            else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findPayments($get)
    {

        try {
            //if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $query = Payments::query()->where(['user_id' => $get['user_id']]);
                $payments = $query->orderBy('created_at', 'asc')->get();
                return self::prepareListing($payments, 1);
            /*}else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }*/
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function prepareListing($data, $countData)
    {
        $result = [];
        $result['countData'] = $countData;
        $result['data'] = PaymentsResource::collection($data);
        return $result;
    }
}
