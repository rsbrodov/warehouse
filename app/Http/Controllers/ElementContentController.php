<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElementContentRequest;
use App\Models\ElementContent;
use App\Models\TypeContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public function getAllVersionElementContent($id)
    {
        $result = $this->elementContentService->getAllVersionElementContent($id);
        return $result;
    }

    public function getAllVersion($id)
    {
        return view('element_content.all-version-element-content');
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

    public function uploadImage(Request $request)
    {
        if ($request->file('file')){
            $image = $request->file('file');
           // $path = $request->file('file')->store('uploads', 'public');
            $name = md5(Carbon::now().'_'.$image->getClientOriginalName()).'.'.$image->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);
            return response()->json(['message' => url('/storage/'.$filePath)]);
            /*if($path){
                return response()->json(['message' => $path]);
            }*/
        }else{
            return response()->json(['message' => 'error uploaded file'], 503);
        }
    }
}
