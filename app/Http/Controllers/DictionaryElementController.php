<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DictionaryElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dictionary_id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('Admin') or $user->hasRole('SuperAdmin')) {
                $dictionary_elements = DictionaryElement::where('dictionary_id', $dictionary_id)->get();
            return view('dictionary_element.index', ['dictionary_elements' => $dictionary_elements, 'dictionary_id'=> $dictionary_id]);
            }
        } else if (Auth::guard('api')->check()) {
            return 'whatsapp??';
        } else {
            return 'not auth';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($dictionary_id)
    {
        if (Auth::guard('web')->check()) {
            return view('dictionary_element.create', ['dictionary_id' => $dictionary_id]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $dictionary_id)
    {
        if (Auth::guard('web')->check()) {
            $request->validate([
                'value' => 'required',
            ]);
            $new_dictionary_element = DictionaryElement::create([
                'value' => $request->input('value'),
                'dictionary_id' => $dictionary_id,
                'created_author' => Auth::guard('web')->user()->id,
                'updated_author' => Auth::guard('web')->user()->id
            ]);
            return redirect()->route('dictionary_element.index', $dictionary_id)->with('success', 'Элемент ' . $new_dictionary_element->value . ' был добавлен');
        } else if (Auth::guard('api')->check()) {
            return 'WHATSAPP!!!';
        } else {
            return 'not auth';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($dic_id, $id)
    {
        $dictionary_element = DictionaryElement::find($id);
        return view('dictionary_element.show')->with('dictionary_element', $dictionary_element);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($dic_id, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_element_dictionary = DictionaryElement::where('id', $id)->first();
                return view('dictionary_element.edit', ['edit_element_dictionary' => $edit_element_dictionary]);
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $dic_id, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_element_dictionary = DictionaryElement::where('id', $id)->first();
                $edit_element_dictionary->value = $request->input('value');
                $edit_element_dictionary->save();
                return redirect()->route('dictionary_element.index', $dic_id)->with('success', 'Элемент ' . $edit_element_dictionary->value . ' был отредактирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($dic_id, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $element_dictionary = DictionaryElement::find($id);
                $element_dictionary->delete();
                return redirect()->route('dictionary_element.index', $dic_id)->with('success', 'Справочник ' . $element_dictionary->value . ' был уничтожен');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }
}
