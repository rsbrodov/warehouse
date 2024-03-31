<?php

namespace App\Services;

use App\Http\Requests\DictionaryRequest;
use App\Http\Resources\TariffResource;
use App\Models\Tariffs;
use Illuminate\Support\Facades\Auth;

class TariffsService
{

    public function update(DictionaryRequest $request, $id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $tariff = Tariffs::find($id);
                $tariff->name = $request->name;
                $tariff->description = $request->description;
                $tariff->status = 'Active';
                $tariff->updated_author = Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id;
                $tariff->update_date = Date('Y-m-d H:i:s');
                $tariff->save();
                return new TariffResource($tariff);
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //получение списка
    public function getListTariff($get)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $query = Tariffs::query();
                if(isset($get['name'])){
                    $query = $query->where('name', 'LIKE', '%'.$get['name'].'%');
                }
                $count = $query->count();
                if (isset($get['page']) && $get['page'] > 0) {
                    $query = $query->offset(($get['page'] - 1) * 15);
                }
                $clientLimit = $query->orderBy('update_date', 'desc')->limit(15)->get();
                return self::prepareListing($clientLimit, $count);
            } else {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function prepareListing($data, $countData)
    {
        $result = [];
        $result['pages'] = ceil($countData / 15);
        $result['countData'] = $countData;
        $result['data'] = TariffResource::collection($data);
        return $result;
    }
}
