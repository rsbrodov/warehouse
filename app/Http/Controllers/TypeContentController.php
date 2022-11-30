<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeContentRequest;
use Illuminate\Http\Request;
use App\Services\TypeContentService;

class TypeContentController extends Controller
{
    public $typeContentService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(TypeContentService $service)
    {
        $this->typeContentService = $service;
        //$this->middleware('auth');
    }

    //получение вьюшки для фронта
    public function index()
    {
        return view('type_content.index');
    }

    public function create()
    {
        $result = $this->typeContentService->create();
        return $result;
    }

    public function store(TypeContentRequest $request)
    {
        $result = $this->typeContentService->store($request);
        return $result;
    }

    public function update(TypeContentRequest $request, $id)
    {
        $result = $this->typeContentService->store($request, $id);
        return $result;
    }

    public function destroy($id)
    {
        $result = $this->typeContentService->destroy($id);
        return $result;
    }

    public function viewNew($id)
    {
        return view('type_content.view-new');
    }

    public function enterVue($id)
    {
        return view('type_content.enter2');
    }

    public function getAllVersion($id)
    {
        return view('type_content.all-version-type-content');
    }

    //получение списка
    public function getListTypeContent()
    {
        $result = $this->typeContentService->getListTypeContent();
        return $result;
    }

    public function getTypeContentID($id)
    {
        $result = $this->typeContentService->getTypeContentID($id);
        return $result;
    }

    public function getElementContentID($id)
    {
        $result = $this->typeContentService->getElementContentID($id);
        return $result;
    }

    public function getAllVersionTypeContent($id)
    {
        $result = $this->typeContentService->getAllVersionTypeContent($id);
        return $result;
    }

    public function View($id)
    {
        $result = $this->typeContentService->View($id);
        return $result;
    }

    public function createNewVersion($id, $parametr)
    {
        $result = $this->typeContentService->createNewVersion($id, $parametr);
        return $result;
    }

    public function createIcons()
    {
        $result = $this->typeContentService->createIcons();
        return $result;
    }

    public function getShowDescription($id)
    {
        $result = $this->typeContentService->getShowDescription($id);
        return $result;
    }

    public function getIcons()
    {
        $result = $this->typeContentService->getIcons();
        return $result;
    }

    public function saveBody(Request $request)
    {
        $result = $this->typeContentService->saveBody($request);
        return $result;
    }

    public function saveBodyElement(Request $request)
    {
        $result = $this->typeContentService->saveBodyElement($request);
        return $result;
    }

    public function getBody($id)
    {
        $result = $this->typeContentService->getBody($id);
        return $result;
    }

    public function getBodyElementContent($id)
    {
        $result = $this->typeContentService->getBodyElementContent($id);
        return $result;
    }

    public function getDropdownListById($id)
    {
        $result = $this->typeContentService->getDropdownListById($id);
        return $result;
    }

    public function getApiUrl($apiUrl)
    {
        $result = $this->typeContentService->getApiUrl($apiUrl);
        return $result;
    }
}
