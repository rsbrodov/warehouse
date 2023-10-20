<?php

namespace App\Services;

use App\Http\Requests\TypeContentRequest;
use App\Http\Resources\TypeContentResource;
use App\Models\DictionaryElement;
use App\Models\ElementContent;
use App\Models\Icons;
use App\Models\TypeContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TypeContentService
{

    public function create()
    {
        if (Auth::guard('web')->check()) {
            $icons = Icons::all();
            return view('type_content.create')->with('icons', $icons);
        }
    }

    public function store(TypeContentRequest $request)
    {
        try {
            $checkApi = $this->checkingApiUrl($request->apiUrl);
            $checkName = $this->checkingName($request->name);
            if ($checkName) return response()->json($checkName, 422);
            if ($checkApi) return response()->json($checkApi, 422);
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $newTypeContent = TypeContent::create([
                    'id_global' => Str::uuid()->toString(),
                    'name' => $request->name,
                    'description' => $request->description,
                    'owner' => $request->owner,
                    'active_from' => $request->activeFrom ? date('Y-m-d H:m:s', strtotime($request->activeFrom)) : null,
                    'active_after' => $request->activeAfter ? date('Y-m-d H:m:s', strtotime($request->activeAfter)): null,
                    'status' => $request->status,
                    'version_major' => '1',
                    'version_minor' => '0',
                    'icon' => $request->icon,
                    'api_url' => $request->apiUrl,
                    'based_type' => null,
                    'created_date' => Date('Y-m-d H:i:s'),
                    'update_date' => Date('Y-m-d H:i:s'),
                    'created_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id
                ]);
                return new TypeContentResource($newTypeContent);
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(TypeContentRequest $request, $id)
    {
        if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
            $type = TypeContent::where('id', $request->id)->first();
            if ($type->status === 'Draft' or $type->status === 'Archive') {
                $otherPublished = TypeContent::where(['id_global' => $type->id_global, 'status' => 'Published'])->first();
                if (isset($otherPublished)) {
                    $otherPublished->status = 'Archive';
                    $otherPublished->save();
                }
            }
            $type->name = $request->name;
            $type->api_url = $request->apiUrl;
            $type->description = $request->description;
            $type->active_from = $request->activeFrom ? date('Y-m-d H:m:s', strtotime($request->activeFrom)) : null;
            $type->active_after = $request->activeAfter ? date('Y-m-d H:m:s', strtotime($request->activeAfter)) : null;

            $type->icon = $request->icon;
            $type->status = $request->status;
            $type->owner = $request->owner;
            $type->update_date = Date('Y-m-d H:i:s');
            $type->updated_author = Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id;
            $type->save();
            return new TypeContentResource($type);
        }else{
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $typeContent = TypeContent::findOrFail($id);
                $typeContent->delete();
                return response()->noContent();
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //получение списка
    public function getListTypeContent($get)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $typeContents = TypeContent::orderBy('name', 'desc')->get()->unique('id_global');
                $ids = [];
                foreach ($typeContents as $typeContent) {
                    $ids[] = TypeContent::where('id_global', $typeContent->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first()->id;
                }
                $query = TypeContent::query()->whereIn('id', $ids);
                if(isset($get['status'])){
                    $query = $query->whereIn('status', explode(',',$get['status']));
                }
                if(isset($get['name'])){
                    $query = $query->where('name', 'LIKE', '%'.$get['name'].'%');
                }
                if(isset($get['active_from'])){
                    $query = $query->where('active_from', '>=', $get['active_from']);
                }
                if(isset($get['active_after'])){
                    $query = $query->where('active_after', '>=', $get['active_after']);
                }
                if(!empty($get['owner'])){
                    $query = $query->where('owner', $get['owner']);
                }
                $count = $query->count();
                if (isset($get['page']) && $get['page'] > 0) {
                    $query = $query->offset(($get['page'] - 1) * 15);
                }
                $typeContentLimit = $query->orderBy('update_date', 'desc')->limit(15)->get();
                return self::prepareListing($typeContentLimit, $count);
            } else {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTypeContentID($id)
    {
        try {
            $typeContent = TypeContent::findOrFail($id);
            return new TypeContentResource($typeContent);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllVersionTypeContent($idGlobal)
    {
        $typeContents = TypeContent::where('id_global', $idGlobal)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
        return TypeContentResource::collection($typeContents);
    }

    public function getAllVersionTypeContentWeb($id)
    {
        $current = TypeContent::findOrFail($id);
        $typeContents = TypeContent::where('id_global', $current->id_global)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
        return TypeContentResource::collection($typeContents);
    }

    public function View($id)
    {
        if (Auth::guard('web')->check()) {
            $typeContent = TypeContent::where('id_global', $id)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->first();
            return view('type_content.view')->with('type_content', $typeContent);
        } else {
            if (Auth::guard('api')->check()) {
                $typeContent = TypeContent::where('id_global', $id)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
                return response()->json($typeContent);
            } else {
                return response()->json('item not found');
            }
        }
    }

    public function createNewVersion($id, $parametr)
    {
        $type = TypeContent::find($id);
        $existOtherDraft = TypeContent::where(['id_global' => $type->id_global, 'status' => 'Draft'])->count();
        if ($existOtherDraft) {
            $error = array(
                'code' => 422,
                'message' => 'The given data was invalid',
                'errors' => [
                    'version_error' => 'У вас уже есть черновик этого типа контента, Вы можете опубликовать новую версию из него'
                ]
            );
            return response()->json($error, 422);
        }
        if ($parametr == 'major' || $parametr == 'minor') {
            if ($parametr == 'major') {
                //если мажор то мы просто создаем дубликат наивысшей строки по version_major
                $typeContent = TypeContent::where('id_global', $type->id_global)
                    ->orderBy('version_major', 'desc')
                    ->first();
                $newTypeContent = $typeContent->replicate(); //тут лежит наш новый объект
                $newTypeContent->version_major = $typeContent->version_major + 1; //изменяем объект с учетом наших параметров затем сохраняем
                $newTypeContent->status = 'Draft';
                $newTypeContent->version_minor = 0;
            } else {
                //если минор то просто ищим наивысшей строки по version_major и version_minor ну и изменяем версию
                $typeContent = TypeContent::where('id_global', $type->id_global)
                    ->orderBy('version_major', 'desc')
                    ->orderBy('version_minor', 'desc')
                    ->first();
                $newTypeContent = $typeContent->replicate();
                $newTypeContent->version_minor = $typeContent->version_minor + 1;
                $newTypeContent->status = 'Draft';
            }
            if ($newTypeContent->save()) {
                return response()->json($newTypeContent);
            } else {
                $error = array(
                    'code' => 422,
                    'message' => 'The given data was invalid',
                    'errors' => [
                        'version_error' => 'Ошибка в формировании новой версии типа контента, перезагрузите страницу и попробуйте снова.'
                    ]
                );
            }
        } else {
            $error = array(
                'code' => 422,
                'message' => 'The given data was invalid',
                'errors' => [
                    'version_error' => 'Ошибка передачи параметра мажор или минор. Перезагрузите страницу и попробуйте снова.'
                ]
            );
            return response()->json($error, 422);
        }
    }

    public function createIcons()
    {
        $icons = [
            ['unicode' => 'f039', 'code' => 'fa-align-justify', 'name' => 'Бургер'],
            ['unicode' => 'xf19c', 'code' => 'fa-bank', 'name' => 'Банк'],
            ['unicode' => 'f080', 'code' => 'fa-baк-chart', 'name' => 'График'],
            ['unicode' => 'f240', 'code' => 'fa-battery', 'name' => 'Заряд батареи'],
            ['unicode' => 'f02d', 'code' => 'fa-book', 'name' => 'Книга'],
        ];
        $i = 0;
        foreach ($icons as $icon) {
            $i++;
            Icons::create([
                'unicode' => $icon['unicode'],
                'name' => $icon['name'],
                'code' => $icon['code']
            ]);
        }
        return redirect()->route('type-content.index')->with('success', 'Создано' . $i . 'иконок');
    }

    public function getShowDescription($id)
    {
        $typeContent = TypeContent::find($id);
        $body = ($typeContent->body) ? json_decode($typeContent->body) : null;
        return view('type_content.descript-version-type-content', [
            'id' => $id,
            'typeContent' => $typeContent,
            'body' => $body,
        ]);
    }

    public function getIcons()
    {
        $icons = Icons::all();
        $data = [];
        /*foreach($icons as $icon){
            $data[] = '<i class="fa "'.$icon->code.'" fa-lg" aria-hidden="true">' .$icon->name. '</i>';
        }*/
        return response()->json($icons);
    }

    public function saveBody(Request $request)
    {
        $typeContent = TypeContent::find($request->id);
        if (!empty($typeContent)) {
            $typeContent->body = json_encode($request->body);
            $typeContent->save();
            return response()->json($typeContent);
        } else {
            return response()->json('object not found');
        }
    }

    public function saveBodyElement(Request $request)
    {
        $elementContent = ElementContent::find($request->id);
        if (!empty($elementContent)) {
            $elementContent->body = json_encode($request->body);
            $elementContent->save();
            return response()->json($elementContent);
        } else {
            return response()->json('object not found');
        }
    }

    public function getBody($id)
    {
        $typeContent = TypeContent::find($id);
        if (!empty($typeContent)) {
            return response()->json(json_decode($typeContent->body));
        } else {
            return response()->json('object not found');
        }
    }

    public function getBodyElementContent($id)
    {
        $elementContent = ElementContent::find($id);
        return response()->json(json_decode($elementContent->body));
    }

    public function getDropdownListById($id)
    {
        $typeContent = TypeContent::find($id);
        $body = json_decode($typeContent->body);
        $dropdownList = [];
        foreach ($body as $row) {
            foreach ($row as $column) {
                foreach ($column as $key => $element) {
                    if ($key == 'dictionary_id') {
                        $dictionaryElements = DictionaryElement::where('dictionary_id', $element)->get();
                        foreach ($dictionaryElements as $dictionaryElement) {
                            $dropdownList[$column->uid][$dictionaryElement['id']] = $dictionaryElement['value'];
                        }
                    }
                }
            }
        }
        dd($dropdownList);
    }

    public function getApiUrl($apiUrl)
    {
        $typeContent = TypeContent::where('api_url', $apiUrl)->first();
        if (isset($typeContent->api_url)) {
            print_r($typeContent);
        } else {
            print_r('Exception');
        }
    }

    public function checkingApiUrl($apiUrl, $idGlobal = null)
    {
        if ($idGlobal) {
            if (($typeContentExistence = TypeContent::where('api_url', $apiUrl)->whereNotIn('id_global', [$idGlobal])->first()) !== null) {
                return [
                    'code' => 422,
                    'message' => 'The given data was invalid',
                    'errors' => [
                        'apiUrl' => '«API URL» должен быть уникальным'
                    ]
                ];
            }
        } else {
            if (($typeContentExistence = TypeContent::where('api_url', $apiUrl)->first()) !== null) {
                return [
                    'code' => 422,
                    'message' => 'The given data was invalid',
                    'errors' => [
                        'apiUrl' => '«API URL» должен быть уникальным'
                    ]
                ];
            }
        }
    }

    public function checkingName($name, $idGlobal = null)
    {
        if ($idGlobal) {
            if (($typeContentExistence = TypeContent::where('name', $name)->whereNotIn('id_global', [$idGlobal])->first()) !== null) {
                return [
                    'code' => 422,
                    'message' => 'The given data was invalid',
                    'errors' => [
                        'name' => '«Наименование» должно быть уникальным'
                    ]
                ];
            }
        } else {
            if (($typeContentExistence = TypeContent::where('name', $name)->first()) !== null) {
                return [
                    'code' => 422,
                    'message' => 'The given data was invalid',
                    'errors' => [
                        'name' => '«Наименование» должно быть уникальным'
                    ]
                ];
            }
        }
    }

    public static function prepareListing($data, $countData)
    {
        $result = [];
        $result['pages'] = ceil($countData / 15);
        $result['countData'] = $countData;
        $result['data'] = TypeContentResource::collection($data);
        return $result;
    }
}
