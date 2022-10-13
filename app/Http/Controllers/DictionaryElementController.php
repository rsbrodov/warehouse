<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DictionaryElementController extends Controller
{

    public function index()
    {
        if(Auth::guard('web')->check()) {
            return view('dictionary_element.index');
        }
    }
    public function findElementDictionaryID($id)
    {
        if (Auth::guard('web')->check()) {
            $dictionaryElement = DictionaryElement::where(['dictionary_id' => $id])->with('created_author:id,name')->with('updated_author:id,name')->orderBy('created_at', 'asc')->get();
            return response()->json($dictionaryElement);
        } else if (Auth::guard('api')->check()) {
            $dictionaryElement = DictionaryElement::where(['dictionary_id' => $id])->with('created_author:id,name')->with('updated_author:id,name')->orderBy('created_at', 'asc')->get();
            return response()->json($dictionaryElement);
        }
    }
    public function findID($id)
    {
        if (Auth::guard('web')->check()) {
            $dictionaryElement = DictionaryElement::where(['id' => $id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionaryElement);
        } else if (Auth::guard('api')->check()) {
            $dictionaryElement = DictionaryElement::where(['id' => $id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionaryElement);
        }
    }


    public function indexCode($code)
    {
        $dictionary = Dictionary::where(['code' => $code])->first();
        if (empty($dictionary)) {
            return response()->json('dictionary not found');
        }
        $dictionaryElement = DictionaryElement::where(['dictionary_id' => $dictionary->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
        if ($dictionaryElement) {
            return response()->json($dictionaryElement);
        } else {
            return response()->json('elements not found');
        }
    }


    public function store(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $dictionaryElement = DictionaryElement::create([
                'value' => $request->form['value'],
                'dictionary_id' => $request->form['dictionary_id'],
                'created_author' => Auth::guard('web')->user()->id,
                'updated_author' => Auth::guard('web')->user()->id
            ]);

            return response()->json($dictionaryElement);
        } else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::where(['id' => $request['dictionary_id']])->first();
            if (empty($dictionary)) {
                return response()->json('dictionary not found');
            }
            $dic = DictionaryElement::create(['dictionary_id' => $request['dictionary_id'], 'value' => $request['value'], 'created_author' => Auth::guard('api')->user()->id, 'updated_author' => Auth::guard('api')->user()->id]);
            $dictionaryElement = DictionaryElement::where('id', $dic->id)->with('created_author:id,name')->with('updated_author:id,name')->first();
            return response()->json($dictionaryElement);
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

            $dictionary = DictionaryElement::find($id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }


    public function destroy($id)
    {
        $dictionaryElement = DictionaryElement::find($id);
        if ($dictionaryElement) {
            $dictionaryElement->delete();
            return response()->json('item was deleted');
        } else {
            return response()->json('item not found');
        }
    }
}
