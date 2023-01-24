<?php
namespace App\Services;

use App\Http\Requests\ElementContentRequest;
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
        //return $request;
        if (Auth::guard('web')->check()) {
            $newElementContent = ElementContent::create(
                [
                    'id_global'      => Str::uuid()->toString(),
                    'type_content_id'=> request('type_content_id'),
                    'label'          => $request->label,
                    'description'          => $request->description,
                    'active_from'    => $request->active_from ? date_create($request->active_from) : null,
                    'active_after'   => $request->active_after ? date_create($request->active_after) : null,
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
            $element = ElementContent::find($request->id);
            if ($element->status === 'Draft' || $element->status === 'Archive') {
                $otherPublished = ElementContent::where(['id_global' => $element->id_global, 'status' => 'Published'])->first();
                if (isset($otherPublished)) {
                    $otherPublished->status = 'Archive';
                    $otherPublished->save();
                }
            }
            //if (!$element->checkingApiUrl($request['url'], $element['id_global'])) {
            $element->label = $request->label;
            $element->api_url = $request->api_url;
            $element->active_from = $request->active_from;
            $element->active_after = $request->active_after;
            $element->status = $request->status;
            $element->description = $request->description;
            $element->updated_author = Auth::guard('web')->user()->id;
            $element->save();
            $elementContent = ElementContent::find(request('id'))->with('created_authors:id,name')->with('updated_authors:id,name');

            // } else {
            return response()->json($elementContent);
            //}
        } /*else {
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
            }*/
        //}
    }
    /*public function destroy(){
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
    }*/

    public function destroy($id)
    {
        $typeContent = ElementContent::find($id);
        if ($typeContent) {
            $typeContent->delete();
            return response()->json('item was deleted');
        } else {
            return response()->json('item not found');
        }
    }

    public function findElementContentID($id)
    {
        if (Auth::guard('web')->check()) {
            $typeContent = TypeContent::find($id);
            $typeContents = TypeContent::where('id_global', $typeContent->id_global)->pluck('id');
            $elementContents = ElementContent::
            whereIn('type_content_id', $typeContents)
                ->with('created_authors:id,name')
                ->with('updated_authors:id,name')
                ->orderBy('created_at', 'asc')
                ->get()
                ->unique('id_global');//все уникальные
            return $elementContents;
        }
    }

    public function createNewVersion($id, $parametr)
    {
        $type = ElementContent::find($id);
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
                //если мажор то мы просто создаем дубликат наивысшей строки по version_major
                $typeContent = ElementContent::where('id_global', $type->id_global)
                    ->orderBy('version_major', 'desc')
                    ->first();
                $newTypeContent = $typeContent->replicate(); //тут лежит наш новый объект
                $newTypeContent->version_major = $typeContent->version_major + 1; //изменяем объект с учетом наших параметров затем сохраняем
                $newTypeContent->status = 'Draft';
                $newTypeContent->version_minor = 0;
            } else {
                //если минор то просто ищим наивысшей строки по version_major и version_minor ну и изменяем версию
                $typeContent = ElementContent::where('id_global', $type->id_global)
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
        $idGlobal = ElementContent::find($id)->id_global;
        $elementContent = ElementContent::where('id_global', $idGlobal)->orderBy('version_major', 'asc')->orderBy('version_minor', 'asc')->get();
        return response()->json($elementContent);

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
}
