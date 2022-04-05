@extends('admin.main')
@section('content')
    <!--    <style>
        .wrapper {
            display: table;
        }
        .container {
            display: table-cell;
            background-color: rgba(255, 255, 255, 0.2);
            width: 50%;
        }
        .container > div,
        .gu-mirror {
            margin: 10px;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.2);
            transition: opacity 0.4s ease-in-out;
        }
        .container > div {
            cursor: move;
            cursor: grab;
            cursor: -moz-grab;
            cursor: -webkit-grab;
        }
        .container .ex-moved {
            background-color: #e74c3c;
        }
        .container.ex-over {
            background-color: rgba(255, 255, 255, 0.3);
        }
        .container.ex-over {
            background-color: rgba(255, 255, 255, 0.3);
        }
    </style>-->
    <!-- {{print_r($id)}}
    {{print_r('<br><br>')}}
    {{print_r($typeContent)}}
    {{print_r('<br><br>')}}
    {{print_r($body)}}-->
    <style>
        .boards_items{
            min-height: 80px;
            background: rgba(0,0,0, .5);
            padding: 10px;
        }
        .title{
            padding: 5px;
        }
        .title:focus{
            outline: 1px solid red;
        }
    </style>
    <div class="container-fluid">
        <a href="{{route('type-content.get-all-version', $typeContent->id_global)}}"
           class="btn btn-danger mt-2 mb-3">Венуться к списку</a>
        <div class="p-2">
            <h5 class="text-center">Просмотр версии шаблона</h5>
            <hr>
            <div class="row mt-0 mb-0 ml-0 mr-0">
                <!-- эта зона где будут наши отрисованы контент-->
                <div  id='sortable' class="col-sm-12 col-md-6 col-lg-8 col-xl-8 border-right boards sortable">
                    <div class="border-2 rounded boards_items mb-3">
                        <!--contenteditable="true" изменения названия-->
                        <div class="text-center m-2">
                            <span contenteditable="true" class="title rounded">Название области</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <div class="m-2 start-cart">
                        <div class="add_btn border-success border p-2 rounded btn-block">
                            <span> + </span> Добавить поле
                        </div>
                        <div class="crt1">
                            <div id="cart1" class="list_item p-2 border bg-gradient-cyan rounded btn-block" draggable="true">
                                Стартовая карта 1
                            </div>
                        </div>
                        <div class="crt2">
                            <div id="cart2" class="list_item p-2 border bg-gradient-cyan rounded btn-block" draggable="true">
                                Стартовая карта 2
                            </div>
                        </div>
                        <a href="#" data-toggle="modal" data-target="#modalefefef" class="nav-link">modal</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <br>
        <br>
        <br>
        <div class="container">
            <label for="wdwd" class="text-center"><b>Напишите Ваш текст который хотите прочесть:</b></label>
            <textarea name="wd" id="wdwd" cols="30" rows="2" class="form-control"></textarea>
            <button id="speakBtn" class="btn form-control btn-secondary mt-2">Озвучить текст</button>
        </div>
        <br>
        <br>
        <br>
        <div class="p-2">
            <a href="{{route('type-content.get-all-version', $typeContent->id_global)}}"
               class="btn btn-danger mt-2 mb-3">Венуться к списку</a>
            <h5 class="text-center">Просмотр версии шаблона 2</h5>
            <hr>
            <div class="row mt-0 mb-0 ml-0 mr-0">
                <div id='sortable' class="col-sm-12 col-md-6 col-lg-8 col-xl-8 border-right sortable">
                    @if($body)
                        {{--{{print_r($body)}}
                        {{exit()}}--}}
                        @foreach($body as $key =>$data)
                            @switch($data->type)
                                @case('input')
                                <div class="el-1 m-1 p-2 border border-secondary rounded">
                                    Тип поля: {{$data->type}}, название: {{$data->name}}
                                </div>
                                @break
                                @case('textarea')
                                <div class="el-1 m-1 p-2 border border-secondary rounded">
                                    Тип поля: {{$data->type}}, название: {{$data->name}}
                                </div>
                                @break
                                @case('date')
                                <div class="el-1 m-1 p-2 border border-secondary rounded">
                                    Тип поля: {{$data->type}}, название: {{$data->name}}
                                </div>
                                @break
                                @default
                                <div class="el-1 m-1 p-2 border border-secondary rounded"></div>
                                @break
                            @endswitch

                        @endforeach
                    @endif
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <div class="m-2">
                        <!--                    <a href="#" data-toggle="modal" data-target="#modalefefef" class="nav-link">modal</a>-->
                        <div class="m-2 mb-2">
                            <a href="#" class="el-1 p-2 border bg-gradient-cyan rounded btn-block">
                                Востановить версию из архива
                            </a>
                        </div>
                        <div class="m-2 mb-2">
                            <a href="{{route('type-content.create-elemen', [$id, 'input'])}}"
                               class="el-1 p-2 border border-success rounded btn-block">
                                Инпут
                            </a>
                        </div>
                        <div class="m-2 mb-2">
                            <a href="{{route('type-content.create-elemen', [$id, 'textarea'])}}"
                               class="el-1 p-2 border border-success rounded btn-block">
                                textarea
                            </a>
                        </div>
                        <div class="m-2 mb-2">
                            <a href="{{route('type-content.create-elemen', [$id, 'date'])}}"
                               class="el-1 p-2 border border-success rounded btn-block">
                                Дата / время
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="container border-2 rounded">
            <h5 class="text-center">Страница:</h5>

            @if($body)
                @foreach($body as $key =>$data)
                    @switch($data->type)
                        @case('input')
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">{{$data->name}}</label>
                            <div class="col-sm-10">
                                <input type="{{$data->type}}" class="form-control" id="staticEmail">
                            </div>
                        </div>
                        @break
                        @case('textarea')
                        <div class="form-group row">
                            <label for="are" class="col-sm-2 col-form-label">{{$data->name}}</label>
                            <div class="col-sm-10">
                                <textarea rows="{{$data->row}}" class="form-control" id="are"></textarea>
                            </div>
                        </div>
                        @break
                        @case('date')

                        <div class="form-group row">
                            <label for="active_after" class="col-sm-2 col-form-label">{{$data->name}}</label>
                            <div class="col-sm-10">
                                <input type="text" name="active_after" id="active_after"
                                       class="form-control datepicker-here">
                            </div>
                        </div>
                        @break
                        @default
                        <div class="el-1 m-1 p-2 border border-secondary rounded"></div>
                        @break
                    @endswitch

                @endforeach
            @endif
        </div>
        <br>
        <br>
        <br>
    </div>
    <script>
        const textEl = document.getElementById('wdwd');
        const speakEl = document.getElementById('speakBtn');
        // перехватим клик по кнопке
        speakEl.addEventListener('click', speakText);
        function speakText() {
            // остановим все, что уже синтезируется в речь
            window.speechSynthesis.cancel();
            // прочитать текст
            const text = textEl.value;
            const utterance = new SpeechSynthesisUtterance(text);
            window.speechSynthesis.speak(utterance);
        }

        const button = document.querySelector('.add_btn')
        console.log(button)
        let idItemsEl = 1
        let idBoardEl = 1
        function dragAndDrop(){
            const listItems = document.querySelectorAll('.list_item'),
                lists2 = document.querySelectorAll('.boards_items')
            //перебераем массивы
            for(let i = 0; i < listItems.length; i++){
                const item = listItems[i]
                //начали перемещать элемент
                item.addEventListener('dragstart', ()=>{
                    dragItem = item.cloneNode();
                    dragItem.id = idItemsEl;
                    dragItem.innerText = item.innerText;
                    idItemsEl++
                    //$('#modalefefef').modal('show');
                    //console.log(dragItem)
                    //console.log(item.parentElement)
                    //удаление элемента
                    dragItem.addEventListener('dblclick', (e)=>{
                        //console.log(e.path[0].id)
                        document.getElementById(e.path[0].id).remove()
                    })
                })
                //Надо сделать так при перетаскивании не удалять элемент сразу а только после того как он dragend совершил
                //закончили перемещать элемент
                item.addEventListener('dragend', ()=>{
                    item.parentElement.append(item)
                })


                for(let j = 0; j < lists2.length; j++){
                    const list555 = lists2[j]
                    //перетакивание на новую доску
                    list555.addEventListener('dragover', e =>{
                        e.preventDefault()
                    })
                    list555.addEventListener('dragenter', function (e){
                        e.preventDefault() //убираем стандартные работы браузера
                        //this.style.backgroundColor = 'rgba(0,0,0,.3)'
                    })
                    list555.addEventListener('dragleave', function (e){
                        //this.style.backgroundColor = 'rgba(0,0,0,0)'
                    })
                    list555.addEventListener('drop', function (e){
                        this.append(dragItem)
                    })
                }
            }
        }
        dragAndDrop()
        function addBoard(){
            const boards = document.querySelector('.boards')
            const board = document.createElement('div')
            board.innerHTML = `
                <div class="border-2 rounded boards_items mb-3">
                    <div class="text-center m-2">
                        <span contenteditable="true" class="title rounded">Название области</span>
                    </div>
                </div>
            `
            boards.append(board)
            dragAndDrop()
            changeTitle()
        }
        button.addEventListener('click', addBoard)
        function changeTitle(){
            const titles = document.querySelectorAll('.title')
            titles.forEach(title =>{
                title.addEventListener('click', e => {
                    console.log(title)
                    old = e.target.textContent
                    e.target.textContent = ''
                })
            })
        }
        changeTitle()

        var sortable = document.querySelector('.sortable');
        console.log(sortable)

        function dragulaF(sortable){
            dragula([sortable]);
        }

    </script>
@endsection