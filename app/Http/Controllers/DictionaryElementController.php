<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DictionaryElementController extends Controller
{

    public function index($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('Admin') or $user->hasRole('SuperAdmin')) {
                $dictionary_elements = DictionaryElement::where('dictionary_id', $id)->get();
                return view('dictionary_element.index', ['dictionary_elements' => $dictionary_elements, 'dictionary_id' => $id]);
            }
        } else if (Auth::guard('api')->check()) {
            $dictionary_element = DictionaryElement::where(['dictionary_id' => $id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary_element);
        }
    }


    public function indexCode($code)
    {
        $dictionary = Dictionary::where(['code' => $code])->first();
        if (empty($dictionary)) {
            return response()->json('dictionary not found');
        }
        $dictionary_element = DictionaryElement::where(['dictionary_id' => $dictionary->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
        if ($dictionary_element) {
            return response()->json($dictionary_element);
        } else {
            return response()->json('elements not found');
        }


    }


    public function store(Request $request, $dictionary_id)
    {
        $request->validate([
            'value' => 'required|max:255',

        ]);
        if (Auth::guard('web')->check()) {
            $new_dictionary_element = DictionaryElement::create([
                'value' => $request->input('value'),
                'dictionary_id' => $dictionary_id,
                'created_author' => Auth::guard('web')->user()->id,
                'updated_author' => Auth::guard('web')->user()->id
            ]);

            return redirect()->route('dictionary-element.index', $dictionary_id)->with('success', 'Элемент ' . $new_dictionary_element->value . ' успешно добавлен');
        } else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::where(['id' => $request['dictionary_id']])->first();
            if (empty($dictionary)) {
                return response()->json('dictionary not found');
            }
            $dic = DictionaryElement::create(['dictionary_id' => $request['dictionary_id'], 'value' => $request['value'], 'created_author' => Auth::guard('api')->user()->id, 'updated_author' => Auth::guard('api')->user()->id]);
            $dictionary_element = DictionaryElement::where('id', $dic->id)->with('created_author:id,name')->with('updated_author:id,name')->first();
            return response()->json($dictionary_element);
        }
    }



    public function edit($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_element_dictionary = DictionaryElement::where('id', $id)->first();
                //dd($edit_element_dictionary);
                return view('dictionary_element.edit', ['edit_element_dictionary' => $edit_element_dictionary]);
            }
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
                $edit_element_dictionary = DictionaryElement::where('id', $id)->first();
                $edit_element_dictionary->value = $request->input('value');
                $edit_element_dictionary->save();
                //dd($edit_element_dictionary);
                return redirect()->route('dictionary-element.index', $edit_element_dictionary->dictionary_id)->with('success', 'Элемент ' . $edit_element_dictionary->value . ' успешно отредактирован');
            }
        } else if (Auth::guard('api')->check()) {
            $dictionary_element = DictionaryElement::find($id);
            $dictionary_element->dictionary_id = $request['dictionary_id'];
            $dictionary_element->value = $request['value'];
            $dictionary_element->updated_author = Auth::guard('api')->user()->id;
            $dictionary_element->save();

            $dictionary = DictionaryElement::find($id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }


    public function destroy($id)
    {
        if(Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $element_dictionary = DictionaryElement::find($id);
                $element_dictionary->delete();
                return redirect()->route('dictionary-element.index', $element_dictionary->dictionary_id)->with('info', 'Элемент ' . $element_dictionary->value . ' уничтожен');
            }else {
                print_r('Авторизируйтесь');
            }
        }else if (Auth::guard('web')->check()) {
            $dictionary_element = DictionaryElement::find($id);
            if ($dictionary_element) {
                $dictionary_element->delete();
                return response()->json('item was deleted');
            } else {
                return response()->json('item not found');
            }
        }
    }
}
