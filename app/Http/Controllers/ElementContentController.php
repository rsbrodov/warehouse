<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElementContentRequest;
use App\Models\ElementContent;
use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ElementContentController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function findElementContentID($id)
    {
        if (Auth::guard('web')->check()) {
            $elementContents = ElementContent::
                where('type_content_id', $id)
                ->with('created_authors:id,name')
                ->with('updated_authors:id,name')
                ->orderBy('created_at', 'asc')
                ->get()
                ->unique('id_global');//все уникальные
            $ids = [];
            return $elementContents;
            foreach ($elementContents as $elementContent){
                $ids[] = ElementContent::where('id_global', $elementContent->id_global)
                ->orderBy('version_major', 'desc')
                ->orderBy('version_minor', 'desc')
                ->first()->id;
            }
            $elementContents = ElementContent::whereIn('id', $ids)->orderBy('created_at', 'asc')->get();
            return response()->json($elementContents);
            /* else {
                if (Auth::guard('api')->check()) {
                    $element_contents = ElementContent::where(['created_author' => Auth::guard('api')->user()->id])->where('type_content_id', request('type_content_id'))->with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('label',
                        'desc')->get();
                    return response()->json($element_contents);
                }
            }*/

        }
    }

    public function index()
    {
        return view('element_content.index');
    }

    public function store(ElementContentRequest $request)
    {
        //return $request;
        if (Auth::guard('web')->check()) {
            $newElementContent = ElementContent::create(
                [
                    'id_global'      => Str::uuid()->toString(),
                    'type_content_id'=> request('type_content_id'),
                    'label'          => $request->label,
                    'description'          => $request->description,
                    'active_from'    => date_create($request->active_from),
                    'active_after'   => date_create($request->active_after),
                    'status'         => 'DRAFT',
                    'version_major'  => '1',
                    'version_minor'  => '0',
                    'api_url'            => str_slug($request->api_url),
                    'based_element'  => null,
                    'body'  => '',
                    'created_author' => Auth::guard('web')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id,
                ]
            );

            return response()->json($newElementContent);
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
            //$element_content = ElementContent::find($id)->with('created_authors:id,name')->with('updated_authors:id,name');
            $acFr = date_create($request['active_from']);
            date_format($acFr, 'Y-m-d H:m:s');
            $acAf = date_create($request['active_after']);
            date_format($acAf, 'Y-m-d H:m:s');
            $element = ElementContent::find(request('id'));
            if ($element->status === 'Draft' or $element->status === 'Archive') {
                $otherPublished = ElementContent::where(['id_global' => $element->id_global, 'status' => 'Published'])->first();
                if (isset($otherPublished)) {
                    $otherPublished->status = 'Archive';
                    $otherPublished->save();
                }
            }
            if (!$element->checkingApiUrl($request['url'], $element['id_global'])) {
                $element->label = $request['label'];
                $element->url = $request['url'];
                $element->active_from = $acFr;
                $element->active_after = $acAf;
                $element->status = $request['status'];
                $element->body = $request['body'];
                $element->updated_author = Auth::guard('web')->user()->id;
                $element->save();
                $elementContent = ElementContent::find(request('id'))->with('created_authors:id,name')->with('updated_authors:id,name');
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
                 $elementContent = ElementContent::find($element->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
                return response()->json($elementContent);
            }
        }
    }
    public function destroy(){
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $destroyElem = ElementContent::where('id', request('id'))->first();
                $typeContentId = $destroyElem->type_content_id;
                $message = ''; $typeMessage = 'success';
                if ($destroyElem->status == 'Destroy') {
                    $existOtherDraft = ElementContent::where(['id_global'=>$destroyElem->id_global, 'status' => 'Draft'])->count();
                    if($existOtherDraft){
                        return redirect()->back()->with('error', 'Вы не сможете восстановить этот элемент, пока не закончите работу с существующим черновиком');
                    }
                    $destroyElem->status = 'Draft';
                    $message = 'Элемент ' . $destroyElem->label . ' восстановлен';
                } else {
                    $destroyElem->status = 'Destroy';
                    $message = 'Элемент ' . $destroyElem->label . ' удален';
                    $typeMessage = 'info';
                }
                $destroyElem->save();
                return redirect()->route('element-content.index', $typeContentId)->with($typeMessage, $message);
            }
        }
    }
    public function createNewVersion($id, $parameter)
    {
        $element = ElementContent::find($id);
        $existOtherDraft = ElementContent::where(['id_global'=>$element->id_global, 'status' => 'Draft'])->count();
        if($existOtherDraft){
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
            $elementContents = ElementContent::where('id_global', request('id_global'))->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
            return view('element_content.all-version-element-content')->with('element_contents', $elementContents);
        } else {
            if (Auth::guard('api')->check()) {
                $elementContent = ElementContent::where('id_global', request('id_global'))->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
                return response()->json($elementContent);
            } else {
                return response()->json('item not found');
            }
        }
    }
    public function saveDraft(Request $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $elementContent = ElementContent::find($id);
            $typeContent = TypeContent::find($elementContent->type_content_id);
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
}
