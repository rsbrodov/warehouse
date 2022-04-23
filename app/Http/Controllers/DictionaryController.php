<?php

namespace App\Http\Controllers;

use App\Http\Requests\DictionaryRequest;
use App\Models\Dictionary;
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
            $dictionary = Dictionary::where(['created_author' => Auth::guard('web')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);

        }else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::where(['created_author' => Auth::guard('api')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }
    public function index2()
    {
        if(Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                $dictionary = Dictionary::all();
            }
            if ($user->hasRole('Admin')) {
                $dictionary = Dictionary::where('created_author', Auth::id())->get();
            }
            return view('dictionary.index2', ['dictionaries' => $dictionary]);
        }else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::where(['archive' => 0, 'created_author' => Auth::guard('api')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if (Auth::guard('web')->check()) {
            return view('dictionary.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DictionaryRequest $request)
    {
        if (Auth::guard('web')->check()) {
            $new_dictionary = Dictionary::create([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'archive' => $request->input('archive'),
                'created_author' => Auth::guard('web')->user()->id,
                'updated_author' => Auth::guard('web')->user()->id
            ]);
            return redirect()->route('dictionary.index')->with('success', 'Справочник ' . $new_dictionary->name . ' успешно добавлен');
        } else if (Auth::guard('api')->check()) {
            $dic = Dictionary::create(['code' => $request['code'], 'name' => $request['name'], 'description' => $request['description'], 'archive' => $request['archive'], 'created_author' => Auth::guard('api')->user()->id, 'updated_author' => Auth::guard('api')->user()->id]);
            $dictionary = Dictionary::where('id', $dic->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dictionary = Dictionary::find($id);
        return view('dictionary.show')->with('dictionary', $dictionary);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_dictionary = Dictionary::where('id', $id)->first();
                return view('dictionary.edit', ['edit_dictionary' => $edit_dictionary]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(DictionaryRequest $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_dictionary = Dictionary::find($id);
                $edit_dictionary->name = $request->input('name');
                $edit_dictionary->description = $request->input('description');
                $edit_dictionary->code = $request->input('code');
                $edit_dictionary->archive = $request->input('archive');
                $edit_dictionary->save();
                return redirect()->route('dictionary.index')->with('success', 'Справочник ' . $edit_dictionary->name . ' успешно отредактирован');
            }
        } else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::find($id);
            $dictionary->name = $request['name'];
            $dictionary->code = $request['code'];
            $dictionary->description = $request['description'];
            $dictionary->archive = $request['archive'];
            $dictionary->updated_author = Auth::guard('api')->user()->id;
            $dictionary->save();
            $dictionary = Dictionary::where('id', $id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($dictionary);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $message = ''; $type_message = 'success';
                $archive_dictionary = Dictionary::where('id', $id)->first();
                if ($archive_dictionary->archive == '1') {
                    $archive_dictionary->archive = '0';
                    $message = 'Справочник ' . $archive_dictionary->name . ' извлечен из архива';
                } else {
                    $archive_dictionary->archive = '1';
                    $message = 'Справочник ' . $archive_dictionary->name . ' перенесён в архив';
                    $type_message = 'warning';
                }
                $archive_dictionary->save();
                return redirect()->route('dictionary.index')->with($type_message, $message);
            }
        }
    }

    public function destroy($id)
    {

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $dictionary = Dictionary::find($id);
                $dictionary->delete();
                return redirect()->route('dictionary.index')->with('info', 'Справочник ' . $dictionary->name . ' уничтожен');
            }
        } else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::find($id);
            if ($dictionary) {
                $dictionary->delete();
                return response()->json('item was deleted');
            }
        } else {
            return response()->json('item not found');
        }
    }
}
