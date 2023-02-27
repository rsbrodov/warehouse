<?php

namespace App\Http\Controllers;


use App\Http\Requests\DictionaryRequest;
use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\DictionaryService;

class DictionaryController extends Controller
{
    public $dictionaryService;

    public function __construct(DictionaryService $service)
    {
        $this->dictionaryService = $service;
    }

    public function index()
    {
        if(Auth::guard('web')->check()) {
            return view('dictionary.index');
        }
    }

    public function store(DictionaryRequest $request)
    {
        $result = $this->dictionaryService->store($request);
        return $result;
    }

    public function update(DictionaryRequest $request, $id)
    {
        $result = $this->dictionaryService->update($request, $id);
        return $result;
    }

    public function destroy($id)
    {
        $result = $this->dictionaryService->destroy($id);
        return $result;
    }

    public function archive($id)
    {
        $result = $this->dictionaryService->archive($id);
        return $result;
    }

    public function findDictionary()
    {
        $result = $this->dictionaryService->findDictionary();
        return $result;
    }

    public function findDictionaryNotEmptyElement()
    {
        $result = $this->dictionaryService->findDictionaryNotEmptyElement();
        return $result;
    }

    public function findDictionaryID($id)
    {
        $result = $this->dictionaryService->findDictionaryID($id);
        return $result;
    }

    public function test()
    {
        return 123;
    }
}
