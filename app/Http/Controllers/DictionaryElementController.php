<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DictionaryElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $dictionary_element = DictionaryElement::where(['dictionary_id'=> $id])->with('created_author:id,name')->with('updated_author:id,name')->get();
        return response()->json($dictionary_element);
    }

    public function indexCode($code)
    {
        $dictionary = Dictionary::where(['code'=> $code])->first();
        if(empty($dictionary)){
            return response()->json('dictionary not found');
        }
        $dictionary_element = DictionaryElement::where(['dictionary_id'=> $dictionary->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
        if($dictionary_element){
            return response()->json($dictionary_element);
        }else{
            return response()->json('elements not found');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'dictionary_id' => 'required|max:255',
            'value' => 'required|max:255',

        ]);
        $dictionary = Dictionary::where(['id'=> $request['dictionary_id']])->first();
        if(empty($dictionary)){
            return response()->json('dictionary not found');
        }
        $dic = DictionaryElement::create(['dictionary_id'=>$request['dictionary_id'], 'value'=> $request['value'], 'created_author'=>Auth::guard('api')->user()->id, 'updated_author'=>Auth::guard('api')->user()->id]);
        $dictionary_element = DictionaryElement::where('id', $dic->id)->with('created_author:id,name')->with('updated_author:id,name')->first();
        return response()->json($dictionary_element);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dictionary_id' => 'required|max:255',
            'value' => 'required|max:255',

        ]);
        $dictionary_element = DictionaryElement::find($id);
        $dictionary_element->dictionary_id = $request['dictionary_id'];
        $dictionary_element->value = $request['value'];
        $dictionary_element->updated_author = Auth::guard('api')->user()->id;
        $dictionary_element->save();

        $dictionary = DictionaryElement::find($id)->with('created_author:id,name')->with('updated_author:id,name')->get();
        return response()->json($dictionary);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dictionary_element = DictionaryElement::find($id);
        if($dictionary_element){
            $dictionary_element->delete();
            return response()->json('item was deleted');
        }else{
            return response()->json('item not found');
        }
    }
}
