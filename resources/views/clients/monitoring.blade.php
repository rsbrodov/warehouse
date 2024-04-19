<?use \App\Models\User;?>
@extends('admin.main')
@section('content')
<div class="mt-5 ml-2 col-6">
    <table class="table table-bordered table-hover mt-4">
        <tr>
            <th style="white-space: nowrap">Наименование</th>
            <th>Хост</th>
            <th>Статус</th>
            <th>Ответ сервера</th>
        </tr>
        @foreach($clients as $client)
            <?php
            $ch = curl_init($client->host.'type-content/icons');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $html = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            ?>
        <tr>
            <td>{{$client->name}}</td>
            <td>{{$client->host}}</td>
            <td>Активирован</td>
            <td class="<?php echo $httpcode == 200 ? 'text-success' : 'text-danger'?>">{{$httpcode}}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
