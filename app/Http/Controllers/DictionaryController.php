<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                $dictionary = Dictionary::all();
            }
            if ($user->hasRole('Admin')) {
                $dictionary = Dictionary::where('created_author', Auth::id())->get();
            }
            return view('dictionary.index', ['dictionaries' => $dictionary]);
        } else if (Auth::guard('api')->check()) {
            $dictionary = Dictionary::with('user')->get();
            return $dictionary;
        } else {
            return 'not auth';
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
    public function store(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $request->validate([
                'name' => 'required',
            ]);
            $new_dictionary = Dictionary::create([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'archive' => $request->input('archive'),
                'created_author' => Auth::guard('web')->user()->id,
                'updated_author' => Auth::guard('web')->user()->id
            ]);

            return redirect()->route('dictionary.index')->with('success', 'Справочник ' . $new_dictionary->name . ' был добавлен');
        } else if (Auth::guard('api')->check()) {
            $dic = Dictionary::create(['code' => $request['code'], 'name' => $request['name'], 'description' => $request['description'], 'archive' => $request['archive'], 'created_author' => Auth::guard('api')->user()->id, 'updated_author' => Auth::guard('api')->user()->id]);
            return $request;
        } else {
            return 'not auth';
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
        } else {
            print_r('Авторизируйтесь');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $edit_dictionary = Dictionary::where('id', $id)->first();
                $edit_dictionary->name = $request->input('name');
                $edit_dictionary->description = $request->input('description');
                $edit_dictionary->code = $request->input('code');
                $edit_dictionary->archive = $request->input('archive');
                $edit_dictionary->save();
                return redirect()->route('dictionary.index')->with('success', 'Справочник ' . $edit_dictionary->name . ' был отредактирован');
            }
        } else {
            print_r('Авторизируйтесь');
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
                $archive_dictionary = Dictionary::where('id', $id)->first();
                if ($archive_dictionary->archive == '1') {
                    $archive_dictionary->archive = '0';
                } else {
                    $archive_dictionary->archive = '1';
                }
                $archive_dictionary->save();
                return redirect()->route('dictionary.index')->with('success', 'Справочник ' . $archive_dictionary->name . ' был архивирован');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }
    public function destroy($id)
    {
        if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $dictionary = Dictionary::find($id);
                $dictionary->delete();
                return redirect()->route('dictionary.index')->with('success', 'Справочник ' . $dictionary->name . ' был уничтожен');
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }
}
