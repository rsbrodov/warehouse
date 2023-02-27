<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeContentRequest;
use Illuminate\Http\Request;
use App\Services\TypeContentService;
use Illuminate\Support\Facades\Auth;

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
        $result = $this->typeContentService->update($request, $id);
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

    public function getApiUrl($id)
    {
        $result = $this->typeContentService->getTypeContentID($id);
        if (Auth::guard('web')->check()) {
            $r = [];$r['id'] = $result->id;$r['idGlobal'] = $result->id_global;$r['name'] = $result->name;$r['apiUrl'] = $result->api_url;$r['icon'] = $result->api_url;$r['icon'] = $result->description;$r['owner'] = $result->owner;$r['basedType'] = $result->based_type;$r['activeFrom'] = $result->active_from;$r['activeAfter'] = $result->active_after;$r['status'] = $result->status;$r['versionMajor'] = $result->version_major;$r['versionMinor'] = $result->version_minor;$r['body'] = $result->body;$r['createdAuthors'] = $result->created_authors;$r['updatedAuthors'] = $result->updated_authors;
            $json_pretty = json_encode($r, JSON_PRETTY_PRINT);
            $result = "<pre>" . $json_pretty . "<pre/>";
            return $result;
        }else{
            return $result;
        }
    }
}
