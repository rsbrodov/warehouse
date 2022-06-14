<?php

namespace App\Http\Controllers;

use App\Models\ElementContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ElementContentController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function index($type_content_id)
    {
        if (Auth::guard('web')->check()) {
            $element_contents = ElementContent::where('owner', Auth::guard('web')->id())->where('type_content_id', $type_content_id)->get();
            return view('element_content.index')->with('element_contents', $element_contents);
        }
    }
    public function create($type_content_id)
    {
        if (Auth::guard('web')->check()) {
            return view('element_content.create')->with('type_content_id', $type_content_id);
        }
    }
    public function store(Request $request, $id)
    {
        if (Auth::guard('web')->check()) {
            dd('ElementContent '.$id);
            $new_element_content = ElementContent::create(
                [
                    'id_global'      => Str::uuid()->toString(),
                    //'type_content_id'=> $type_content_id,
                    'label'          => $request->label,
                    'owner'          => Auth::guard('web')->user()->id,
                    'active_from'    => date_create($request->active_from),
                    'active_after'   => date_create($request->active_after),
                    'status'         => 'DRAFT',
                    'version_major'  => '1',
                    'version_minor'  => '0',
                    'url'            => str_slug($request->url),
                    'based_element'  => null,
                    'created_author' => Auth::guard('web')->user()->id,
                    'updated_author' => Auth::guard('web')->user()->id,
                ]
            );
        }
        return redirect()->route('element-content.index')->with('success', 'Элемент ' . $new_element_content->label . ' успешно добавлен');
    }
}
