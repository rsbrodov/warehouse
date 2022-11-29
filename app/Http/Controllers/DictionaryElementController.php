<?php

namespace App\Http\Controllers;


use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\DictionaryElementService;

class DictionaryElementController extends Controller
{
    public $dictionaryElementService;

    public function __construct(DictionaryElementService $service)
    {
        $this->dictionaryElementService = $service;
    }

    public function index()
    {
        if(Auth::guard('web')->check()) {
            return view('dictionary_element.index');
        }
    }

    public function store(Request $request)
    {
        $result = $this->dictionaryElementService->store($request);
        return $result;
    }

    public function update(Request $request, $id)
    {
        $result = $this->dictionaryElementService->update($request, $id);
        return $result;
    }

    public function destroy($id)
    {
        $result = $this->dictionaryElementService->destroy($id);
        return $result;
    }

    public function findElementDictionaryID($id)
    {
        $result = $this->dictionaryElementService->findElementDictionaryID($id);
        return $result;
    }

    public function findID($id)
    {
        $result = $this->dictionaryElementService->findID($id);
        return $result;
    }

    public function indexCode($code)
    {
        $result = $this->dictionaryElementService->indexCode($code);
        return $result;
    }
}
