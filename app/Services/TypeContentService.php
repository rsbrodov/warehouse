<?php
namespace App\Services;

use App\Http\Requests\TypeContentRequest;
use App\Models\DictionaryElement;
use App\Models\ElementContent;
use App\Models\Icons;
use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $model = new TypeContent();
        $checkApi = $model->checkingApiUrl($request->api_url);
        $checkName = $model->checkingName($request->name);
        if ($checkName) {
            return response()->json($checkName, 422);
        }
        if ($checkApi) {
            return response()->json($checkApi, 422);
        }
        if (Auth::guard('web')->check()) {
            $newTypeContent = TypeContent::create([
                'id_global' => Str::uuid()->toString(),
                'name' => $request->name,
                'description' => $request->description,
                'owner' => $request->owner,
                'active_from' => date_create($request->active_from),
                'active_after' => date_create($request->active_after),
                'status' => 'DRAFT',
                'version_major' => '1',
                'version_minor' => '0',
                'icon' => $request->icon,
                'api_url' => $request->api_url,
                'based_type' => null,
                'created_author' => Auth::guard('web')->user()->id,
                'updated_author' => Auth::guard('web')->user()->id
            ]);
            return response()->json($newTypeContent);
        } elseif (Auth::guard('api')->check()) {
            $newTypeContent = TypeContent::create([
                'id_global' => Str::uuid()->toString(),
                'name' => $request->name,
                'description' => $request->description,
                'owner' => $request->owner,
                'icon' => $request->icon,
                'active_from' => $request->active_from,
                'active_after' => $request->active_after,
                'api_url' => $request->api_url,
                'created_author' => Auth::guard('api')->user()->id,
                'updated_author' => Auth::guard('api')->user()->id
            ]);
            $typeContent = TypeContent::find($newTypeContent->id)->with('created_authors:id,name')->with('updated_authors:id,name')->get();
            return response()->json($typeContent);
        }
    }

    public function update(TypeContentRequest $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $type = TypeContent::where('id', $request->id)->first();
            if ($type->status === 'Draft' or $type->status === 'Archive') {
                $otherPublished = TypeContent::where(['id_global' => $type->id_global, 'status' => 'Published'])->first();
                if (isset($otherPublished)) {
                    $otherPublished->status = 'Archive';
                    $otherPublished->save();
                }
            }
            $type->name = $request->name;
            $type->api_url = $request->api_url;
            $type->description = $request->description;
            $type->active_from = date_format(date_create($request->active_from), 'Y-m-d H:m:s');
            $type->active_after = date_format(date_create($request->active_after), 'Y-m-d H:m:s');
            $type->icon = $request->icon;
            $type->status = $request->status;
            $type->owner = $request->owner;
            $type->updated_author = Auth::guard('web')->user()->id;
            $type->save();
            $typeContent = TypeContent::where('id', $request->id)->with('created_authors:id,name')->with('updated_authors:id,name')->first();
            return response()->json($typeContent);
        } else {
            if (Auth::guard('api')->check()) {
                $type = TypeContent::find($id);
                $type->name = $request->name;
                $type->api_url = $request->api_url;
                $type->description = $request->description;
                $type->active_from = date_format(date_create($request->active_from), 'Y-m-d H:m:s');
                $type->active_after = date_format(date_create($request->active_after), 'Y-m-d H:m:s');
                $type->icon = $request->icon;
                $type->owner = $request->owner;
                $type->updated_author = Auth::guard('api')->user()->id;
                $type->updated_author = Auth::guard('api')->user()->id;
                $type->save();
                $typeContent = TypeContent::where('id', $type->id)->with('created_authors:id,name')->with('updated_authors:id,name')->first();
                return response()->json($typeContent);
            }
        }
    }

    public function destroy($id)
    {
        $typeContent = TypeContent::find($id);
        if ($typeContent) {
            $typeContent->delete();
            return response()->json('item was deleted');
        } else {
            return response()->json('item not found');
        }
    }

    //получение списка
    public function getListTypeContent()
    {
        if (Auth::guard('web')->check()) {
            $typeContents = TypeContent::with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name', 'desc')->get()->unique('id_global'); //все уникальные
            $ids = [];
            foreach ($typeContents as $typeContent) {
                $ids[] = TypeContent::where('id_global', $typeContent->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first()->id;
            }
            $typeContents = TypeContent::whereIn('id', $ids)->orderBy('created_at', 'asc')->get();
            return response()->json($typeContents);
        } else {
            if (Auth::guard('api')->check()) {
                $typeContents = TypeContent::with('created_authors:id,name')->with('updated_authors:id,name')->orderBy(
                    'name',
                    'desc'
                )->get();
                return response()->json($typeContents);
            }
        }
    }

    public function getTypeContentID($id)
    {
        $typeContent = TypeContent::find($id);
        return response()->json($typeContent);
    }

    public function getElementContentID($id)
    {
        $elementContent = ElementContent::where('id', $id)->with('type_contents')->with('created_authors:id,name')->first();
        return response()->json($elementContent);
    }

    public function getAllVersionTypeContent($id)
    {
        $idGlobal = TypeContent::find($id)->id_global;
        $typeContent = TypeContent::where('id_global', $idGlobal)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
        return response()->json($typeContent);
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
                'code'      =>  422,
                'message'   =>  'The given data was invalid',
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
                    ->orderBy('version_major','desc')
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
                    'code'      =>  422,
                    'message'   =>  'The given data was invalid',
                    'errors' => [
                        'version_error' => 'Ошибка в формировании новой версии типа контента, перезагрузите страницу и попробуйте снова.'
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
        /*$errors = $elementContent->validateElementContent($elementContent->type_content_id, $request->body);
        if($errors){
            return response()->json([
                'message' => $errors
            ], 400);
        }*/
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
        foreach ($body as $row){
            foreach ($row as $column){
                foreach ($column as $key=>$element){
                    if($key == 'dictionary_id'){
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
}
