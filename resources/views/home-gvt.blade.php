@extends('admin.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @php
                $gridData = [
                'dataProvider' => $dataProvider,
                'title' => 'Таблица Users',
                'useFilters' => false,
                'columnFields' => [
                    [
                        'label' => 'ID', // Column label.
                        'attribute' => 'id', // Attribute, by which the row column data will be taken from a model.
                    ],
                    [
                        'label' => 'Создатель', // Column label.
                        'attribute' => 'parent_id', // Attribute, by which the row column data will be taken from a model.
                    ],
                    'status',
                     [
                        'label' => 'Имя', // Column label.
                        'attribute' => 'name', // Attribute, by which the row column data will be taken from a model.
                    ],
                    'email',
                    'created_at',
                    'updated_at'
                 ]
                ];
            @endphp
            @gridView($gridData)
        </div>
    </div>
@endsection
