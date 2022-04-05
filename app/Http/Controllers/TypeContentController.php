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

    public function index()
    {
        if (Auth::guard('web')->check()) {
            $type_contents = TypeContent::where(['created_author' => Auth::guard('web')->user()->id])->with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name', 'desc')->get()->unique('id_global');//все уникальные
            $ids = [];
            foreach ($type_contents as $type_content){
                $ids[] = TypeContent::where('id_global', $type_content->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first()->id;
            }
            //dd($ids);
            $type_contents = TypeContent::whereIn('id', $ids)->get();
            return view('type_content.index')->with('type_contents', $type_contents);
        } else {
            if (Auth::guard('api')->check()) {
                $type_contents = TypeContent::where(['created_author' => Auth::guard('api')->user()->id])->with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name',
                    'desc')->get();
                return response()->json($type_contents);
            } else {
                return 'not auth';
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::guard('web')->check()) {
            $icons = Icons::all();
            return view('type_content.create')->with('icons', $icons);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeContentRequest $request)
    {
        $model = new TypeContent();
        if (Auth::guard('web')->check()) {
            if (!$model->checkingApiUrl(str_slug($request->input('name')))) {
                $new_type_content = TypeContent::create([
                    'id_global' => Str::uuid()->toString(),
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'owner' => '7856',
                    'active_from' => $request->input('active_from'),
                    'active_after' => $request->input('active_after'),
                    'status' => 'DRAFT',
                    'version_major' => '1',
                    'version_minor' => '0',
                    'icon' => $request->input('icon'),
                    'api_url' => str_slug($request->input('api_url')),
                    'based_type' => null,
                    'created_author' => Auth::guard('web')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id
                ]);
                return redirect()->route('type-content.index')->with('success',
                    'Тип контента ' . $new_type_content->name . ' успешно создан');
            } else {
                return redirect()->back()->with('error', 'Что-то пошло не так');
            }
        } elseif (Auth::guard('api')->check()) {

            if (!$model->checkingApiUrl(str_slug($request->input('name')))) {
                $new_type_content = TypeContent::create([
                    'id_global' => Str::uuid()->toString(),
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'owner' => Str::uuid()->toString(),
                    'icon' => $request['icon'],
                    'active_from' => $request['active_from'],
                    'active_after' => $request['active_after'],
                    'api_url' => str_slug($request->input('name')),
                    'body' => $request['body'],
                    'created_author' => Auth::guard('api')->user()->id,
                    'updated_author' => Auth::guard('api')->user()->id
                ]);
                $type_content = TypeContent::find($new_type_content->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
                return response()->json($type_content);
            } else {
                return response()->json([
                    'message' => 'Unprocessable Entity (validation failed "API URL")'
                ], 422);
            }


            $new_type_content = TypeContent::create([
                'id_global' => Str::uuid()->toString(),
                'name' => $request['name'],
                'description' => $request['description'],
                'owner' => Str::uuid()->toString(),
                'icon' => $request['icon'],
                'active_from' => $request['active_from'],
                'active_after' => $request['active_after'],
                'api_url' => $request->input('api_url'),
                'body' => $request['body'],
                'created_author' => Auth::guard('api')->user()->id,
                'updated_author' => Auth::guard('api')->user()->id
            ]);
            $type_content = TypeContent::find($new_type_content->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($type_content);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function show(TypeContent $typeContent, $id)
    {
        $type_content = TypeContent::find($id);
        return view('type_content.show')->with('type_content', $type_content);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeContent $typeContent, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $type_content = TypeContent::where('id', $id)->first();
                $icons = Icons::all();
                return view('type_content.edit', ['type_content' => $type_content, 'icons' => $icons]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function update(TypeContentRequest $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $type = TypeContent::find($id);
                if (!$type->checkingApiUrl($request['api_url'], $type['id_global'])) {
                    $type->name = $request['name'];
                    $type->api_url = $request['api_url'];
                    $type->description = $request['description'];
                    $type->active_from = $request['active_from'];
                    $type->active_after = $request['active_after'];
                    $type->status = $request['status'];
                    $type->icon = $request['icon'];
                    $type->body = $request['body'];
                    $type->updated_author = Auth::guard('web')->user()->id;
                    $type->save();
                    return redirect()->route('type-content.index')->with('success',
                        'Тип ' . $type->name . ' успешно отредактирован');
                } else {
                    return redirect()->back()->with('error', 'Что-то пошло не так');
                }
            }
        } else {
            if (Auth::guard('api')->check()) {

                $type = TypeContent::find($id);
                //print_r($id);exit;
                $type->name = $request['name'];
                $type->description = $request['description'];
                $type->owner = $request['owner'];
                $type->icon = $request['icon'];
                $type->active_from = $request['active_from'];
                $type->active_after = $request['active_after'];
                $type->api_url = $request['api_url'];
                $type->body = $request['body'];
                $type->updated_author = Auth::guard('api')->user()->id;
                $type->save();
                $type_content = TypeContent::find($type->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
                return response()->json($type_content);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
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

    public function createNewVersion($id, $parametr)
    {
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

    public function getIcons()
    {
        //dd('test');
//        return response()->json(Icons::all());
        return 'Success!';
        //return redirect()->route('type-content.index')->with('success', 'Создано' . $i . 'иконок');
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

    public function createElemen(TypeContent $typeContent, $id, $type)
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
    }
}
