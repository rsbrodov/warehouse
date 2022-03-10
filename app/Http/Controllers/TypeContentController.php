<?php

namespace App\Http\Controllers;

use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nette\Utils\Type;
use Illuminate\Support\Facades\Auth;

class TypeContentController extends Controller
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
                $type_content = TypeContent::all();
            } else if ($user->hasRole('Admin')) {
                $type_content = TypeContent::where('created_author', Auth::id())->orWhere('id', Auth::id())->get();
            }
            return view('type_content.index')->with('type_content', $type_content);
        } else {
            print_r('Авторизируйтесь');
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
            return view('type_content.create');
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
            $user = Auth::guard('web')->user();
            $request->validate([
                'name' => 'required'
            ]);
            $new_type_content = TypeContent::create([
                'id_global' => Str::uuid()->toString(),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'owner' => '7856',
                'active_from' => $request->input('active_from'),
                'active_after' => $request->input('active_after'),
                'status' => 'DRAFT',
                'version_major' => '1',
                'version_minor' => '0',
                'icon' => $request->input('icon'),
                'api_url' => str_slug($request->input('name')),
                'based_type' => null,
                'created_author' => $user->id,
                'updated_author' => $user->id
            ]);
            return redirect()->route('type_content.index')->with('success', 'Тип контента ' . $new_type_content->name . ' был создан');
        } else {
            print_r('Авторизируйтесь');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function show(TypeContent $typeContent, $id)
    {
        $type_content = TypeContent::find($id);
        return view('type_content.show')->with('type_content', $type_content);
    }

    public function shablonVersionIndex(TypeContent $typeContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeContent $typeContent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeContent $typeContent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeContent $typeContent)
    {
        //
    }
}
