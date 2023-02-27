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
                $newDictionary = Dictionary::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'description' => $request->description,
                    'archive' => 0,
                    'created_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id
                ]);
                return new DictionaryResource($newDictionary);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(DictionaryRequest $request, $id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionary = Dictionary::find($id);
                $dictionary->name = $request->name;
                $dictionary->description = $request->description;
                $dictionary->code = $request->code;
                $dictionary->updated_author = Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id;
                $dictionary->save();
                return new DictionaryResource($dictionary);
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
                $dictionary->save();
                return response()->noContent();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findDictionary()
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionary = Dictionary::where(['created_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id])
                    ->orderBy('created_at', 'asc')
                    ->get();
                return DictionaryResource::collection($dictionary);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findDictionaryNotEmptyElement()
    {
        if (Auth::guard('web')->check()) {
            $dictionaryElements = DictionaryElement::where(['created_author' => Auth::guard('web')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->orderBy('created_at', 'asc')->get();
            $dictionaryId = [];
            foreach ($dictionaryElements as $dictionaryElement) {
                $dictionaryId[] = $dictionaryElement->dictionary_id;
            }
            $dictionary = Dictionary::where(['created_author' => Auth::guard('web')->user()->id, 'archive' => 0])->whereIn('id', array_unique($dictionaryId))->with('created_author:id,name')->with('updated_author:id,name')->orderBy('created_at', 'asc')->get();
            return response()->json($dictionary);
        }
    }

    public function findDictionaryID($id)
    {
        try {
            $dictionary = Dictionary::find($id)->orderBy('created_at', 'asc')->get();
            return DictionaryResource::collection($dictionary);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
