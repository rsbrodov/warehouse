<?php

namespace App\Services;

use App\Http\Requests\TypeContentRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\TypeContentResource;
use App\Models\Clients;
use App\Models\DictionaryElement;
use App\Models\ElementContent;
use App\Models\Icons;
use App\Models\TypeContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientsService
{
    //получение списка
    public function getListClient($get)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $query = Clients::query();
                if(isset($get['status'])){
                    $query = $query->whereIn('status', explode(',',$get['status']));
                }
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
        $result['data'] = ClientResource::collection($data);
        return $result;
    }
}
