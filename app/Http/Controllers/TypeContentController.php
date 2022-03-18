<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeContentRequest;
use App\Models\Icons;
use App\Models\TypeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                $type_contents = TypeContent::where(['created_author' => Auth::guard('web')->user()->id])->with('created_authors:id,name')->with('updated_authors:id,name')->get();
            } elseif ($user->hasRole('Admin')) {
                $type_contents = TypeContent::where(['created_author' => Auth::guard('web')->user()->id])->with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name', 'desc')->get();
            }
            return view('type_content.index')->with('type_contents', $type_contents);
        } else {
            if (Auth::guard('api')->check()) {
                $type_contents = TypeContent::where(['created_author' => Auth::guard('api')->user()->id])->with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name', 'desc')->get();
                return response()->json($type_contents);
            } else {
                return 'not auth';
            }
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
            $icons = Icons::all();
            foreach ($icons as $key => $icon)
            {
                $icons_array[$icon->code] = $icon->code;
            }
            //print_r($icons_array);
            return view('type_content.create')->with('icons_array', $icons_array);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeContentRequest $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $model = new TypeContent();
            $apiUrl = str_slug($request->input('name'));
            //$idGlobal = Str::uuid()->toString();
            if (!$model->checkingApiUrl($apiUrl)) {
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
                    'api_url' => $apiUrl,
                    'based_type' => null,
                    'created_author' => $user->id,
                    'updated_author' => $user->id
                ]);
                return redirect()->route('type-content.index')->with('success',
                    'Тип контента ' . $new_type_content->name . ' успешно создан');
            } else {
                return redirect()->back()->with('error', 'Что-то пошло не так');
            }
        } elseif (Auth::guard('api')->check()) {

            $new_type_content = TypeContent::create([
                'id_global' => Str::uuid()->toString(),
                'name' => $request['name'],
                'description' => $request['description'],
                'owner' => Str::uuid()->toString(),
                'icon' => $request['icon'],
                'active_from' => $request['active_from'],
                'active_after' => $request['active_after'],
                'api_url' => $request->input('api_url'),
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeContent $typeContent, $id)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                $type_content = TypeContent::where('id', $id)->first();
                return view('type_content.edit', ['type_content' => $type_content]);
            }
        } else {
            print_r('Авторизируйтесь');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\TypeContent $typeContent
     * @return \Illuminate\Http\Response
     */
    public function update(TypeContentRequest $request, $id)
    {
                if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('SuperAdmin') or $user->hasRole('Admin')) {
                //$idGlobal = Str::uuid()->toString();
                $type = TypeContent::find($id);
                if (!$type->checkingApiUrl($request['api_url'], $type['id_global'])) {
                    $type->name = $request['name'];
                    $type->api_url = $request['api_url'];
                    $type->description = $request['description'];
                    $type->active_from = $request['active_from'];
                    $type->active_after = $request['active_after'];
                    $type->status = $request['status'];
                    $type->icon = $request['icon'];
                    $type->body = $request['body'];
                    $type->updated_author = Auth::guard('web')->user()->id;
                    $type->save();
                    return redirect()->route('type-content.index')->with('success', 'Тип ' . $type->name . ' успешно отредактирован');
                } else {
                    return redirect()->back()->with('error', 'Что-то пошло не так');
                }
            }
        } else {
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
            $type_content = TypeContent::find($id);
            if ($type_content) {
                $type_content->delete();
                return redirect()->route('type-content.index')->with('success', 'Тип ' . $type_content->name . ' успешно удален');
            } else{

            }
        } else {
            if (Auth::guard('api')->check()) {
                $type_content = TypeContent::find($id);
                if ($type_content) {
                    $type_content->delete();
                    return response()->json('item was deleted');
                }
            } else {
                return response()->json('item not found');
            }
        }
    }

    public function getAllVersionTypeContent($id)
    {
        if (Auth::guard('web')->check()) {
            $type_contents = TypeContent::where('id_global', $id)->get();
            return view('type_content.all-version-type-content')->with('type_contents', $type_contents);
        } else {
            if (Auth::guard('api')->check()) {
                $type_content = TypeContent::where('id_global', $id)->get();
                return response()->json($type_content);
            } else {
                return response()->json('item not found');
            }
        }
    }

    public function createNewVersion($id, $parametr)
    {
        //проверка параметров
        if ($parametr == 'major' || $parametr == 'minor') {
            if ($parametr == 'major') {
                //если мажор то мы просто создаем дубликат наивысшей строки по version_major
                $typeContent = TypeContent::where('id_global', $id)->orderBy('version_major', 'desc')->first();
                //replicate - встроенный метод дублирования в laravel
                $newTypeContent = $typeContent->replicate();//тут лежит наш новый объект
                $newTypeContent->version_major = $typeContent->version_major + 1;//изменяем объект с учетом наших параметров затем сохраняем
                $newTypeContent->version_minor = 0;
            } else {
                //если минор то просто ищим наивысшей строки по version_major и version_minor ну и изменяем версию

                $typeContent = TypeContent::where('id_global', $id)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first();

                $newTypeContent = $typeContent->replicate();
                $newTypeContent->version_minor = $typeContent->version_minor + 1;
            }
            if ($newTypeContent->save()) {

                return redirect()->route('type-content.get-all-version', $typeContent->id_global)->with('success', 'Новая версия успешно создана');

            } else {
                return redirect()->back()->with('error', 'Что-то пошло не так');
            }
        } else {
            return redirect()->back()->with('error', 'Что-то пошло не так');
        }
    }

    public function createIcons()
    {
        $icons = [
            ['code' => 'fa-500px', 'name' => ''],
            ['code' => 'fa-address-book', 'name' => ''],
            ['code' => 'fa-address-book-o', 'name' => ''],
            ['code' => 'fa-address-card', 'name' => 'Визитка(черный)'],
            ['code' => 'fa-address-card-o', 'name' => 'Визитка(белый)'],
            ['code' => 'fa-adjust', 'name' => ''],
            ['code' => 'fa-adn', 'name' => ''],
            ['code' => 'fa-align-center', 'name' => ''],
            ['code' => 'fa-align-justify', 'name' => ''],
            ['code' => 'fa-align-left', 'name' => ''],
            ['code' => 'fa-align-right', 'name' => ''],
            ['code' => 'fa-amazon', 'name' => ''],
            ['code' => 'fa-ambulance', 'name' => ''],
            ['code' => 'fa-american-sign-language-interpreting', 'name' => ''],
            ['code' => 'fa-anchor', 'name' => 'Якорь'],
            ['code' => 'fa-android', 'name' => ''],
            ['code' => 'fa-angellist', 'name' => ''],
            ['code' => 'fa-angle-double-down', 'name' => ''],
            ['code' => 'fa-angle-double-left', 'name' => ''],
            ['code' => 'fa-angle-double-right', 'name' => ''],
            ['code' => 'fa-angle-double-up', 'name' => ''],
            ['code' => 'fa-angle-down', 'name' => ''],
            ['code' => 'fa-angle-left', 'name' => ''],
            ['code' => 'fa-angle-right', 'name' => ''],
            ['code' => 'fa-angle-up', 'name' => ''],
            ['code' => 'fa-apple', 'name' => ''],
            ['code' => 'fa-archive', 'name' => ''],
            ['code' => 'fa-area-chart', 'name' => ''],
            ['code' => 'fa-arrow-circle-down', 'name' => ''],
            ['code' => 'fa-arrow-circle-left', 'name' => ''],
            ['code' => 'fa-arrow-circle-o-down', 'name' => ''],
            ['code' => 'fa-arrow-circle-o-left', 'name' => ''],
            ['code' => 'fa-arrow-circle-o-right', 'name' => ''],
            ['code' => 'fa-arrow-circle-o-up', 'name' => ''],
            ['code' => 'fa-arrow-circle-right', 'name' => ''],
            ['code' => 'fa-arrow-circle-up', 'name' => ''],
            ['code' => 'fa-arrow-down', 'name' => ''],
            ['code' => 'fa-arrow-left', 'name' => ''],
            ['code' => 'fa-arrow-right', 'name' => ''],
            ['code' => 'fa-arrow-up', 'name' => ''],
            ['code' => 'fa-arrows', 'name' => ''],
            ['code' => 'fa-arrows-alt', 'name' => ''],
            ['code' => 'fa-arrows-h', 'name' => ''],
            ['code' => 'fa-arrows-v', 'name' => ''],
            ['code' => 'fa-asl-interpreting', 'name' => ''],
            ['code' => 'fa-assistive-listening-systems', 'name' => ''],
            ['code' => 'fa-asterisk', 'name' => ''],
            ['code' => 'fa-at', 'name' => ''],
            ['code' => 'fa-audio-description', 'name' => ''],
            ['code' => 'fa-automobile', 'name' => 'Автомобиль'],
            ['code' => 'fa-backward', 'name' => ''],
            ['code' => 'fa-balance-scale', 'name' => ''],
            ['code' => 'fa-ban', 'name' => ''],
            ['code' => 'fa-bandcamp', 'name' => ''],
            ['code' => 'fa-bank', 'name' => ''],
            ['code' => 'fa-bar-chart', 'name' => ''],
            ['code' => 'fa-bar-chart-o', 'name' => ''],
            ['code' => 'fa-barcode', 'name' => ''],
            ['code' => 'fa-bars', 'name' => ''],
            ['code' => 'fa-bath', 'name' => ''],
            ['code' => 'fa-bathtub', 'name' => ''],
            ['code' => 'fa-battery', 'name' => ''],
            ['code' => 'fa-battery-0', 'name' => ''],
            ['code' => 'fa-battery-1', 'name' => ''],
            ['code' => 'fa-battery-2', 'name' => ''],
            ['code' => 'fa-battery-3', 'name' => ''],
            ['code' => 'fa-battery-4', 'name' => ''],
            ['code' => 'fa-battery-empty', 'name' => ''],
            ['code' => 'fa-battery-full', 'name' => ''],
            ['code' => 'fa-battery-half', 'name' => ''],
            ['code' => 'fa-battery-quarter', 'name' => ''],
            ['code' => 'fa-battery-three-quarters', 'name' => ''],
            ['code' => 'fa-bedКровать', 'name' => ''],
            ['code' => 'fa-beerПиво', 'name' => ''],
            ['code' => 'fa-behance', 'name' => ''],
            ['code' => 'fa-behance-square', 'name' => ''],
            ['code' => 'fa-bell', 'name' => 'Колокол(черный)'],
            ['code' => 'fa-bell-o', 'name' => 'Колокол(белый)'],
            ['code' => 'fa-bell-slash', 'name' => 'Беззвука(черный)'],
            ['code' => 'fa-bell-slash-o', 'name' => ''],
            ['code' => 'fa-bicycle', 'name' => ''],
            ['code' => 'fa-binoculars', 'name' => ''],
            ['code' => 'fa-birthday-cake', 'name' => ''],
            ['code' => 'fa-bitbucket', 'name' => ''],
            ['code' => 'fa-bitbucket-square', 'name' => ''],
            ['code' => 'fa-bitcoin', 'name' => ''],
            ['code' => 'fa-black-tie', 'name' => ''],
            ['code' => 'fa-blind', 'name' => ''],
            ['code' => 'fa-bluetooth', 'name' => ''],
            ['code' => 'fa-bluetooth-b', 'name' => ''],
            ['code' => 'fa-bold', 'name' => ''],
            ['code' => 'fa-bolt', 'name' => ''],
            ['code' => 'fa-bomb', 'name' => ''],
            ['code' => 'fa-book', 'name' => ''],
            ['code' => 'fa-bookmark', 'name' => ''],
            ['code' => 'fa-bookmark-o', 'name' => ''],
            ['code' => 'fa-braille', 'name' => ''],
            ['code' => 'fa-briefcase', 'name' => ''],
            ['code' => 'fa-btc', 'name' => ''],
            ['code' => 'fa-bug', 'name' => ''],
            ['code' => 'fa-building', 'name' => ''],
            ['code' => 'fa-building-o', 'name' => ''],
            ['code' => 'fa-bullhorn', 'name' => ''],
            ['code' => 'fa-bullseye', 'name' => ''],
            ['code' => 'fa-bus', 'name' => ''],
            ['code' => 'fa-buysellads', 'name' => ''],
            ['code' => 'fa-cab', 'name' => ''],
            ['code' => 'fa-calculator', 'name' => ''],
            ['code' => 'fa-calendar', 'name' => ''],
            ['code' => 'fa-calendar-check-o', 'name' => ''],
            ['code' => 'fa-calendar-minus-o', 'name' => ''],
            ['code' => 'fa-calendar-o', 'name' => ''],
            ['code' => 'fa-calendar-plus-o', 'name' => ''],
            ['code' => 'fa-calendar-times-o', 'name' => ''],
            ['code' => 'fa-camera', 'name' => ''],
            ['code' => 'fa-camera-retro', 'name' => ''],
            ['code' => 'fa-car', 'name' => ''],
            ['code' => 'fa-caret-down', 'name' => ''],
            ['code' => 'fa-caret-left', 'name' => ''],
            ['code' => 'fa-caret-right', 'name' => ''],
            ['code' => 'fa-caret-square-o-down', 'name' => ''],
            ['code' => 'fa-caret-square-o-left', 'name' => ''],
            ['code' => 'fa-caret-square-o-right', 'name' => ''],
            ['code' => 'fa-caret-square-o-up', 'name' => ''],
            ['code' => 'fa-caret-up', 'name' => ''],
            ['code' => 'fa-cart-arrow-down', 'name' => ''],
            ['code' => 'fa-cart-plus', 'name' => ''],
            ['code' => 'fa-cc', 'name' => ''],
            ['code' => 'fa-cc-amex', 'name' => ''],
            ['code' => 'fa-cc-diners-club', 'name' => ''],
            ['code' => 'fa-cc-discover', 'name' => ''],
            ['code' => 'fa-cc-jcb', 'name' => ''],
            ['code' => 'fa-cc-mastercard', 'name' => ''],
            ['code' => 'fa-cc-paypal', 'name' => ''],
            ['code' => 'fa-cc-stripe', 'name' => ''],
            ['code' => 'fa-cc-visa', 'name' => ''],
            ['code' => 'fa-certificate', 'name' => ''],
            ['code' => 'fa-chain', 'name' => ''],
            ['code' => 'fa-chain-broken', 'name' => ''],
            ['code' => 'fa-check', 'name' => ''],
            ['code' => 'fa-check-circle', 'name' => ''],
            ['code' => 'fa-check-circle-o', 'name' => ''],
            ['code' => 'fa-check-square', 'name' => ''],
            ['code' => 'fa-check-square-o', 'name' => ''],
            ['code' => 'fa-chevron-circle-down', 'name' => ''],
            ['code' => 'fa-chevron-circle-left', 'name' => ''],
            ['code' => 'fa-chevron-circle-right', 'name' => ''],
            ['code' => 'fa-chevron-circle-up', 'name' => ''],
            ['code' => 'fa-chevron-down', 'name' => ''],
            ['code' => 'fa-chevron-left', 'name' => ''],
            ['code' => 'fa-chevron-right', 'name' => ''],
            ['code' => 'fa-chevron-up', 'name' => ''],
            ['code' => 'fa-child', 'name' => ''],
            ['code' => 'fa-chrome', 'name' => ''],
            ['code' => 'fa-circle', 'name' => ''],
            ['code' => 'fa-circle-o', 'name' => ''],
            ['code' => 'fa-circle-o-notch', 'name' => ''],
            ['code' => 'fa-circle-thin', 'name' => ''],
            ['code' => 'fa-clipboard', 'name' => ''],
            ['code' => 'fa-clock-o', 'name' => ''],
            ['code' => 'fa-clone', 'name' => ''],
            ['code' => 'fa-close', 'name' => ''],
            ['code' => 'fa-cloud', 'name' => ''],
            ['code' => 'fa-cloud-download', 'name' => ''],
            ['code' => 'fa-cloud-upload', 'name' => ''],
            ['code' => 'fa-cny', 'name' => ''],
            ['code' => 'fa-code', 'name' => ''],
            ['code' => 'fa-code-fork', 'name' => ''],
            ['code' => 'fa-codepen', 'name' => ''],
            ['code' => 'fa-codiepie', 'name' => ''],
            ['code' => 'fa-coffee', 'name' => ''],
            ['code' => 'fa-cog', 'name' => ''],
            ['code' => 'fa-cogs', 'name' => ''],
            ['code' => 'fa-columns', 'name' => ''],
            ['code' => 'fa-comment', 'name' => ''],
            ['code' => 'fa-comment-o', 'name' => ''],
            ['code' => 'fa-commenting', 'name' => ''],
            ['code' => 'fa-commenting-o', 'name' => ''],
            ['code' => 'fa-comments', 'name' => ''],
            ['code' => 'fa-comments-o', 'name' => ''],
            ['code' => 'fa-compass', 'name' => ''],
            ['code' => 'fa-compress', 'name' => ''],
            ['code' => 'fa-connectdevelop', 'name' => ''],
            ['code' => 'fa-contao', 'name' => ''],
            ['code' => 'fa-copy', 'name' => ''],
            ['code' => 'fa-copyright', 'name' => ''],
            ['code' => 'fa-creative-commons', 'name' => ''],
            ['code' => 'fa-credit-card', 'name' => ''],
            ['code' => 'fa-credit-card-alt', 'name' => ''],
            ['code' => 'fa-crop', 'name' => ''],
            ['code' => 'fa-crosshairs', 'name' => ''],
            ['code' => 'fa-css3', 'name' => ''],
            ['code' => 'fa-cube', 'name' => ''],
            ['code' => 'fa-cubes', 'name' => ''],
            ['code' => 'fa-cut', 'name' => ''],
            ['code' => 'fa-cutlery', 'name' => ''],
            ['code' => 'fa-dashboard', 'name' => ''],
            ['code' => 'fa-dashcube', 'name' => ''],
            ['code' => 'fa-database', 'name' => ''],
            ['code' => 'fa-deaf', 'name' => ''],
            ['code' => 'fa-deafness', 'name' => ''],
            ['code' => 'fa-dedent', 'name' => ''],
            ['code' => 'fa-delicious', 'name' => ''],
            ['code' => 'fa-desktop', 'name' => ''],
            ['code' => 'fa-deviantart', 'name' => ''],
            ['code' => 'fa-diamond', 'name' => ''],
            ['code' => 'fa-digg', 'name' => ''],
            ['code' => 'fa-dollar', 'name' => ''],
            ['code' => 'fa-dot-circle-o', 'name' => ''],
            ['code' => 'fa-download', 'name' => ''],
            ['code' => 'fa-dribbble', 'name' => ''],
            ['code' => 'fa-drivers-license', 'name' => ''],
            ['code' => 'fa-drivers-license-o', 'name' => ''],
            ['code' => 'fa-dropbox', 'name' => ''],
            ['code' => 'fa-drupal', 'name' => ''],
            ['code' => 'fa-edge', 'name' => ''],
            ['code' => 'fa-edit', 'name' => 'Редактировать'],
            ['code' => 'fa-eercast', 'name' => ''],
            ['code' => 'fa-eject', 'name' => ''],
            ['code' => 'fa-ellipsis-h', 'name' => ''],
            ['code' => 'fa-ellipsis-v', 'name' => ''],
            ['code' => 'fa-empire', 'name' => ''],
            ['code' => 'fa-envelope', 'name' => ''],
            ['code' => 'fa-envelope-o', 'name' => ''],
            ['code' => 'fa-envelope-open', 'name' => ''],
            ['code' => 'fa-envelope-open-o', 'name' => ''],
            ['code' => 'fa-envelope-square', 'name' => ''],
            ['code' => 'fa-envira', 'name' => ''],
            ['code' => 'fa-eraser', 'name' => ''],
            ['code' => 'fa-etsy', 'name' => ''],
            ['code' => 'fa-eur', 'name' => ''],
            ['code' => 'fa-euro', 'name' => ''],
            ['code' => 'fa-exchange', 'name' => ''],
            ['code' => 'fa-exclamation', 'name' => ''],
            ['code' => 'fa-exclamation-circle', 'name' => ''],
            ['code' => 'fa-exclamation-triangle', 'name' => ''],
            ['code' => 'fa-expand', 'name' => ''],
            ['code' => 'fa-expeditedssl', 'name' => ''],
            ['code' => 'fa-external-link', 'name' => ''],
            ['code' => 'fa-external-link-square', 'name' => ''],
            ['code' => 'fa-eye', 'name' => 'Просмотреть'],
            ['code' => 'fa-eye-slash', 'name' => 'Скрыть'],
            ['code' => 'fa-eyedropper', 'name' => 'Пипетка'],
            ['code' => 'fa-fa', 'name' => ''],
            ['code' => 'fa-facebook', 'name' => ''],
            ['code' => 'fa-facebook-f', 'name' => ''],
            ['code' => 'fa-facebook-official', 'name' => ''],
            ['code' => 'fa-facebook-square', 'name' => ''],
            ['code' => 'fa-fast-backward', 'name' => ''],
            ['code' => 'fa-fast-forward', 'name' => ''],
            ['code' => 'fa-fax', 'name' => ''],
            ['code' => 'fa-feed', 'name' => ''],
            ['code' => 'fa-female', 'name' => ''],
            ['code' => 'fa-fighter-jet', 'name' => ''],
            ['code' => 'fa-file', 'name' => ''],
            ['code' => 'fa-file-archive-o', 'name' => ''],
            ['code' => 'fa-file-audio-o', 'name' => ''],
            ['code' => 'fa-file-code-o', 'name' => ''],
            ['code' => 'fa-file-excel-o', 'name' => ''],
            ['code' => 'fa-file-image-o', 'name' => ''],
            ['code' => 'fa-file-movie-o', 'name' => ''],
            ['code' => 'fa-file-o', 'name' => ''],
            ['code' => 'fa-file-pdf-o', 'name' => ''],
            ['code' => 'fa-file-photo-o', 'name' => ''],
            ['code' => 'fa-file-picture-o', 'name' => ''],
            ['code' => 'fa-file-powerpoint-o', 'name' => ''],
            ['code' => 'fa-file-sound-o', 'name' => ''],
            ['code' => 'fa-file-text', 'name' => ''],
            ['code' => 'fa-file-text-o', 'name' => ''],
            ['code' => 'fa-file-video-o', 'name' => ''],
            ['code' => 'fa-file-word-o', 'name' => ''],
            ['code' => 'fa-file-zip-o', 'name' => ''],
            ['code' => 'fa-files-o', 'name' => ''],
            ['code' => 'fa-film', 'name' => ''],
            ['code' => 'fa-filter', 'name' => ''],
            ['code' => 'fa-fire', 'name' => ''],
            ['code' => 'fa-fire-extinguisher', 'name' => ''],
            ['code' => 'fa-firefox', 'name' => ''],
            ['code' => 'fa-first-order', 'name' => ''],
            ['code' => 'fa-flag', 'name' => ''],
            ['code' => 'fa-flag-checkered', 'name' => ''],
            ['code' => 'fa-flag-o', 'name' => ''],
            ['code' => 'fa-flash', 'name' => ''],
            ['code' => 'fa-flask', 'name' => ''],
            ['code' => 'fa-flickr', 'name' => ''],
            ['code' => 'fa-floppy-o', 'name' => ''],
            ['code' => 'fa-folder', 'name' => ''],
            ['code' => 'fa-folder-o', 'name' => ''],
            ['code' => 'fa-folder-open', 'name' => ''],
            ['code' => 'fa-folder-open-o', 'name' => ''],
            ['code' => 'fa-font', 'name' => ''],
            ['code' => 'fa-font-awesome', 'name' => ''],
            ['code' => 'fa-fonticons', 'name' => ''],
            ['code' => 'fa-fort-awesome', 'name' => ''],
            ['code' => 'fa-forumbee', 'name' => ''],
            ['code' => 'fa-forward', 'name' => ''],
            ['code' => 'fa-foursquare', 'name' => ''],
            ['code' => 'fa-free-code-camp', 'name' => ''],
            ['code' => 'fa-frown-o', 'name' => ''],
            ['code' => 'fa-futbol-o', 'name' => ''],
            ['code' => 'fa-gamepad', 'name' => ''],
            ['code' => 'fa-gavel', 'name' => ''],
            ['code' => 'fa-gbp', 'name' => ''],
            ['code' => 'fa-ge', 'name' => ''],
            ['code' => 'fa-gear', 'name' => ''],
            ['code' => 'fa-gears', 'name' => ''],
            ['code' => 'fa-genderless', 'name' => ''],
            ['code' => 'fa-get-pocket', 'name' => ''],
            ['code' => 'fa-gg', 'name' => ''],
            ['code' => 'fa-gg-circle', 'name' => ''],
            ['code' => 'fa-gift', 'name' => ''],
            ['code' => 'fa-git', 'name' => ''],
            ['code' => 'fa-git-square', 'name' => ''],
            ['code' => 'fa-github', 'name' => ''],
            ['code' => 'fa-github-alt', 'name' => ''],
            ['code' => 'fa-github-square', 'name' => ''],
            ['code' => 'fa-gitlab', 'name' => ''],
            ['code' => 'fa-gittip', 'name' => ''],
            ['code' => 'fa-glass', 'name' => ''],
            ['code' => 'fa-glide', 'name' => ''],
            ['code' => 'fa-glide-g', 'name' => ''],
            ['code' => 'fa-globe', 'name' => ''],
            ['code' => 'fa-google', 'name' => ''],
            ['code' => 'fa-google-plus', 'name' => ''],
            ['code' => 'fa-google-plus-circle', 'name' => ''],
            ['code' => 'fa-google-plus-official', 'name' => ''],
            ['code' => 'fa-google-plus-square', 'name' => ''],
            ['code' => 'fa-google-wallet', 'name' => ''],
            ['code' => 'fa-graduation-cap', 'name' => ''],
            ['code' => 'fa-gratipay', 'name' => ''],
            ['code' => 'fa-grav', 'name' => ''],
            ['code' => 'fa-group', 'name' => ''],
            ['code' => 'fa-h-square', 'name' => ''],
            ['code' => 'fa-hacker-news', 'name' => ''],
            ['code' => 'fa-hand-grab-o', 'name' => ''],
            ['code' => 'fa-hand-lizard-o', 'name' => ''],
            ['code' => 'fa-hand-o-down', 'name' => ''],
            ['code' => 'fa-hand-o-left', 'name' => ''],
            ['code' => 'fa-hand-o-right', 'name' => ''],
            ['code' => 'fa-hand-o-up', 'name' => ''],
            ['code' => 'fa-hand-paper-o', 'name' => ''],
            ['code' => 'fa-hand-peace-o', 'name' => ''],
            ['code' => 'fa-hand-pointer-o', 'name' => ''],
            ['code' => 'fa-hand-rock-o', 'name' => ''],
            ['code' => 'fa-hand-scissors-o', 'name' => ''],
            ['code' => 'fa-hand-spock-o', 'name' => ''],
            ['code' => 'fa-hand-stop-o', 'name' => ''],
            ['code' => 'fa-handshake-o', 'name' => ''],
            ['code' => 'fa-hard-of-hearing', 'name' => ''],
            ['code' => 'fa-hashtag', 'name' => ''],
            ['code' => 'fa-hdd-o', 'name' => ''],
            ['code' => 'fa-header', 'name' => ''],
            ['code' => 'fa-headphones', 'name' => ''],
            ['code' => 'fa-heart', 'name' => ''],
            ['code' => 'fa-heart-o', 'name' => ''],
            ['code' => 'fa-heartbeat', 'name' => ''],
            ['code' => 'fa-history', 'name' => ''],
            ['code' => 'fa-home', 'name' => ''],
            ['code' => 'fa-hospital-o', 'name' => ''],
            ['code' => 'fa-hotel', 'name' => ''],
            ['code' => 'fa-hourglass', 'name' => ''],
            ['code' => 'fa-hourglass-1', 'name' => ''],
            ['code' => 'fa-hourglass-2', 'name' => ''],
            ['code' => 'fa-hourglass-3', 'name' => ''],
            ['code' => 'fa-hourglass-end', 'name' => ''],
            ['code' => 'fa-hourglass-half', 'name' => ''],
            ['code' => 'fa-hourglass-o', 'name' => ''],
            ['code' => 'fa-hourglass-start', 'name' => ''],
            ['code' => 'fa-houzz', 'name' => ''],
            ['code' => 'fa-html5', 'name' => ''],
            ['code' => 'fa-i-cursor', 'name' => ''],
            ['code' => 'fa-id-badge', 'name' => ''],
            ['code' => 'fa-id-card', 'name' => ''],
            ['code' => 'fa-id-card-o', 'name' => ''],
            ['code' => 'fa-ils', 'name' => ''],
            ['code' => 'fa-image', 'name' => ''],
            ['code' => 'fa-imdb', 'name' => ''],
            ['code' => 'fa-inbox', 'name' => ''],
            ['code' => 'fa-indent', 'name' => ''],
            ['code' => 'fa-industry', 'name' => ''],
            ['code' => 'fa-info', 'name' => ''],
            ['code' => 'fa-info-circle', 'name' => ''],
            ['code' => 'fa-inr', 'name' => ''],
            ['code' => 'fa-instagram', 'name' => ''],
            ['code' => 'fa-institution', 'name' => ''],
            ['code' => 'fa-internet-explorer', 'name' => ''],
            ['code' => 'fa-intersex', 'name' => ''],
            ['code' => 'fa-ioxhost', 'name' => ''],
            ['code' => 'fa-italic', 'name' => ''],
            ['code' => 'fa-joomla', 'name' => ''],
            ['code' => 'fa-jpy', 'name' => ''],
            ['code' => 'fa-jsfiddle', 'name' => ''],
            ['code' => 'fa-key', 'name' => ''],
            ['code' => 'fa-keyboard-o', 'name' => ''],
            ['code' => 'fa-krw', 'name' => ''],
            ['code' => 'fa-language', 'name' => ''],
            ['code' => 'fa-laptop', 'name' => ''],
            ['code' => 'fa-lastfm', 'name' => ''],
            ['code' => 'fa-lastfm-square', 'name' => ''],
            ['code' => 'fa-leaf', 'name' => ''],
            ['code' => 'fa-leanpub', 'name' => ''],
            ['code' => 'fa-legal', 'name' => ''],
            ['code' => 'fa-lemon-o', 'name' => ''],
            ['code' => 'fa-level-down', 'name' => ''],
            ['code' => 'fa-level-up', 'name' => ''],
            ['code' => 'fa-life-bouy', 'name' => ''],
            ['code' => 'fa-life-buoy', 'name' => ''],
            ['code' => 'fa-life-ring', 'name' => ''],
            ['code' => 'fa-life-saver', 'name' => ''],
            ['code' => 'fa-lightbulb-o', 'name' => ''],
            ['code' => 'fa-line-chart', 'name' => ''],
            ['code' => 'fa-link', 'name' => ''],
            ['code' => 'fa-linkedin', 'name' => ''],
            ['code' => 'fa-linkedin-square', 'name' => ''],
            ['code' => 'fa-linode', 'name' => ''],
            ['code' => 'fa-linux', 'name' => ''],
            ['code' => 'fa-list', 'name' => ''],
            ['code' => 'fa - list-alt', 'name' => ''],
            ['code' => 'fa-list-ol', 'name' => ''],
            ['code' => 'fa-list-ul', 'name' => ''],
            ['code' => 'fa-location-arrow', 'name' => ''],
            ['code' => 'fa-lock', 'name' => ''],
            ['code' => 'fa-long-arrow-down', 'name' => ''],
            ['code' => 'fa-long-arrow-left', 'name' => ''],
            ['code' => 'fa-long-arrow-right', 'name' => ''],
            ['code' => 'fa-long-arrow-up', 'name' => ''],
            ['code' => 'fa-low-vision', 'name' => ''],
            ['code' => 'fa-magic', 'name' => ''],
            ['code' => 'fa-magnet', 'name' => ''],
            ['code' => 'fa-mail-forward', 'name' => ''],
            ['code' => 'fa-mail-reply', 'name' => ''],
            ['code' => 'fa-mail-reply-all', 'name' => ''],
            ['code' => 'fa-male', 'name' => ''],
            ['code' => 'fa-map', 'name' => ''],
            ['code' => 'fa-map-marker', 'name' => ''],
            ['code' => 'fa-map-o', 'name' => ''],
            ['code' => 'fa-map-pin', 'name' => ''],
            ['code' => 'fa-map-signs', 'name' => ''],
            ['code' => 'fa-mars', 'name' => ''],
            ['code' => 'fa-mars-double', 'name' => ''],
            ['code' => 'fa-mars-stroke', 'name' => ''],
            ['code' => 'fa-mars-stroke-h', 'name' => ''],
            ['code' => 'fa-mars-stroke-v', 'name' => ''],
            ['code' => 'fa-maxcdn', 'name' => ''],
            ['code' => 'fa-meanpath', 'name' => ''],
            ['code' => 'fa-medium', 'name' => ''],
            ['code' => 'fa-medkit', 'name' => ''],
            ['code' => 'fa-meetup', 'name' => ''],
            ['code' => 'fa-meh-o', 'name' => ''],
            ['code' => 'fa-mercury', 'name' => ''],
            ['code' => 'fa-microchip', 'name' => ''],
            ['code' => 'fa-microphone', 'name' => ''],
            ['code' => 'fa-microphone-slash', 'name' => ''],
            ['code' => 'fa-minus', 'name' => ''],
            ['code' => 'fa-minus-circle', 'name' => ''],
            ['code' => 'fa-minus-square', 'name' => ''],
            ['code' => 'fa-minus-square-o', 'name' => ''],
            ['code' => 'fa-mixcloud', 'name' => ''],
            ['code' => 'fa-mobile', 'name' => ''],
            ['code' => 'fa-mobile-phone', 'name' => ''],
            ['code' => 'fa-modx', 'name' => ''],
            ['code' => 'fa-money', 'name' => ''],
            ['code' => 'fa-moon-o', 'name' => ''],
            ['code' => 'fa-mortar-board', 'name' => ''],
            ['code' => 'fa-motorcycle', 'name' => ''],
            ['code' => 'fa-mouse-pointer', 'name' => ''],
            ['code' => 'fa-music', 'name' => ''],
            ['code' => 'fa-navicon', 'name' => ''],
            ['code' => 'fa-neuter', 'name' => ''],
            ['code' => 'fa-newspaper-o', 'name' => ''],
            ['code' => 'fa-object-group', 'name' => ''],
            ['code' => 'fa-object-ungroup', 'name' => ''],
            ['code' => 'fa-odnoklassniki', 'name' => ''],
            ['code' => 'fa-odnoklassniki-square', 'name' => ''],
            ['code' => 'fa-opencart', 'name' => ''],
            ['code' => 'fa-openid', 'name' => ''],
            ['code' => 'fa-opera', 'name' => ''],
            ['code' => 'fa-optin-monster', 'name' => ''],
            ['code' => 'fa-outdent', 'name' => ''],
            ['code' => 'fa-pagelines', 'name' => ''],
            ['code' => 'fa-paint-brush', 'name' => ''],
            ['code' => 'fa-paper-plane', 'name' => ''],
            ['code' => 'fa-paper-plane-o', 'name' => ''],
            ['code' => 'fa-paperclip', 'name' => ''],
            ['code' => 'fa-paragraph', 'name' => ''],
            ['code' => 'fa-paste', 'name' => ''],
            ['code' => 'fa-pause', 'name' => ''],
            ['code' => 'fa-pause-circle', 'name' => ''],
            ['code' => 'fa-pause-circle-o', 'name' => ''],
            ['code' => 'fa-paw', 'name' => ''],
            ['code' => 'fa-paypal', 'name' => ''],
            ['code' => 'fa-pencil', 'name' => ''],
            ['code' => 'fa-pencil-square', 'name' => ''],
            ['code' => 'fa-pencil-square-o', 'name' => ''],
            ['code' => 'fa-percent', 'name' => ''],
            ['code' => 'fa-phone', 'name' => ''],
            ['code' => 'fa-phone-square', 'name' => ''],
            ['code' => 'fa-photo', 'name' => ''],
            ['code' => 'fa-picture-o', 'name' => ''],
            ['code' => 'fa-pie-chart', 'name' => ''],
            ['code' => 'fa-pied-piper', 'name' => ''],
            ['code' => 'fa-pied-piper-alt', 'name' => ''],
            ['code' => 'fa-pied-piper-pp', 'name' => ''],
            ['code' => 'fa-pinterest', 'name' => ''],
            ['code' => 'fa-pinterest-p', 'name' => ''],
            ['code' => 'fa-pinterest-square', 'name' => ''],
            ['code' => 'fa-plane', 'name' => ''],
            ['code' => 'fa-play', 'name' => ''],
            ['code' => 'fa-play-circle', 'name' => ''],
            ['code' => 'fa-play-circle-o', 'name' => ''],
            ['code' => 'fa-plug', 'name' => ''],
            ['code' => 'fa-plus', 'name' => ''],
            ['code' => 'fa-plus-circle', 'name' => ''],
            ['code' => 'fa-plus-square', 'name' => ''],
            ['code' => 'fa-plus-square-o', 'name' => ''],
            ['code' => 'fa-podcast', 'name' => ''],
            ['code' => 'fa-power-off', 'name' => ''],
            ['code' => 'fa-print', 'name' => ''],
            ['code' => 'fa-product-hunt', 'name' => ''],
            ['code' => 'fa-puzzle-piece', 'name' => ''],
            ['code' => 'fa-qq', 'name' => ''],
            ['code' => 'fa-qrcode', 'name' => ''],
            ['code' => 'fa-question', 'name' => ''],
            ['code' => 'fa-question-circle', 'name' => ''],
            ['code' => 'fa-question-circle-o', 'name' => ''],
            ['code' => 'fa-quora', 'name' => ''],
            ['code' => 'fa-quote-left', 'name' => ''],
            ['code' => 'fa-quote-right', 'name' => ''],
            ['code' => 'fa-ra', 'name' => ''],
            ['code' => 'fa-random', 'name' => ''],
            ['code' => 'fa-ravelry', 'name' => ''],
            ['code' => 'fa-rebel', 'name' => ''],
            ['code' => 'fa-recycle', 'name' => ''],
            ['code' => 'fa-reddit', 'name' => ''],
            ['code' => 'fa-reddit-alien', 'name' => ''],
            ['code' => 'fa-reddit-square', 'name' => ''],
            ['code' => 'fa-refresh', 'name' => ''],
            ['code' => 'fa-registered', 'name' => ''],
            ['code' => 'fa-remove', 'name' => ''],
            ['code' => 'fa-renren', 'name' => ''],
            ['code' => 'fa-reorder', 'name' => ''],
            ['code' => 'fa-repeat', 'name' => ''],
            ['code' => 'fa-reply', 'name' => ''],
            ['code' => 'fa-reply-all', 'name' => ''],
            ['code' => 'fa-resistance', 'name' => ''],
            ['code' => 'fa-retweet', 'name' => ''],
            ['code' => 'fa-rmb', 'name' => ''],
            ['code' => 'fa-road', 'name' => ''],
            ['code' => 'fa-rocket', 'name' => ''],
            ['code' => 'fa-rotate-left', 'name' => ''],
            ['code' => 'fa-rotate-right', 'name' => ''],
            ['code' => 'fa-rouble', 'name' => ''],
            ['code' => 'fa-rss', 'name' => ''],
            ['code' => 'fa-rss-square', 'name' => ''],
            ['code' => 'fa-rub', 'name' => ''],
            ['code' => 'fa-ruble', 'name' => ''],
            ['code' => 'fa-rupee', 'name' => ''],
            ['code' => 'fa-s15', 'name' => ''],
            ['code' => 'fa-safari', 'name' => ''],
            ['code' => 'fa-save', 'name' => ''],
            ['code' => 'fa-scissors', 'name' => ''],
            ['code' => 'fa-scribd', 'name' => ''],
            ['code' => 'fa-search', 'name' => ''],
            ['code' => 'fa-search-minus', 'name' => ''],
            ['code' => 'fa-search-plus', 'name' => ''],
            ['code' => 'fa-sellsy', 'name' => ''],
            ['code' => 'fa-send', 'name' => ''],
            ['code' => 'fa-send-o', 'name' => ''],
            ['code' => 'fa-server', 'name' => ''],
            ['code' => 'fa-share', 'name' => ''],
            ['code' => 'fa-share-alt', 'name' => ''],
            ['code' => 'fa-share-alt-square', 'name' => ''],
            ['code' => 'fa-share-square', 'name' => ''],
            ['code' => 'fa-share-square-o', 'name' => ''],
            ['code' => 'fa-shekel', 'name' => ''],
            ['code' => 'fa-sheqel', 'name' => ''],
            ['code' => 'fa-shield', 'name' => ''],
            ['code' => 'fa-ship', 'name' => ''],
            ['code' => 'fa-shirtsinbulk', 'name' => ''],
            ['code' => 'fa-shopping-bag', 'name' => ''],
            ['code' => 'fa-shopping-basket', 'name' => ''],
            ['code' => 'fa-shopping-cart', 'name' => ''],
            ['code' => 'fa-shower', 'name' => ''],
            ['code' => 'fa-sign-in', 'name' => ''],
            ['code' => 'fa-sign-language', 'name' => ''],
            ['code' => 'fa-sign-out', 'name' => ''],
            ['code' => 'fa-signal', 'name' => ''],
            ['code' => 'fa-signing', 'name' => ''],
            ['code' => 'fa-simplybuilt', 'name' => ''],
            ['code' => 'fa-sitemap', 'name' => ''],
            ['code' => 'fa-skyatlas', 'name' => ''],
            ['code' => 'fa-skype', 'name' => ''],
            ['code' => 'fa-slack', 'name' => ''],
            ['code' => 'fa-sliders', 'name' => ''],
            ['code' => 'fa-slideshare', 'name' => ''],
            ['code' => 'fa-smile-o', 'name' => ''],
            ['code' => 'fa-snapchat', 'name' => ''],
            ['code' => 'fa-snapchat-ghost', 'name' => ''],
            ['code' => 'fa-snapchat-square', 'name' => ''],
            ['code' => 'fa-snowflake-o', 'name' => ''],
            ['code' => 'fa-soccer-ball-o', 'name' => ''],
            ['code' => 'fa-sort', 'name' => ''],
            ['code' => 'fa-sort-alpha-asc', 'name' => ''],
            ['code' => 'fa-sort-alpha-desc', 'name' => ''],
            ['code' => 'fa-sort-amount-asc', 'name' => ''],
            ['code' => 'fa-sort-amount-desc', 'name' => ''],
            ['code' => 'fa-sort-asc', 'name' => ''],
            ['code' => 'fa-sort-desc', 'name' => ''],
            ['code' => 'fa-sort-down', 'name' => ''],
            ['code' => 'fa-sort-numeric-asc', 'name' => ''],
            ['code' => 'fa-sort-numeric-desc', 'name' => ''],
            ['code' => 'fa-sort-up', 'name' => ''],
            ['code' => 'fa-soundcloud', 'name' => ''],
            ['code' => 'fa-space-shuttle', 'name' => ''],
            ['code' => 'fa-spinner', 'name' => ''],
            ['code' => 'fa-spoon', 'name' => ''],
            ['code' => 'fa-spotify', 'name' => ''],
            ['code' => 'fa-square', 'name' => ''],
            ['code' => 'fa-square-o', 'name' => ''],
            ['code' => 'fa-stack-exchange', 'name' => ''],
            ['code' => 'fa-stack-overflow', 'name' => ''],
            ['code' => 'fa-star', 'name' => ''],
            ['code' => 'fa-star-half', 'name' => ''],
            ['code' => 'fa-star-half-empty', 'name' => ''],
            ['code' => 'fa - star - half - full', 'name' => ''],
            ['code' => 'fa-star-half-o', 'name' => ''],
            ['code' => 'fa-star-o', 'name' => ''],
            ['code' => 'fa-steam', 'name' => ''],
            ['code' => 'fa-steam-square', 'name' => ''],
            ['code' => 'fa-step-backward', 'name' => ''],
            ['code' => 'fa-step-forward', 'name' => ''],
            ['code' => 'fa-stethoscope', 'name' => ''],
            ['code' => 'fa-sticky-note', 'name' => ''],
            ['code' => 'fa-sticky-note-o', 'name' => ''],
            ['code' => 'fa-stop', 'name' => ''],
            ['code' => 'fa-stop-circle', 'name' => ''],
            ['code' => 'fa-stop-circle-o', 'name' => ''],
            ['code' => 'fa-street-view', 'name' => ''],
            ['code' => 'fa-strikethrough', 'name' => ''],
            ['code' => 'fa-stumbleupon', 'name' => ''],
            ['code' => 'fa-stumbleupon-circle', 'name' => ''],
            ['code' => 'fa-subscript', 'name' => ''],
            ['code' => 'fa-subway', 'name' => ''],
            ['code' => 'fa-suitcase', 'name' => ''],
            ['code' => 'fa-sun-o', 'name' => ''],
            ['code' => 'fa-superpowers', 'name' => ''],
            ['code' => 'fa-superscript', 'name' => ''],
            ['code' => 'fa-support', 'name' => ''],
            ['code' => 'fa-table', 'name' => ''],
            ['code' => 'fa-tablet', 'name' => ''],
            ['code' => 'fa-tachometer', 'name' => ''],
            ['code' => 'fa-tag', 'name' => ''],
            ['code' => 'fa-tags', 'name' => ''],
            ['code' => 'fa-tasks', 'name' => ''],
            ['code' => 'fa-taxi', 'name' => ''],
            ['code' => 'fa-telegram', 'name' => ''],
            ['code' => 'fa-television', 'name' => ''],
            ['code' => 'fa-tencent-weibo', 'name' => ''],
            ['code' => 'fa-terminal', 'name' => ''],
            ['code' => 'fa-text-height', 'name' => ''],
            ['code' => 'fa-text-width', 'name' => ''],
            ['code' => 'fa-th', 'name' => ''],
            ['code' => 'fa-th-large', 'name' => ''],
            ['code' => 'fa-th-list', 'name' => ''],
            ['code' => 'fa-themeisle', 'name' => ''],
            ['code' => 'fa-thermometer', 'name' => ''],
            ['code' => 'fa-thermometer-0', 'name' => ''],
            ['code' => 'fa-thermometer-1', 'name' => ''],
            ['code' => 'fa-thermometer-2', 'name' => ''],
            ['code' => 'fa-thermometer-3', 'name' => ''],
            ['code' => 'fa-thermometer-4', 'name' => ''],
            ['code' => 'fa-thermometer-empty', 'name' => ''],
            ['code' => 'fa-thermometer-full', 'name' => ''],
            ['code' => 'fa-thermometer-half', 'name' => ''],
            ['code' => 'fa-thermometer-quarter', 'name' => ''],
            ['code' => 'fa-thermometer-three-quarters', 'name' => ''],
            ['code' => 'fa-thumb-tack', 'name' => ''],
            ['code' => 'fa-thumbs-down', 'name' => ''],
            ['code' => 'fa-thumbs-o-down', 'name' => ''],
            ['code' => 'fa-thumbs-o-up', 'name' => ''],
            ['code' => 'fa-thumbs-up', 'name' => ''],
            ['code' => 'fa-ticket', 'name' => ''],
            ['code' => 'fa-times', 'name' => ''],
            ['code' => 'fa-times-circle', 'name' => ''],
            ['code' => 'fa-times-circle-o', 'name' => ''],
            ['code' => 'fa-times-rectangle', 'name' => ''],
            ['code' => 'fa-times-rectangle-o', 'name' => ''],
            ['code' => 'fa-tint', 'name' => ''],
            ['code' => 'fa-toggle-down', 'name' => ''],
            ['code' => 'fa-toggle-left', 'name' => ''],
            ['code' => 'fa-toggle-off', 'name' => ''],
            ['code' => 'fa-toggle-on', 'name' => ''],
            ['code' => 'fa-toggle-right', 'name' => ''],
            ['code' => 'fa-toggle-up', 'name' => ''],
            ['code' => 'fa-trademark', 'name' => ''],
            ['code' => 'fa-train', 'name' => ''],
            ['code' => 'fa-transgender', 'name' => ''],
            ['code' => 'fa-transgender-alt', 'name' => ''],
            ['code' => 'fa-trash', 'name' => ''],
            ['code' => 'fa-trash-o', 'name' => ''],
            ['code' => 'fa-tree', 'name' => ''],
            ['code' => 'fa-trello', 'name' => ''],
            ['code' => 'fa-tripadvisor', 'name' => ''],
            ['code' => 'fa-trophy', 'name' => ''],
            ['code' => 'fa-truck', 'name' => ''],
            ['code' => 'fa-try', 'name' => ''],
            ['code' => 'fa-tty', 'name' => ''],
            ['code' => 'fa-tumblr', 'name' => ''],
            ['code' => 'fa-tumblr-square', 'name' => ''],
            ['code' => 'fa-turkish-lira', 'name' => ''],
            ['code' => 'fa-tv', 'name' => ''],
            ['code' => 'fa-twitch', 'name' => ''],
            ['code' => 'fa-twitter', 'name' => ''],
            ['code' => 'fa-twitter-square', 'name' => ''],
            ['code' => 'fa-umbrella', 'name' => ''],
            ['code' => 'fa-underline', 'name' => ''],
            ['code' => 'fa-undo', 'name' => ''],
            ['code' => 'fa-universal-access', 'name' => ''],
            ['code' => 'fa-university', 'name' => ''],
            ['code' => 'fa-unlink', 'name' => ''],
            ['code' => 'fa-unlock', 'name' => ''],
            ['code' => 'fa-unlock-alt', 'name' => ''],
            ['code' => 'fa-unsorted', 'name' => ''],
            ['code' => 'fa-upload', 'name' => ''],
            ['code' => 'fa-usb', 'name' => ''],
            ['code' => 'fa-usd', 'name' => ''],
            ['code' => 'fa-user', 'name' => ''],
            ['code' => 'fa-user-circle', 'name' => ''],
            ['code' => 'fa-user-circle-o', 'name' => ''],
            ['code' => 'fa-user-md', 'name' => ''],
            ['code' => 'fa-user-o', 'name' => ''],
            ['code' => 'fa-user-plus', 'name' => ''],
            ['code' => 'fa-user-secret', 'name' => ''],
            ['code' => 'fa-user-times', 'name' => ''],
            ['code' => 'fa-users', 'name' => ''],
            ['code' => 'fa-vcard', 'name' => ''],
            ['code' => 'fa-vcard-o', 'name' => ''],
            ['code' => 'fa-venus', 'name' => ''],
            ['code' => 'fa-venus-double', 'name' => ''],
            ['code' => 'fa-venus-mars', 'name' => ''],
            ['code' => 'fa-viacoin', 'name' => ''],
            ['code' => 'fa-viadeo', 'name' => ''],
            ['code' => 'fa-viadeo-square', 'name' => ''],
            ['code' => 'fa-video-camera', 'name' => ''],
            ['code' => 'fa-vimeo', 'name' => ''],
            ['code' => 'fa-vimeo-square', 'name' => ''],
            ['code' => 'fa-vine', 'name' => ''],
            ['code' => 'fa-vk', 'name' => ''],
            ['code' => 'fa-volume-control-phone', 'name' => ''],
            ['code' => 'fa-volume-down', 'name' => ''],
            ['code' => 'fa-volume-off', 'name' => ''],
            ['code' => 'fa-volume-up', 'name' => ''],
            ['code' => 'fa-warning', 'name' => ''],
            ['code' => 'fa-wechat', 'name' => ''],
            ['code' => 'fa-weibo', 'name' => ''],
            ['code' => 'fa-weixin', 'name' => ''],
            ['code' => 'fa-whatsapp', 'name' => ''],
            ['code' => 'fa-wheelchair', 'name' => ''],
            ['code' => 'fa-wheelchair-alt', 'name' => ''],
            ['code' => 'fa-wifi', 'name' => ''],
            ['code' => 'fa-wikipedia-w', 'name' => ''],
            ['code' => 'fa-window-close', 'name' => ''],
            ['code' => 'fa-window-close-o', 'name' => ''],
            ['code' => 'fa-window-maximize', 'name' => ''],
            ['code' => 'fa-window-minimize', 'name' => ''],
            ['code' => 'fa-window-restore', 'name' => ''],
            ['code' => 'fa-windows', 'name' => ''],
            ['code' => 'fa-won', 'name' => ''],
            ['code' => 'fa-wordpress', 'name' => ''],
            ['code' => 'fa-wpbeginner', 'name' => ''],
            ['code' => 'fa-wpexplorer', 'name' => ''],
            ['code' => 'fa-wpforms', 'name' => ''],
            ['code' => 'fa-wrench', 'name' => ''],
            ['code' => 'fa-xing', 'name' => ''],
            ['code' => 'fa-xing-square', 'name' => ''],
            ['code' => 'fa-y-combinator', 'name' => ''],
            ['code' => 'fa-y-combinator-square', 'name' => ''],
            ['code' => 'fa-yahoo', 'name' => ''],
            ['code' => 'fa-yc', 'name' => ''],
            ['code' => 'fa-yc-square', 'name' => ''],
            ['code' => 'fa-yelp', 'name' => ''],
            ['code' => 'fa-yen', 'name' => ''],
            ['code' => 'fa-yoast', 'name' => ''],
            ['code' => 'fa-youtube', 'name' => ''],
            ['code' => 'fa-youtube-play', 'name' => ''],
            ['code' => 'fa-youtube-square', 'name' => ''],
        ];
        $i = 0;
        foreach ($icons as $icon) {
            $i++;
            Icons::create([
                'name' => $icon['name'],
                'code' => $icon['code']
            ]);
        }
        return redirect()->route('type-content.index')->with('success', 'Создано' . $i . 'иконок');
    }

    public function getIcons()
    {
        //dd('test');
//        return response()->json(Icons::all());
        return 'Success!';
        //return redirect()->route('type-content.index')->with('success', 'Создано' . $i . 'иконок');
    }
}
