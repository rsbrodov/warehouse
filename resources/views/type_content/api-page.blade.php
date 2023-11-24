@extends('admin.main')
@section('content')
    <div>
        <h4><b>Шаблон</b></h4>
        <p><b>API:</b> <i>{{request()->getHttpHost()}}/api/template/GetTypeContentId/{{$typeContent['id']}}</i>
            <a href="/api/template/GetTypeContentId/{{$typeContent['id']}}" class="ml-3 mt-2 btn btn-sm btn-outline-secondary form-control pb-1" style="width:100px; max-height:25px; line-height:1;margin-bottom: 5px;" target="_blank">Перейти</a>
        </p>
        <p><b>Объект:</b></p>
        {<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"id": {{$typeContent['id']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"idGlobal": {{$typeContent['idGlobal']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp; "name": {{$typeContent['name']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"apiUrl": {{$typeContent['apiUrl']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"icon": {{$typeContent['icon']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"owner": {{$typeContent['owner']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"basedType": {{$typeContent['basedType']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"activeFrom": {{$typeContent['activeFrom']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"activeAfter": {{$typeContent['activeAfter']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"status": {{$typeContent['status']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"versionMajor": {{$typeContent['versionMajor']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"versionMinor": {{$typeContent['versionMinor']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"createdAuthors": {{$typeContent['createdAuthors']}},<br>
        &nbsp;&nbsp;&nbsp;&nbsp;"updatedAuthors": {{$typeContent['updatedAuthors']}}<br>
        }

        <h4><b>Получение списка всего контента по данному шаблону</b></h4>
        <p><b>API:</b> <i>{{request()->getHttpHost()}}/api/type-content/find-all-element-body/{{$typeContent['id']}}</i>
            <a href="/api/type-content/find-all-element-body/{{$typeContent['id']}}" class="ml-3 mt-2 btn btn-sm btn-outline-secondary form-control pb-1" style="width:100px; max-height:25px; line-height:1;margin-bottom: 5px;" target="_blank">Перейти</a>
        </p>
        <p><b>Объект:</b></p>
        <?$json_pretty_result = json_encode($result, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $json_pretty_result = "<pre>" . $json_pretty_result . "<pre/>";?>
        <?print_r($json_pretty_result) ?>

    </div>
@endsection
