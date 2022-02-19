<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DictionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::guard('api')->check()) {
            $dictionary = Dictionary::where(['archive'=> 0, 'created_author' => Auth::guard('api')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }else {
            return 'not auth';
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
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
                'name' => 'required|max:255',
                'code' => 'required',
                'archive' => 'boolean',
                'description' => 'nullable',

            ]);
            $dic = Dictionary::create(['code'=>$request['code'], 'name'=> $request['name'], 'description'=> $request['description'], 'archive'=>$request['archive'], 'created_author'=>Auth::guard('api')->user()->id, 'updated_author'=>Auth::guard('api')->user()->id]);
            $dictionary = Dictionary::find($dic->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
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
            'name' => 'required|max:255',
            'code' => 'required',
            'archive' => 'boolean',
            'description' => 'nullable',

        ]);
        $dictionary = Dictionary::find($id);
        $dictionary->name = $request['name'];
        $dictionary->code = $request['code'];
        $dictionary->description = $request['description'];
        $dictionary->archive = $request['archive'];
        $dictionary->updated_author = Auth::guard('api')->user()->id;
        $dictionary->save();

        $dictionary = Dictionary::find($id)->with('created_author:id,name')->with('updated_author:id,name')->get();
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
        $dictionary = Dictionary::find($id);
        if($dictionary){
            $dictionary->delete();
            return response()->json('item was deleted');
        }else{
            return response()->json('item not found');
        }

    }
}
