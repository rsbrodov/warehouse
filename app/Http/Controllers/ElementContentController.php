<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElementContentRequest;
use App\Models\Dictionary;
use App\Models\DictionaryElement;
use App\Models\ElementContent;
use App\Models\TypeContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ElementContentService;
use Illuminate\Support\Arr;

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
    public function updateFields($id)
    {
        $elementContentData = [];
        $elementContent = ElementContent::find($id);
        $typeContentPublished = TypeContent::where(['id_global'=> TypeContent::find($elementContent->type_content_id)->id_global, 'status' => 'Published'])->first();
        if($elementContent->body){
            $body = json_decode($elementContent->body);
            foreach ($body as $row){
                foreach ($row as $column){
                    foreach ($column as $element){
                        if($element->type == 'select' || $element->type == 'radio' || $element->type == 'checkbox'){
                            if(Dictionary::find($element->dictionary_id)){
                                $p = [];
                                $dictionaryElements = DictionaryElement::where(['dictionary_id' => $element->dictionary_id])->pluck('value', 'id')->toArray();
                                foreach ($dictionaryElements as $dictionaryElement){
                                    if(!in_array($dictionaryElement, (array)$element->parameter)){
                                        $element->parameter[] = $dictionaryElement;
                                    }
                                }
                                foreach ($element->parameter as $key => $parameter){
                                    if(!in_array($parameter, $dictionaryElements ?? [])){
                                        if(is_string($element->value) && $element->value != $parameter){
                                            unset($element->parameter[$key]);
                                        }elseif(is_array($element->value) || is_object($element->value)){
                                            if(!in_array($parameter, (array)$element->value ?? [])){
                                                unset($element->parameter[$key]);
                                            }
                                        }
                                    }
                                }
                                /*заново перебираем массив так как он превращается в объект если удалить элемент массива посередине, так как сбиваются порядковые номера ключей массива*/
                                foreach ($element->parameter as $key => $parameter){
                                    $p[] = $parameter;
                                }
                                $element->parameter = $p;
                                $elementContentData[$element->uid]['value'] = $element->value;
                                if($element->parameter){
                                    $elementContentData[$element->uid]['parameter'] = $element->parameter;
                                }
                            }
                        }
                    }
                }
            }
            $elementContent->body = json_encode($body);
            $elementContent->save();
            if($typeContentPublished){
                if($typeContentPublished->id == $elementContent->type_content_id){
                    return response()->json($elementContent);
                }else{
                    $elementContent->type_content_id = $typeContentPublished->id;
                    $bodyForElement = json_decode($typeContentPublished->body);
                    foreach ($bodyForElement as $row) {
                        foreach ($row as $column) {
                            foreach ($column as $element) {
                                unset($element->name);
                                unset($element->class);
                                if(empty($elementContentData[$element->uid])){
                                    $element->value = '';
                                    if($element->type == 'checkbox'){
                                        $element->value = (object)[];
                                    }
                                    if ($element->type == 'select' || $element->type == 'radio' || $element->type == 'checkbox') {
                                        $dictionaryElements = DictionaryElement::where('dictionary_id', $element->dictionary_id)->get();
                                        $element->parameter = [];
                                        foreach($dictionaryElements as $dictionaryElement){
                                            $element->parameter[] = $dictionaryElement->value;
                                        }
                                    }
                                }else{
                                    $element->value = $elementContentData[$element->uid]['value'];
                                    if ($element->type == 'select' || $element->type == 'radio' || $element->type == 'checkbox') {
                                        $element->parameter = $elementContentData[$element->uid]['parameter'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $elementContent->body = json_encode($bodyForElement);
            $elementContent->save();
            return response()->json($elementContent);
        }
        return $id;
    }

    public function getApiElement($typeContentApiUrl, $typeVersionMajor, $typeVersionMinor, $elementContentApiUrl, $versionMajor, $versionMinor)
    {
        $typeContent = TypeContent::where(['api_url' => $typeContentApiUrl, 'version_major' => $typeVersionMajor, 'version_minor' => $typeVersionMinor])->first();
        $elementContent = ElementContent::where([
            'type_content_id' => $typeContent->id,
            'api_url' => $elementContentApiUrl,
            'version_major' => $versionMajor,
            'version_minor' => $versionMinor])
            ->with('created_authors:id,name')
            ->with('updated_authors:id,name')
            ->first();
        $elementContent->body = json_decode($elementContent->body);
        $r = [];$r['id'] = $elementContent->id;$r['idGlobal'] = $elementContent->id_global;$r['typeContentId'] = $elementContent->type_content_id;$r['label'] = $elementContent->label;$r['apiUrl'] = $elementContent->api_url;$r['description'] = $elementContent->description;$r['activeFrom'] = $elementContent->active_from;$r['activeAfter'] = $elementContent->active_after;$r['status'] = $elementContent->status;$r['versionMajor'] = $elementContent->version_major;$r['versionMinor'] = $elementContent->version_minor;$r['body'] = $elementContent->body;$r['basedElement'] = $elementContent->based_element;$r['createdAuthors'] = $elementContent->created_authors;$r['updatedAuthors'] = $elementContent->updated_authors;
        if (Auth::guard('web')->check()) {
            $json_pretty = json_encode($r, JSON_PRETTY_PRINT);
            $elementContent = "<pre>" . $json_pretty . "<pre/>";
            return $elementContent;
        }else{
            return $r;
        }
    }
}
