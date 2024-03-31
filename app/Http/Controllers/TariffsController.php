<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DictionaryRequest;
use App\Services\TariffsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TariffsController extends Controller
{
    public $tariffsService;
    /**
     * Display a listing of the resource.
     *
     */
    public function __construct(TariffsService $service)
    {
        $this->tariffsService = $service;
    }

    public function getListTariffs()
    {
        return $this->tariffsService->getListTariff($_GET);
    }

    public function create()
    {
        if (Auth::guard('web')->check()) {
            return view('tariffs.create');
        }
    }

    public function update(DictionaryRequest $request, $id)
    {
        $result = $this->tariffsService->update($request, $id);
        return $result;
    }
}
