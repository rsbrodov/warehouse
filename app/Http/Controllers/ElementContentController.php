<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElementContentRequest;
use App\Models\ElementContent;
use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\ElementContentService;

class ElementContentController extends Controller
{
    public $elementContentService;

    public function __construct(ElementContentService $service)
    {
        $this->elementContentService = $service;
        //$this->middleware('auth');
    }

    public function index()
    {
        return view('element_content.index');
    }

    public function store(ElementContentRequest $request)
    {
        $result = $this->elementContentService->store($request);
        return $result;
    }

    public function edit($id)
    {
        $result = $this->elementContentService->edit($id);
        return $result;
    }

    public function update(Request $request)
    {
        $result = $this->elementContentService->update($request);
        return $result;
    }

    public function destroy($id)
    {
        $result = $this->elementContentService->destroy($id);
        return $result;
    }

    public function findElementContentID($id)
    {
        $result = $this->elementContentService->findElementContentID($id);
        return $result;
    }

    public function createNewVersion($id, $parameter)
    {
        $result = $this->elementContentService->createNewVersion($id, $parameter);
        return $result;
    }

    public function getAllVersionElementContent()
    {
        $result = $this->elementContentService->getAllVersionElementContent();
        return $result;
    }

    public function saveDraft(Request $request, $id)
    {
        $result = $this->elementContentService->saveDraft($request, $id);
        return $result;
    }

    public function getApiUrl($apiUrl)
    {
        $result = $this->elementContentService->getApiUrl($apiUrl);
        return $result;
    }
}
