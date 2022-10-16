<?php

namespace App\Http\Controllers;

use App\Http\Requests\DictionaryRequest;
use App\Models\Dictionary;
use App\Models\DictionaryElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DictionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        if(Auth::guard('web')->check()) {
            return view('dictionary.index');
        }
    }

    public function findDictionary()
    {
        if(Auth::guard('web')->check()) {
            $dictionary = Dictionary::where(['created_author' => Auth::guard('web')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->orderBy('created_at', 'asc')->get();
            return response()->json($dictionary);

        }else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::where(['created_author' => Auth::guard('api')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->orderBy('created_at', 'asc')->get();
            return response()->json($dictionary);
        }
    }

    public function findDictionaryID($id)
    {
        $dictionary = Dictionary::where(['id' => $id])->with('created_author:id,name')->with('updated_author:id,name')->orderBy('created_at', 'asc')->get();
        if($dictionary){
            return response()->json($dictionary);
        }else{
            return response()->json('not found');
        }


    }

    public function store(DictionaryRequest $request)
    {
        if (Auth::guard('web')->check()) {
            $newDictionary = Dictionary::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'archive' => 0,
                'created_author' => Auth::guard('web')->user()->id,
                'updated_author' => Auth::guard('web')->user()->id
            ]);
            $dictionary = Dictionary::where('id', $newDictionary->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);

        } else if (Auth::guard('api')->check()) {
            $dic = Dictionary::create(['code' => $request->code, 'name' => $request->name, 'description' => $request->description, 'archive' => 0, 'created_author' => Auth::guard('api')->user()->id, 'updated_author' => Auth::guard('api')->user()->id]);
            $dictionary = Dictionary::where('id', $dic->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }


    public function show($id)
    {

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('Admin') or $user->hasRole('SuperAdmin')) {
                $dictionaryElements = DictionaryElement::where('dictionary_id', $id)->get();
                return view('dictionary.show', ['dictionary_elements' => $dictionaryElements, 'dictionary_id' => $id]);
            }
        } else if (Auth::guard('api')->check()) {
            $dictionaryElement = DictionaryElement::where(['dictionary_id' => $id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionaryElement);
        }
    }


    public function update(DictionaryRequest $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $dictionary = Dictionary::find($id);
            $dictionary->name = $request->name;
            $dictionary->description = $request->description;
            $dictionary->code = $request->code;
            $dictionary->updated_author = Auth::guard('web')->user()->id;
            $dictionary->save();
            $dictionary = Dictionary::find($id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);

        } else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::find($id);
            $dictionary->name = $request->name;
            $dictionary->description = $request->description;
            $dictionary->code = $request->code;
            $dictionary->updated_author = Auth::guard('api')->user()->id;
            $dictionary->save();
            $dictionary = Dictionary::find($id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }


    public function archive($id)
    {
        if (Auth::guard('web')->check()) {
            $dictionary = Dictionary::where('id', $id)->first();
            if ($dictionary->archive == 1) {
                $dictionary->archive = 0;
            } else {
                $dictionary->archive = 1;
            }
            $dictionary->save();
            return response()->json($dictionary);
        }
    }

    public function destroy($id)
    {

        $dictionary = Dictionary::find($id);
        if ($dictionary) {
            $dictionary->delete();
            return response()->json('item was deleted');
        }else {
            return response()->json('item not found');
        }
    }
}
