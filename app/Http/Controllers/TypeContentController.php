<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeContentRequest;
use App\Models\ElementContent;
use App\Models\Icons;
use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nette\Utils\Type;


class TypeContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    //получение вьюшки для фронта
    public function index()
    {
        return view('type_content.index');
    }

    public function viewNew($id)
    {
        return view('type_content.view-new');
    }

    //получение списка
    public function getListTypeContent()
    {
        if (Auth::guard('web')->check()) {
            $type_contents = TypeContent::with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name', 'desc')->get()->unique('id_global'); //все уникальные
            $ids = [];
            foreach ($type_contents as $type_content) {
                $ids[] = TypeContent::where('id_global', $type_content->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first()->id;
            }
            $type_contents = TypeContent::whereIn('id', $ids)->orderBy('created_at', 'asc')->get();
            return response()->json($type_contents);
        } else {
            if (Auth::guard('api')->check()) {
                $type_contents = TypeContent::with('created_authors:id,name')->with('updated_authors:id,name')->orderBy(
                    'name',
                    'desc'
                )->get();
                return response()->json($type_contents);
            }
        }
    }

    public function getTypeContentID($id)
    {
        $type_content = TypeContent::find($id);
        return response()->json($type_content);
    }



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
        $check_api = $model->checkingApiUrl($request->api_url);
        $check_name = $model->checkingName($request->name);
        if ($check_name) {
            return response()->json($check_name, 422);
        }
        if ($check_api) {
            return response()->json($check_api, 422);
        }
        if (Auth::guard('web')->check()) {
            $new_type_content = TypeContent::create([
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
            return response()->json($new_type_content);
        } elseif (Auth::guard('api')->check()) {
            $new_type_content = TypeContent::create([
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
            $type_content = TypeContent::find($new_type_content->id)->with('created_authors:id,name')->with('updated_authors:id,name')->get();
            return response()->json($type_content);
        }
    }

    public function update(TypeContentRequest $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $type = TypeContent::where('id', $request->id)->first();
            if ($type->status === 'Draft' or $type->status === 'Archive') {
                $other_published = TypeContent::where(['id_global' => $type->id_global, 'status' => 'Published'])->first();
                if (isset($other_published)) {
                    $other_published->status = 'Archive';
                    $other_published->save();
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
            $type_content = TypeContent::where('id', $request->id)->with('created_authors:id,name')->with('updated_authors:id,name')->first();
            return response()->json($type_content);
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
                $type_content = TypeContent::where('id', $type->id)->with('created_authors:id,name')->with('updated_authors:id,name')->first();
                return response()->json($type_content);
            }
        }
    }
    
    public function enter($id)
    {
        $element_content = ElementContent::find($id);

        $type_content = TypeContent::find($element_content->type_content_id);
        $body = json_decode($type_content->body);
        return view('type_content.enter', [
            'body' => $body,
            'type_content' => $type_content,
            'element_content' => $element_content
        ]);
    }
    public function destroy($id)
    {
        $type_content = TypeContent::find($id);
        if ($type_content) {
            $type_content->delete();
            return response()->json('item was deleted');
        } else {
            return response()->json('item not found');
        }
    }

    public function getAllVersionTypeContent($id)
    {
        $id_global = TypeContent::find($id)->id_global;
        $type_content = TypeContent::where('id_global', $id_global)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
        return response()->json($type_content);
    }

    public function getAllVersion($id)
    {
        return view('type_content.all-version-type-content');
    }
    public function View($id)
    {
        if (Auth::guard('web')->check()) {
            $type_content = TypeContent::where('id_global', $id)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->first();
            return view('type_content.view')->with('type_content', $type_content);
        } else {
            if (Auth::guard('api')->check()) {
                $type_content = TypeContent::where('id_global', $id)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
                return response()->json($type_content);
            } else {
                return response()->json('item not found');
            }
        }
    }
    public function createNewVersion($id, $parametr)
    {
        $type = TypeContent::find($id);
        $exist_other_draft = TypeContent::where(['id_global' => $type->id_global, 'status' => 'Draft'])->count();
        if ($exist_other_draft) {
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
        $type_content = TypeContent::find($request->id);
        if (!empty($type_content)) {
            $type_content->body = json_encode($request->body);
            $type_content->save();
            return response()->json($type_content);
        } else {
            return response()->json('object not found');
        }
    }
    public function getBody($id)
    {
        $type_content = TypeContent::find($id);
        if (!empty($type_content)) {
            return response()->json(json_decode($type_content->body));
        } else {
            return response()->json('object not found');
        }
    }
}
