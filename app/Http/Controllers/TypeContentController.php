<?php

namespace App\Http\Controllers;

use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Nette\Utils\Type;


class TypeContentController extends Controller
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
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin')) {
                $type_content = TypeContent::all();
            } else if ($user->hasRole('Admin')) {
                $type_content = TypeContent::where('created_author', Auth::id())->orWhere('id', Auth::id())->get();
            }
            return view('type_content.index')->with('type_content', $type_content);
        } else if (Auth::guard('api')->check()) {
            $type_content = TypeContent::where(['created_author' => Auth::guard('api')->user()->id])->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($type_content);
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

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'icon' => 'nullable|max:150',
            'active_from' => 'nullable|date',
            'active_after' => 'nullable|date',
            'api_url' => 'required|unique:type_contents|max:150',
            'body' => 'nullable|max:1000',

        ]);
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

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
        } elseif (Auth::guard('api')->check()) {

            $new_type_content = TypeContent::create([
                'id_global' => Str::uuid()->toString(),
                'name' => $request['name'],
                'description' => $request['description'],
                'owner' => Str::uuid()->toString(),
                'icon' => $request['icon'],
                'active_from' => $request['active_from'],
                'active_after' => $request['active_after'],
                'api_url' => str_slug($request->input('name')),
                'body' => $request['body'],
                'created_author' => Auth::guard('api')->user()->id,
                'updated_author' => Auth::guard('api')->user()->id
            ]);
            $type_content = TypeContent::find($new_type_content->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($type_content);
        } else {
            return 'not auth';
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'icon' => 'nullable|max:150',
            'active_from' => 'nullable|date',
            'active_after' => 'nullable|date',
            'api_url' => 'required|max:150',
            'body' => 'nullable|max:1000',

        ]);
        if (Auth::guard('api')->check()) {
            $type = TypeContent::find($id);
            //print_r($id);exit;
            $type->name = $request['name'];
            $type->description = $request['description'];
            $type->owner = $request['owner'];
            $type->icon = $request['icon'];
            $type->active_from = $request['active_from'];
            $type->active_after = $request['active_after'];
            $type->api_url = $request['api_url'];
            $type->body = $request['body'];
            $type->updated_author = Auth::guard('api')->user()->id;
            $type->save();
            $type_content = TypeContent::find($type->id)->with('created_author:id,name')->with('updated_author:id,name')->get();
            return response()->json($type_content);
        } else {
            return 'not auth';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::guard('web')->check()) {
            //...
        } else if (Auth::guard('api')->check()) {
            $type_content = TypeContent::find($id);
            if ($type_content) {
                $type_content->delete();
                return response()->json('item was deleted');
            }
        } else {
            return response()->json('item not found');
        }
    }

    public function getAllVersionTypeContent($id)
    {
        if (Auth::guard('web')->check()) {
            //...
        } else if (Auth::guard('api')->check()) {
            $type_content = TypeContent::where('id_global', $id)->get();
            return response()->json($type_content);
        } else {
            return response()->json('item not found');
        }
    }
}
