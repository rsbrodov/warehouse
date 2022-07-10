<?php

namespace App\Http\Controllers;

use App\Models\ElementContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ElementContentController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function index()
    {
        if (Auth::guard('web')->check()) {
            if (Auth::guard('web')->check()) {
                $element_contents = ElementContent::  where(['created_author' => Auth::guard('web')->user()->id])
                                                    ->where('type_content_id', request('type_content_id'))
                                                    ->with('created_authors:id,name')
                                                    ->with('updated_authors:id,name')
                                                    ->orderBy('label', 'desc')
                                                    ->get()
                                                    ->unique('id_global');//все уникальные
                $ids = [];
                foreach ($element_contents as $element_content){
                    $ids[] = ElementContent::where('id_global', $element_content->id_global)
                    ->orderBy('version_major', 'desc')
                    ->orderBy('version_minor', 'desc')
                    ->first()->id;
                }
                $element_contents = ElementContent::whereIn('id', $ids)->orderBy('created_at', 'asc')->get();
                return view('element_content.index')->with('element_contents', $element_contents);
                //return response()->json($element_contents);
            } else {
                if (Auth::guard('api')->check()) {
                    $element_contents = ElementContent::where(['created_author' => Auth::guard('api')->user()->id])->where('type_content_id', request('type_content_id'))->with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('label',
                        'desc')->get();
                    return response()->json($element_contents);
                }
            }
            //$element_contents = ElementContent::where('owner', Auth::guard('web')->id())->where('element_content_id', request('element_content_id'))->get();
            //return view('element_content.index')->with('element_contents', $element_contents);
        }
    }
    public function create()
    {
        if (Auth::guard('web')->check()) {
            return view('element_content.create');
        }
    }
    public function store(Request $request)
    {
        if (Auth::guard('web')->check()) {
            //dd('ElementContent '.request('type_content_id'));
            $new_element_content = ElementContent::create(
                [
                    'id_global'      => Str::uuid()->toString(),
                    'type_content_id'=> request('type_content_id'),
                    'label'          => $request->label,
                    'owner'          => Auth::guard('web')->user()->id,
                    'active_from'    => date_create($request->active_from),
                    'active_after'   => date_create($request->active_after),
                    'status'         => 'DRAFT',
                    'version_major'  => '1',
                    'version_minor'  => '0',
                    'url'            => str_slug($request->url),
                    'based_element'  => null,
                    'body'  => '',
                    'created_author' => Auth::guard('web')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id,
                ]
            );
            return redirect()->route('element-content.index', request('type_content_id'))->with('success', 'Элемент ' . $new_element_content->label . ' успешно добавлен');
        }
    }
    public function edit($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $element_content = ElementContent::where('id', $id)->first();
                return view('element_content.edit', ['element_content' => $element_content]);
            }
        }
    }
    public function update(Request $request)
    {
        if (Auth::guard('web')->check()) {
            //$element_content = ElementContent::find($id)->with('created_authors:id,name')->with('updated_authors:id,name');
            $ac_fr = date_create($request['active_from']);
            date_format($ac_fr, 'Y-m-d H:m:s');
            $ac_af = date_create($request['active_after']);
            date_format($ac_af, 'Y-m-d H:m:s');
            $element = ElementContent::find(request('id'));
            if ($element->status === 'Draft' or $element->status === 'Archive') {
                $other_published = ElementContent::where(['id_global' => $element->id_global, 'status' => 'Published'])->first();
                if (isset($other_published)) {
                    $other_published->status = 'Archive';
                    $other_published->save();
                }
            }
            if (!$element->checkingApiUrl($request['url'], $element['id_global'])) {
                $element->label = $request['label'];
                $element->url = $request['url'];
                $element->active_from = $ac_fr;
                $element->active_after = $ac_af;
                $element->status = $request['status'];
                $element->body = $request['body'];
                $element->updated_author = Auth::guard('web')->user()->id;
                $element->save();
                $element_content = ElementContent::find(request('id'))->with('created_authors:id,name')->with('updated_authors:id,name');
                return redirect()->route('element-content.index', $element->type_content_id)->with('success', 'Элемент ' . $element->label . ' успешно отредактирован');
                //return response()->json($element_content);
            } else {
                return response()->json(123);
            }
        } else {
            if (Auth::guard('api')->check()) {
                $element = ElementContent::find(request('id'));
                 $element->label = $request['label'];
                 $element->owner = $request['owner'];
                 $element->active_from = $request['active_from'];
                 $element->active_after = $request['active_after'];
                 $element->url = $request['url'];
                 $element->body = $request['body'];
                 $element->updated_author = Auth::guard('api')->user()->id;
                 $element->save();
                 $element_content = ElementContent::find($element->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
                return response()->json($element_content);
            }
        }
    }
    public function destroy(){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $destroy_elem = ElementContent::where('id', request('id'))->first();
                $type_content_id = $destroy_elem->type_content_id;
                $message = ''; $type_message = 'success';
                if ($destroy_elem->status == 'Destroy') {
                    $exist_other_draft = ElementContent::where(['id_global'=>$destroy_elem->id_global, 'status' => 'Draft'])->count();
                    if($exist_other_draft){
                        return redirect()->back()->with('error', 'Вы не сможете восстановить этот элемент, пока не закончите работу с существующим черновиком');
                    }
                    $destroy_elem->status = 'Draft';
                    $message = 'Элемент ' . $destroy_elem->label . ' восстановлен';
                } else {
                    $destroy_elem->status = 'Destroy';
                    $message = 'Элемент ' . $destroy_elem->label . ' удален';
                    $type_message = 'info';
                }
                $destroy_elem->save();
                return redirect()->route('element-content.index', $type_content_id)->with($type_message, $message);
            }
        }
    }
    public function createNewVersion($id, $parameter)
    {
        $element = ElementContent::find($id);
        $exist_other_draft = ElementContent::where(['id_global'=>$element->id_global, 'status' => 'Draft'])->count();
        if($exist_other_draft){
            return redirect()->back()->with('error', 'Черновик уже существует. Удалите его или отредактируйте.');
        }
        //проверка параметров
        if ($parameter == 'major' || $parameter == 'minor') {
            if ($parameter == 'major') {
                //если мажор то мы просто создаем дубликат наивысшей строки по version_major
                $elementContent = ElementContent::where('id_global', $element->id_global)->orderBy('version_major', 'desc')->first();
                //replicate - встроенный метод дублирования в laravel
                $newElementContent = $elementContent->replicate();//тут лежит наш новый объект
                $newElementContent->version_major = $elementContent->version_major + 1;//изменяем объект с учетом наших параметров затем сохраняем
                $newElementContent->version_minor = 0;
            } else {
                //если минор то просто ищим наивысшей строки по version_major и version_minor ну и изменяем версию
                $elementContent = ElementContent::where('id_global', $element->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first();
                $newElementContent = $elementContent->replicate();
                $newElementContent->version_minor = $elementContent->version_minor + 1;
            }
            $newElementContent->status = 'Draft';
            $newElementContent->based_element = $element->id;
            if ($newElementContent->save()) {
               return redirect()->route('element-content.get-all-version', $elementContent->id_global)->with('success', 'Новая версия успешно создана');
                // return redirect()->route('element-content.index', $element->type_content_id)->with('success', 'Новая версия успешно создана');
            } else {
                return redirect()->back()->with('error', 'Что-то пошло не так');
            }
        } else {
            return redirect()->back()->with('error', 'Что-то пошло не так');
        }
    }
    
    public function getAllVersionElementContent()
    {
        if (Auth::guard('web')->check()) {
            $element_contents = ElementContent::where('id_global', request('id_global'))->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
            return view('element_content.all-version-element-content')->with('element_contents', $element_contents);
        } else {
            if (Auth::guard('api')->check()) {
                $element_content = ElementContent::where('id_global', request('id_global'))->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
                return response()->json($element_content);
            } else {
                return response()->json('item not found');
            }
        }
    }
}
