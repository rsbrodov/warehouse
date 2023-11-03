<?php
namespace App\Services;

use App\Http\Requests\ElementContentRequest;
use App\Http\Resources\ElementContentResource;
use App\Models\DictionaryElement;
use App\Models\ElementContent;
use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ElementContentService
{

    public function store(ElementContentRequest $request)
    {
        if (Auth::guard('web')->check()) {
            $typeContent = TypeContent::find(request('type_content_id'));
            $bodyForElement = json_decode($typeContent->body);
            if (!empty($bodyForElement)){
                foreach ($bodyForElement as $row) {
                    foreach ($row as $column) {
                        foreach ($column as $element) {
                            $element->value = '';
                            if($element->type == 'checkbox'){
                                $element->value = (object)[];
                            }
                            unset($element->name);
                            unset($element->class);
                            if ($element->type == 'select' || $element->type == 'radio' || $element->type == 'checkbox') {
                                $dictionaryElements = DictionaryElement::where('dictionary_id', $element->dictionary_id)->get();
                                $element->parameter = [];
                                foreach($dictionaryElements as $dictionaryElement){
                                    $element->parameter[] = $dictionaryElement->value;
                                }
                            }
                        }
                    }
                }
            }

            $newElementContent = ElementContent::create(
                [
                    'id_global'      => Str::uuid()->toString(),
                    'type_content_id'=> request('type_content_id'),
                    'label'          => $request->label,
                    'description'          => $request->description,
                    'active_from'    => $request->active_from ? date('Y-m-d H:m:s', strtotime($request->active_from)) : null,
                    'active_after'   => $request->active_after ? date('Y-m-d H:m:s', strtotime($request->active_after)) : null,
                    'status'         => 'DRAFT',
                    'version_major'  => '1',
                    'version_minor'  => '0',
                    'api_url'            => str_slug($request->api_url),
                    'based_element'  => null,
                    'body'  => json_encode($bodyForElement),
                    'created_author' => Auth::guard('web')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id,
                    'created_date' => Date('Y-m-d H:i:s'),
                    'update_date' => Date('Y-m-d H:i:s'),
                ]
            );

            return new ElementContentResource($newElementContent);
        }
    }
    public function edit($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $elementContent = ElementContent::where('id', $id)->first();
                return view('element_content.edit', ['element_content' => $elementContent]);
            }
        }
    }
    public function update(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $element = ElementContent::find($request->id);
            if ($element->status === 'Draft' || $element->status === 'Archive') {
                $otherPublished = ElementContent::where(['id_global' => $element->id_global, 'status' => 'Published'])->first();
                if (isset($otherPublished)) {
                    $otherPublished->status = 'Archive';
                    $otherPublished->save();
                }
            }

            $element->label = $request->label;
            $element->api_url = $request->api_url;
            $element->active_from = $request->active_from ? date('Y-m-d H:m:s', strtotime($request->active_from)) : null;
            $element->active_after = $request->active_after ? date('Y-m-d H:m:s', strtotime($request->active_after)) : null;
            $element->status = $request->status;
            $element->description = $request->description;
            $element->updated_author = Auth::guard('web')->user()->id;
            $element->update_date = Date('Y-m-d H:i:s');
            $element->save();

            return new ElementContentResource($element);
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $elementContent = ElementContent::find($id);
                if ($elementContent) {
                    $elementContent->delete();
                    return response()->noContent();
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findElementContentID($id, $get)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $typeContent = TypeContent::find($id);
                $typeContents = TypeContent::where('id_global', $typeContent->id_global)->pluck('id');
                $query = ElementContent::query()
                    ->whereIn('type_content_id', $typeContents)
                    ->with('typeContent');
                if (isset($get['status'])) {
                    $query = $query->whereIn('status', explode(',', $get['status']));
                }
                if (isset($get['label'])) {
                    $query = $query->where('label', 'LIKE', '%' . $get['label'] . '%');
                }
                if (isset($get['active_from'])) {
                    $query = $query->where('active_from', '>=', date('Y-m-d', strtotime($get['active_from'])));
                }
                if (isset($get['active_after'])) {
                    $query = $query->where('active_after', '>=', date('Y-m-d', strtotime($get['active_after'])));
                }
                if (isset($get['url'])) {
                    $query = $query->where('api_url', 'LIKE', '%' . $get['url'] . '%');
                }
                $count = $query->count();
                if (isset($get['page']) && $get['page'] > 0) {
                    $query = $query->offset(($get['page'] - 1) * 15);
                }
                $elementContent = $query->orderBy('update_date', 'desc')->limit(15)->get()->unique('id_global');
                return self::prepareListing($elementContent, $count);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findElementContentAll($get)
    {
        try {
            if (Auth::guard('web')->check()) {
                $typeContent = TypeContent::all();
                $typeContents = TypeContent::pluck('id');
                $query = ElementContent::query()
                    ->whereIn('type_content_id', $typeContents)
                    ->with('typeContent');
                if(isset($get['status'])){
                    $query = $query->whereIn('status', explode(',',$get['status']));
                }
                if(isset($get['label'])){
                    $query = $query->where('label', 'LIKE', '%'.$get['label'].'%');
                }
                if(isset($get['active_from'])){
                    $query = $query->where('active_from', '>=', $get['active_from']);
                }
                if(isset($get['active_after'])){
                    $query = $query->where('active_after', '>=', $get['active_after']);
                }
                if(isset($get['url'])){
                    $query = $query->where('api_url', 'LIKE', '%'.$get['url'].'%');
                }
                $count = $query->count();
                if (isset($get['page']) && $get['page'] > 0) {
                    $query = $query->offset(($get['page'] - 1) * 15);
                }
                $elementContents = $query->orderBy('update_date', 'desc')->limit(15)->get()->unique('id_global');

                return self::prepareListing($elementContents, $count);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createNewVersion($id, $parametr)
    {
        $type = ElementContent::findOrFail($id);
        $existOtherDraft = ElementContent::where(['id_global' => $type->id_global, 'status' => 'Draft'])->count();
        if ($existOtherDraft) {
            $error = array(
                'code'      =>  422,
                'message'   =>  'The given data was invalid',
                'errors' => [
                    'version_error' => 'У вас уже есть черновик этого элемента контента, Вы можете опубликовать новую версию из него'
                ]
            );
            return response()->json($error, 422);
        }
        if ($parametr == 'major' || $parametr == 'minor') {
            if ($parametr == 'major') {
                $typeContent = ElementContent::where('id_global', $type->id_global)
                    ->orderBy('version_major', 'desc')
                    ->first();
                $newTypeContent = $typeContent->replicate(); //тут лежит наш новый объект
                $newTypeContent->version_major = $typeContent->version_major + 1; //изменяем объект с учетом наших параметров затем сохраняем
                $newTypeContent->status = 'Draft';
                $newTypeContent->version_minor = 0;
            } else {
                $typeContent = ElementContent::where('id_global', $type->id_global)
                    ->orderBy('version_major','desc')
                    ->orderBy('version_minor', 'desc')
                    ->first();
                $newTypeContent = $typeContent->replicate();
                $newTypeContent->version_minor = $typeContent->version_minor + 1;
                $newTypeContent->status = 'Draft';
            }
            if ($newTypeContent->save()) {
                return new ElementContentResource($newTypeContent);
            } else {
                $error = array(
                    'code'      =>  422,
                    'message'   =>  'The given data was invalid',
                    'errors' => [
                        'version_error' => 'Ошибка в формировании новой версии элемента контента, перезагрузите страницу и попробуйте снова.'
                    ]
                );
            }
        } else {
            $error = array(
                'code'      =>  422,
                'message'   =>  'The given data was invalid',
                'errors' => [
                    'version_error' => 'Ошибка передачи параметра мажор или минор. Перезагрузите страницу и попробуйте снова.'
                ]
            );
            return response()->json($error, 422);
        }
    }

    public function saveDraft(Request $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $elementContent = ElementContent::findOrFail($id);
            $typeContent = TypeContent::findOrFail($elementContent->type_content_id);
            $body = json_decode($typeContent->body);
            $error = [];
            $i = 0;
            foreach ($body as $row){
                foreach ($row as $column){
                    foreach ($column as $element){
                        $i++;
                        print_r($element->name.' : '.$request[$element->type.$i].'</br>');
                        if ($element->required==1 and !$request[$element->type.$i]) {
                            $error[] = [
                                'errors' => [
                                    'field_name'      =>  $element->name,
                                    'field_type'      =>  $element->type.$i,
                                    'filled_error' => 'Поле не заполнено',
                                ]
                            ];
                        }
                    }
                }
            }
            dd($error);
        }
    }

    public function getApiUrl($apiUrl)
    {
        $elementContent = ElementContent::where('api_url', $apiUrl)->first();
        if (isset($elementContent->api_url)) {
            print_r($elementContent);
        } else {
            print_r('Exception');
        }
    }


    public function getAllVersionElementContent($id)
    {
        $current = ElementContent::findOrFail($id);
        $elementContent = ElementContent::where('id_global', $current->id_global)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
        return ElementContentResource::collection($elementContent);
    }

    public function checkingApiUrl($apiUrl, $idGlobal = null)
    {
        if ($idGlobal) {
            if (($elementContentExistence = ElementContent::where('url', $apiUrl)->whereNotIn('id_global', [$idGlobal])->first()) !== null) {
                return 'error';
            }
        } else {
            if (($elementContentExistence = ElementContent::where('url', $apiUrl)->first()) !== null) {
                return 'error';
            }
        }
    }

    public function validateElementContent($id, $request)
    {
        $typeContent = TypeContent::find($id);
        $body = json_decode($typeContent->body);
        $error = [];
        $i = 0;
        foreach ($body as $row) {
            foreach ($row as $column) {
                foreach ($column as $element) {
                    if ($element->required == 1 && !$request[$element->uid]['value']) {
                        $error[$element->uid] = 'Необходимо заполнить «'.$element->title.'»';
                    }
                }
            }
        }
        return $error;
    }

    public function pushingDropDownList($id)
    {
        $typeContent = TypeContent::find($id);
        $body = json_decode($typeContent->body);
        $dictionaryList = [];
        $i = 0;
        foreach ($body as $row) {
            foreach ($row as $column) {
                foreach ($column as $element) {
                    if (!empty($element->dictionary_id)) {
                        $dictionaryElements = DictionaryElement::where('dictionary_id', $element->dictionary_id)->get();
                        foreach($dictionaryElements as $dictionaryElement){
                            $dictionaryList[$element->uid][$dictionaryElement->id] = $dictionaryElement->value;
                        }
                    }
                }
            }
        }
        return $dictionaryList;
    }

    public function getElementContentID($id)
    {
        $elementContent = ElementContent::where('id', $id)->with('typeContent')->first();
        return new ElementContentResource($elementContent);
    }

    public static function prepareListing($data, $countData)
    {
        $result = [];
        $result['pages'] = ceil($countData / 15);
        $result['countData'] = $countData;
        $result['data'] = ElementContentResource::collection($data);
        return $result;
    }

    public static function getElementBodyById($id)
    {
        $elementContent = ElementContent::findOrFail($id);
        $result =[];
        $body = json_decode($elementContent->body);
        foreach ($body as $row) {
            foreach ($row as $column) {
                foreach ($column as $element) {
                    $result[$element->uid] = $element->value;
                }
            }
        }

        return $result;
    }
}
