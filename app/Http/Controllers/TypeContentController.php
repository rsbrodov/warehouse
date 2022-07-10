<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeContentRequest;
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
            $type_contents = TypeContent::with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name', 'desc')->get()->unique('id_global');//все уникальные
            $ids = [];
            foreach ($type_contents as $type_content){
                $ids[] = TypeContent::where('id_global', $type_content->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first()->id;
            }
            $type_contents = TypeContent::whereIn('id', $ids)->orderBy('created_at', 'asc')->get();
            return response()->json($type_contents);
        } else {
            if (Auth::guard('api')->check()) {
                $type_contents = TypeContent::with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name',
                    'desc')->get();
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
        if($check_api){
            return response()->json($check_api, 422);
        }
        if($check_name){
            return response()->json($check_name, 422);
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



    public function show(TypeContent $typeContent, $id)
    {
        $type_content = TypeContent::find($id);
        return view('type_content.show')->with('type_content', $type_content);
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

    public function enter($id){
        $type_content = TypeContent::find($id);

        $object = (object)[
            [
                "idRow" => "1",
                "col"   => [
                    [
                        "idCol"   => "row1/col1",
                        "element" => [
                            [
                                "id"       => 1,
                                "type"     => "text",
                                "order"    => 1,
                                "title"    => "Название единорога",
                                "name"     => "name",
                                "required" => true,
                            ],
                            [
                                "id"       => 2,
                                "type"     => "text",
                                "order"    => 3,
                                "title"    => "Цена единорога",
                                "required" => true,
                                "name"     => "cost",
                            ],
                        ],
                    ],
                ],
            ],
            [
                "idRow" => "2",
                "col"   => [
                    [
                        "idCol"   => "row2/col2",
                        "element" => [
                            [
                                "id"         => 1,
                                "type"       => "checkbox",
                                "order"      => 4,
                                "title"      => "Пол единорога (БД)",
                                "name"       => "sex",
                                "required"   => true,
                                "parameters" => "9ca1da69-7104-4b15-ad89-a645d143abef",
                            ],
                            [
                                "id"       => 3,
                                "type"     => "text",
                                "order"    => 2,
                                "title"    => "Размер единорога",
                                "name"     => "size",
                                "required" => true,
                            ],
                            [
                                "id"       => 2,
                                "type"     => "textarea",
                                "order"    => 2,
                                "title"    => "Описание единорога",
                                "name"     => "description",
                                "required" => true,

                            ],
                        ],
                    ],
                ],
            ],
            [
                "idRow" => "2",
                "col"   => [
                    [
                        "idCol"   => "row2/col2",
                        "element" => [
                            [
                                "id"       => 1,
                                "type"     => "text",
                                "order"    => 4,
                                "title"    => "Местоположение единорога",
                                "name"     => "location",
                                "required" => true,
                            ],
                            [
                                "id"         => 3,
                                "type"       => "select",
                                "order"      => 2,
                                "title"      => "Наличие разрешения на управление (БД)",
                                "name"       => "permission",
                                "required"   => true,
                                "parameters" => "dd882515-16ea-4d3a-ba87-6cb05126702e",
                            ],
                            [
                                "id"         => 2,
                                "type"       => "select",
                                "order"      => 2,
                                "title"      => "Тип допуска (БД)",
                                "name"       => "admission",
                                "required"   => true,
                                "parameters" => "a0114e96-d434-4d91-845b-0b76eb531cef",
                            ],
                            //                            [
                            //                                "id" => 2,
                            //                                "type" => "select",
                            //                                "order" => 2,
                            //                                "title" => "Тип допуска",
                            //                                "name" => "description",
                            //                                "required" => true,
                            //                                "parameters" =>  [
                            //                                    [
                            //                                        "label"=> "Полный",
                            //                                        "value"=>"Full",
                            //                                    ],
                            //                                    [
                            //                                        "label"=> "Ограниченный",
                            //                                        "value"=>"Partly",
                            //                                    ],
                            //                                    [
                            //                                        "label"=> "Отсутствует",
                            //                                        "value"=>"Denied",
                            //                                    ]
                            //                                ]
                            //                            ],
                            //                            [
                            //                                "id" => 2,
                            //                                "type" => "radio",
                            //                                "order" => 2,
                            //                                "title" => "Предпочитаемый способ езды",
                            //                                "name" => "rideType",
                            //                                "required" => true,
                            //                                "parameters" =>  [
                            //                                    [
                            //                                        "label"=> "Шаг",
                            //                                        "value"=>"Step",
                            //                                    ],
                            //                                    [
                            //                                        "label"=> "Рысь",
                            //                                        "value"=>"Trott",
                            //                                    ],
                            //                                    [
                            //                                        "label"=> "Галоп",
                            //                                        "value"=>"Gallop",
                            //                                    ],
                            //                                    [
                            //                                        "label"=> "Карьер",
                            //                                        "value"=>"Quarry",
                            //                                    ]
                            //                                ]
                            //                            ],
                            [
                                "id"         => 2,
                                "type"       => "radio",
                                "order"      => 2,
                                "title"      => "Предпочитаемый способ езды (БД)",
                                "name"       => "rideType",
                                "required"   => true,
                                "parameters" => "81f52fa0-e5e1-41d4-a530-0102e4d0bbdb",
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $type_content->body = serialize($object);
        $type_content->save();


        $type_content = TypeContent::find($id);
        $rows = unserialize($type_content->body);
        return view('type_content.enter', ['rows' => $rows]);
      /*  foreach ($rows as $row){
            foreach ($row['col'] as $column){
                foreach ($column['element'] as $field){
                    print_r($field);
                    print_r('<pre>');
                }
            }
        }*/
    }



    public function publish($id){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $type_content = TypeContent::where('id', $id)->first();
                if ($type_content->status == 'Draft') {
                    $type_content->status = 'Published';
                }
                $type_content->save();
                return redirect()->route('type-content.get-all-version', $type_content->id_global)->with('success', 'Тип ' . $type_content->name . ' успешно опубликован');
            }
        }
    }
    public function destroy($id)
    {
        if (Auth::guard('web')->check()) {
            $type_content = TypeContent::find($id);
            if ($type_content) {
                $type_content->delete();
                return redirect()->route('type-content.index')->with('success',
                    'Тип ' . $type_content->name . ' успешно удален');
            } else {

            }
        } else {
            if (Auth::guard('api')->check()) {
                $type_content = TypeContent::find($id);
                if ($type_content) {
                    $type_content->delete();
                    return response()->json('item was deleted');
                }
            } else {
                return response()->json('item not found');
            }
        }
    }

    public function getAllVersionTypeContent($id)
    {
        if (Auth::guard('web')->check()) {
            $type_contents = TypeContent::where('id_global', $id)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
            return view('type_content.all-version-type-content')->with('type_contents', $type_contents);
        } else {
            if (Auth::guard('api')->check()) {
                $type_content = TypeContent::where('id_global', $id)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
                return response()->json($type_content);
            } else {
                return response()->json('item not found');
            }
        }
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
        //todo проверить эту портянку на то что при создании новой версии и существующем черновике выдается ошибка!
        $type = TypeContent::find($id);
        $exist_other_draft = TypeContent::where(['id_global'=>$type->id_global, 'status' => 'Draft'])->count();
        if($exist_other_draft){
            alert('ERROR!');
            return redirect()->back()->with('error', 'Черновик уже существует. Удалите его или отредактируйте.');
        }
        // endtodo: вот до сюдова

        //проверка параметров
        if ($parametr == 'major' || $parametr == 'minor') {
            if ($parametr == 'major') {
                //если мажор то мы просто создаем дубликат наивысшей строки по version_major
                $typeContent = TypeContent::where('id_global', $id)->orderBy('version_major', 'desc')->first();
                //replicate - встроенный метод дублирования в laravel
                $newTypeContent = $typeContent->replicate();//тут лежит наш новый объект
                $newTypeContent->version_major = $typeContent->version_major + 1;//изменяем объект с учетом наших параметров затем сохраняем
                $newTypeContent->version_minor = 0;
            } else {
                //если минор то просто ищим наивысшей строки по version_major и version_minor ну и изменяем версию

                $typeContent = TypeContent::where('id_global', $id)->orderBy('version_major',
                    'desc')->orderBy('version_minor', 'desc')->first();

                $newTypeContent = $typeContent->replicate();
                $newTypeContent->version_minor = $typeContent->version_minor + 1;
            }
            if ($newTypeContent->save()) {

                return redirect()->route('type-content.get-all-version', $typeContent->id_global)->with('success',
                    'Новая версия успешно создана');

            } else {
                return redirect()->back()->with('error', 'Что-то пошло не так');
            }
        } else {
            return redirect()->back()->with('error', 'Что-то пошло не так');
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
       /* $data = [
            0 => [
                'type' => 'date',
                'name' => 'Дата и время',
                'null' => 'null',
                'sort' => '0',
            ],
            1 => [
                'type' => 'input',
                'name' => 'Введите значение',
                'null' => 'null',
                'typeInput' => 'text',
                'sort' => '1',
            ],
            2 => [
                'type' => 'textarea',
                'name' => 'Ваш ответ',
                'null' => 'null',
                'row' => '1',
                'sort' => '2',
            ],
        ];
        array_push($data, [
            'name' => 'Ваш ответ',
            'null' => 'null',
            'row' => '1',
            'sort' => '3',
        ]);*/
        //$typeContent = TypeContent::find($id);
       /* print_r('<pre>');
        print_r($data);
        print_r('</pre>');

        print_r($data);
        //print_r($typeContent['body']);
        exit();*/
        //$body = ()
        $typeContent = TypeContent::find($id);
        $body = ($typeContent->body) ? json_decode($typeContent->body): null;
        return view('type_content.descript-version-type-content', [
            'id' => $id,
            'typeContent' => $typeContent,
            'body' => $body,
        ]);
    }

    public function createDescription(TypeContent $typeContent, $request)
    {
        //$typeContent тут наша модель
        $data = [
            0 => [
                'type' => 'date',
                'name' => 'Дата и время',
                'null' => 'null',
                'sort' => '0',
            ],
            1 => [
                'type' => 'input',
                'name' => 'Введите значение',
                'null' => 'null',
                'typeInput' => 'text',
                'sort' => '1',
            ],
            2 => [
                'type' => 'textarea',
                'name' => 'Ваш ответ',
                'null' => 'null',
                'row' => '1',
                'sort' => '2',
            ],
        ];
        array_push($data, [
            'name' => 'Ваш ответ',
            'null' => 'null',
            'row' => '1',
            'sort' => '3',
            'sort' => '3',
        ]);
        //$typeContent = TypeContent::find($id);
        print_r('<pre>');
        print_r($data);
        print_r('</pre>');

        print_r($data);
        //print_r($typeContent['body']);
        exit();
        //$body = ()
        return view('type_content.descript-version-type-content', [
            'id' => $id,
            'body' => $body,
        ]);
    }

    /*public function createElemen(TypeContent $typeContent, $id, $type)
    {
        //проверить каждый ШАГ!!!
        $typeContent = TypeContent::find($id);
        $data = ($typeContent->body) ? json_decode($typeContent->body): [];

        switch ($type) {
            case 'input':
                array_push($data, [
                    'type' => 'input',
                    'name' => 'Значение',
                    'null' => 'null',
                    'typeInput' => 'text',
                    'sort' => '0',
                ]);
                break;
            case 'textarea':
                array_push($data, [
                    'type' => 'textarea',
                    'name' => 'Текстовое поле',
                    'null' => 'null',
                    'row' => '1',
                    'sort' => '0',
                ]);
                break;
            case 'date':
                array_push($data, [
                    'type' => 'date',
                    'name' => 'Дата и время',
                    'null' => 'null',
                    'sort' => '0',
                ]);
                break;
        }

        //$typeContent->where('id', $id)->update(['body' => json_encode($data)]);

        $typeContent->body = json_encode($data);
        $typeContent->save();

        //return redirect()->route('users.index', $id);
        return view('type_content.descript-version-type-content', [
            'id' => $id,
            'typeContent' => $typeContent,
            'body' => json_decode($typeContent->body),
        ]);
    }*/

    public function getIcons()
    {
        $icons = Icons::all();
        return response()->json($icons);

    }

    public function saveBody(Request $request)
    {
        $type_content = TypeContent::find($request->id);
        if(!empty($type_content)){
            $type_content->body = json_encode($request->body);
            $type_content->save();
            return response()->json($type_content);
        }else{
            return response()->json('object not found');
        }
    }
    public function bodyType($id)
    {
        $type_content = TypeContent::find($id);
        if(!empty($type_content)){
            return response()->json(json_decode($type_content->body));
        }else{
            return response()->json('object not found');
        }
    }
}
