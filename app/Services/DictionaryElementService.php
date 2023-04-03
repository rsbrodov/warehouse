<?php

namespace App\Services;

use App\Http\Resources\DictionaryElementResource;
use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DictionaryElementService
{

    public function store(Request $request)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionaryElement = DictionaryElement::create([
                    'value' => $request->form['value'],
                    'dictionary_id' => $request->form['dictionary_id'],
                    'created_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id ?? Auth::guard('api')->user()->id,
                ]);
                return new DictionaryElementResource($dictionaryElement);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|max:255',
        ]);
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $editElementDictionary = DictionaryElement::where('id', $id)->first();
                $editElementDictionary->value = $request->input('value');
                $editElementDictionary->save();
                //dd($editElementDictionary);
                return redirect()->route('dictionary-element.index', $editElementDictionary->dictionary_id)->with('success', 'Элемент ' . $editElementDictionary->value . ' успешно отредактирован');
            }
        } else if (Auth::guard('api')->check()) {
            $dictionaryElement = DictionaryElement::find($id);
            $dictionaryElement->dictionary_id = $request['dictionary_id'];
            $dictionaryElement->value = $request['value'];
            $dictionaryElement->updated_author = Auth::guard('api')->user()->id;
            $dictionaryElement->save();

            $dictionary = DictionaryElement::find($id)->with('createdAuthor:id,name')->with('updatedAuthor:id,name')->get();
            return response()->json($dictionary);
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionary = DictionaryElement::findOrFail($id);
                $dictionary->delete();
                return response()->noContent();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findElementDictionaryID($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionaryElement = DictionaryElement::where(['dictionary_id' => $id])->orderBy('created_date', 'asc')->get();
                return DictionaryElementResource::collection($dictionaryElement);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findID($id)
    {
        try {
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                $dictionaryElement = DictionaryElement::find($id);
                return DictionaryElementResource::collection($dictionaryElement);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function indexCode($code)
    {
        $dictionary = Dictionary::where(['code' => $code])->first();
        if (empty($dictionary)) {
            return response()->json([]);
        }
        $dictionaryElement = DictionaryElement::where(['dictionary_id' => $dictionary->id])->get();
        if ($dictionaryElement) {
            return DictionaryElementResource::collection($dictionaryElement);
        } else {
            return response()->json([]);
        }
    }
}
