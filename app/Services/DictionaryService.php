<?php

namespace App\Services;

use App\Http\Requests\DictionaryRequest;
use App\Http\Resources\DictionaryElementResource;
use App\Http\Resources\DictionaryResource;
use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Support\Facades\Auth;

class DictionaryService
{
    public function store(DictionaryRequest $request)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $checkCode = $this->checkingUnique($request);
                if ($checkCode) return response()->json($checkCode, 422);
                $newDictionary = Dictionary::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'description' => $request->description,
                    'archive' => 0,
                    'created_date' => Date('Y-m-d H:i:s'),
                    'update_date' => Date('Y-m-d H:i:s'),
                    'created_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id
                ]);
                return new DictionaryResource($newDictionary);
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(DictionaryRequest $request, $id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $checkCode = $this->checkingUnique($request, $id);
                if ($checkCode) return response()->json($checkCode, 422);
                $dictionary = Dictionary::find($id);
                $dictionary->name = $request->name;
                $dictionary->description = $request->description;
                $dictionary->code = $request->code;
                $dictionary->updated_author = Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id;
                $dictionary->update_date = Date('Y-m-d H:i:s');
                $dictionary->save();
                return new DictionaryResource($dictionary);
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionary = Dictionary::findOrFail($id);
                $dictionary->delete();
                return response()->noContent();
            }
            else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function archive($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionary = Dictionary::findOrFail($id);
                $dictionary->archive = $dictionary->archive == 1 ? false : true;
                $dictionary->update_date = Date('Y-m-d H:i:s');
                $dictionary->save();
                return response()->noContent();
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findDictionary($get)
    {

        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $query = Dictionary::query()->where(['created_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id]);
                    if(isset($get['archive'])){
                        $query = $query->whereIn('archive', explode(',',$get['archive']));
                    }
                    if(isset($get['code'])){
                        $query = $query->where('code', 'LIKE', '%'.$get['code'].'%');
                    }
                    if(isset($get['name'])){
                        $query = $query->where('name', 'LIKE', '%'.$get['name'].'%');
                    }
                    $count = $query->count();
                    if(isset($get['page']) && $get['page'] > 0){
                        $query = $query->offset(($get['page']-1)*15);
                    }
                    $dictionary = $query->orderBy('update_date', 'desc')->limit(15)->get();
                return self::prepareListing($dictionary, $count);
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findDictionaryNotEmptyElement()
    {
        try {
            if (Auth::guard('web')->check()) {
                $dictionaryElements = DictionaryElement::/*orderBy('created_date', 'asc')->*/all();
                $dictionaryId = [];
                foreach ($dictionaryElements as $dictionaryElement) {
                    $dictionaryId[] = $dictionaryElement->dictionary_id;
                }
                $dictionary = Dictionary::where(['archive' => 0])->whereIn('id', array_unique($dictionaryId))/*->orderBy('created_date', 'asc')*/->get();
                return $dictionary;
            }else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findDictionaryID($id)
    {
        try {
            $dictionary = Dictionary::find($id)->orderBy('created_date', 'asc')->get();
            return DictionaryResource::collection($dictionary);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function prepareListing($data, $countData)
    {
        $result = [];
        $result['pages'] = ceil($countData/15);
        $result['countData'] = $countData;
        $result['data'] = DictionaryResource::collection($data);
        return $result;
    }

    public function checkingUnique($dictionary, $id = null)
    {
        $errors = [];
        if($id == null){
            if (Dictionary::where('code', $dictionary->code)->first() !== null) {
                $errors['code'] = '«Код справочника» должен быть уникальным';
            }
            if (Dictionary::where('name', $dictionary->name)->first() !== null) {
                $errors['name'] = '«Наименование справочника» должно быть уникальным';
            }
        }else{
            foreach (Dictionary::where('code', $dictionary->code)->get() as $d){
                if($d->id !== $id){
                    $errors['code'] = '«Код справочника» должен быть уникальным';
                }
            }
            foreach (Dictionary::where('name', $dictionary->name)->get() as $d){
                if($d->id !== $id){
                    $errors['name'] = '«Наименование справочника» должно быть уникальным';
                }
            }
        }

        if(!empty($errors)){
            return [
                'code' => 422,
                'message' => 'The given data was invalid',
                'errors' => $errors
            ];
        }
    }
}
