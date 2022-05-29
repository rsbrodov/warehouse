<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryElement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Itstructure\GridView\DataProviders\EloquentDataProvider;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /*public function __construct()
    {
        $this->middleware('auth:web');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(Auth::guard('web')->check()) {
            return view('home');
            //return 'web auth';
        }elseif(Auth::guard('api')->check()) {
            return 'api auth';
        }else {
            return 'not auth';
        }
    }

    public function index2()
    {
        if(Auth::check()){
            $user = Auth::user();
            //$user->assignRole('SuperAdmin');
            if($user->hasRole('SuperAdmin')){
                $user_id = Auth::id();
                $user = Auth::user();
                $users = User::all();
                return view('users.user-create-view', ['user_id' => $user_id, 'user_login' => $user->name, 'users' => $users]);
            }
        }else{
            return 'not auth';
        }
    }

    public function index3()
    {

        $dic = Dictionary::create(['code'=>'1523', 'name'=> 'namre', 'description'=> 'sdfsdrfsd', 'archive'=>0, 'created_author'=>1, 'updated_author'=>1]);
        $dictionary_element = DictionaryElement::create(['dictionary_id'=> $dic->id, 'value'=> 'sfsdf', 'created_author'=>1, 'updated_author'=>1]);
        //$user = User::create(['name'=>'admin', 'email'=> 'admin@mail.ru', 'pass'=> 'sdfsdrfsd', 'archive'=>0, 'created_author'=>1, 'updated_author'=>1]);
        print_r($dic);
        print_r($dictionary_element);
    }


    public function test()
    {
        if(Auth::guard('web')->check()) {
            $object = (object)[
                [
                    "idRow" => "1",
                    "col" => [
                        [
                            "idCol" => "row1/col1",
                            "element" => [
                                [
                                    "id" => 1,
                                    "type" => "text",
                                    "order" => 1,
                                    "title" => "Название единорога",
                                    "name" => "name",
                                    "required" => true,
                                ],
                                [
                                    "id" => 2,
                                    "type" => "text",
                                    "order" => 3,
                                    "title" => "Цена единорога",
                                    "required" => true,
                                    "name" => "cost",
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "idRow" => "2",
                    "col" => [
                        [
                            "idCol" => "row2/col2",
                            "element" => [
                                [
                                    "id" => 1,
                                    "type" => "checkbox",
                                    "order" => 4,
                                    "title" => "Пол единорога (БД)",
                                    "name" => "sex",
                                    "required" => true,
                                    "parameters" => "9ca1da69-7104-4b15-ad89-a645d143abef"
                                ],
                                [
                                    "id" => 3,
                                    "type" => "text",
                                    "order" => 2,
                                    "title" => "Размер единорога",
                                    "name" => "size",
                                    "required" => true
                                ],
                                [
                                    "id" => 2,
                                    "type" => "textarea",
                                    "order" => 2,
                                    "title" => "Описание единорога",
                                    "name" => "description",
                                    "required" => true

                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "idRow" => "2",
                    "col" => [
                        [
                            "idCol" => "row2/col2",
                            "element" => [
                                [
                                    "id" => 1,
                                    "type" => "text",
                                    "order" => 4,
                                    "title" => "Местоположение единорога",
                                    "name" => "location",
                                    "required" => true
                                ],
                                [
                                    "id" => 3,
                                    "type" => "select",
                                    "order" => 2,
                                    "title" => "Наличие разрешения на управление (БД)",
                                    "name" => "permission",
                                    "required" => true,
                                    "parameters" => "dd882515-16ea-4d3a-ba87-6cb05126702e"
                                ],
                                [
                                    "id" => 2,
                                    "type" => "select",
                                    "order" => 2,
                                    "title" => "Тип допуска (БД)",
                                    "name" => "admission",
                                    "required" => true,
                                    "parameters" =>  "a0114e96-d434-4d91-845b-0b76eb531cef"
                                ],
                                [
                                    "id" => 2,
                                    "type" => "radio",
                                    "order" => 2,
                                    "title" => "Предпочитаемый способ езды (БД)",
                                    "name" => "rideType",
                                    "required" => true,
                                    "parameters" =>  "81f52fa0-e5e1-41d4-a530-0102e4d0bbdb"
                                ]
                            ]
                        ]
                    ]
                ],
            ];
            $rand_words_1 = [
                'Безотказная',
                'Развратная',
                'Раскрытая',
                'Офигевшая',
                'Шальная',
                'Бухая',
                'Вычурная',
                'Честная',
                'Приятная',
                'Горячая',
                'Бесконечная',
                'Космическая',
                'Протёкшая',
            ];
            $rand_words_2 = [
                'императрица',
                'богиня',
                'машина',
                'дочь олигарха',
                'развратница',
                'микроволновка',
                'кочерга',
                'кувалда',
                'книга',
                'бутылка',
                'простокваша',
                'пальма',
                'швабра',
            ];
            $rand_words_3 = [
                'на выезде',
                'на задании',
                'вышла на охоту',
                'кусает шашлык',
                'выглядит развратно',
                'хочет тебя',
                'волнуется и переживает',
                'уже хочет тебя',
                'отключилась',
                'в жопе',
            ];
            $random_status = [
                'Published',
                'Draft',
                'Archive'
            ];
            for ($i = 0; $i < 5000; $i++){
                $str = $rand_words_1[rand(0, count($rand_words_1)-1)] . ' ' . $rand_words_2[rand(0, count($rand_words_2)-1)] . ' ' . $rand_words_3[rand(0, count($rand_words_3)-1)];
                \App\Models\TypeContent::create(
                    [
                        'id_global'      => \Illuminate\Support\Str::uuid()->toString(),
                        'name'           => $str,
                        'description'    => $str,
                        'owner'          => 'TEST10000',
                        'active_from'    => date_create('2022-01-01'),
                        'active_after'   => date_create('2023-02-02'),
                        'status'         => $random_status[rand(0, count($random_status)-1)],
                        'version_major'  => '1',
                        'version_minor'  => '0',
                        'icon'           => 'fa-battery',
                        'api_url'        => str_slug($str),
                        'body' =>serialize($object),
                        'based_type'     => null,
                        'created_author' => 1,
                        'updated_author' => 1,
                    ]
                );
            }
           return redirect()->route('home')->with('success', '10000 типов контента успешно созданы!');
            //return view('home-test', ['str'=>$str]);
        } else {
            return 'not auth';
        }
    }
    public function test2()
    {
        $rand_words_1 = [
            'честных',
            'безумных',
            'квадратных',
            'великих',
            'странных',
            'культурных',
            'неизвестных',
            'великолепных'
        ];
        $rand_words_2 = [
            'дел',
            'стрел',
            'людей',
            'коров',
            'бобров',
            'домов',
            'лольных кеков',
            'пиндосов'
        ];

        //for ($i = 0; $i < 5000; $i++){
            $str = $rand_words_1[rand(0, count($rand_words_1)-1)] . ' ' . $rand_words_2[rand(0, count($rand_words_2)-1)] . ' ';
            $dictionary = \App\Models\Dictionary::create([
                   'code' => 'TEST10000',
                   'name' => 'Справочник '.$str,
                   'description' => 'Описание справочника '.$str,
                   'archive' => rand(0, 1),
                   'created_author' => 1,
                   'updated_author' => 1
               ]);
            for ($e = 0; $e < rand(1, 10); $e++) {
                \App\Models\DictionaryElement::create(
                    [
                        'dictionary_id'  => $dictionary->id,
                        'value'          => 'TEST10000',
                        'created_author' => 1,
                        'updated_author' => 1,
                    ]
                );
            }
       // }

        return redirect()->route('home')->with('warning', '10000 справочников успешно созданы!');
    }
    public function deleteTEST10000TC(){
        if(Auth::guard('web')->check()) {
            $tc10000 = \App\Models\TypeContent::where('owner', 'TEST10000');
            if ($tc10000) {
                $tc10000->delete();
            }
            return redirect()->route('home')->with('success', '10000 типов контента успешно удалены!');
        } else {
            return 'not auth';
        }
    }
    public function deleteTEST10000D(){
        if(Auth::guard('web')->check()) {
            $d10000 = \App\Models\Dictionary::where('code', 'TEST10000');
            //todo: не удаляется связь по внешнему ключу
            if ($d10000) {
                $d10000->delete();
            }
            return redirect()->route('home')->with('success', '10000 справочников и элементов успешно удалены!');
        } else {
            return 'not auth';
        }
    }
    public function imageUpload(Request $request){
        //$path = $request->file('image')->store('uploads', 'public');
        $path = Image::make($request->file('image')->getRealPath())->resize(200, 200)->store('uploads', 'public');
        return view('image-show', ['path' => $path]);
    }
    public function imageShow(){
        $directory = 'public/uploads';
        $images = Storage::disk('local')->allFiles($directory);
        //dd($images);
        return view('image-show', ['images' => $images]);
    }
}
