<?php
$typeContents = \App\Models\TypeContent::where(['status' => 'Published'])->orderBy('name', 'desc')->get()->unique('id_global');//все уникальные
$ids = [];
foreach ($typeContents as $typeContent) {
    $ids[] = \App\Models\TypeContent::where('id_global', $typeContent->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first()->id;
}
$typeContents = \App\Models\TypeContent::whereIn('id', $ids)->orderBy('update_date', 'desc')->get();
$currentPage = false;
foreach ($typeContents as $typeContent) {
    if (request()->route('type_content_id') == $typeContent->id) {
        $currentPage = true;
    }
}
?>
    <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
        <img src="http://rider-cms.ru/images/logo.jpg" alt="RIDER ADMIN"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">RIDER ADMIN</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('/storage/' . Auth::guard('web')->user()->photo)}}" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="{{route('users.profile')}}" class="d-block">{{Auth::guard('web')->user()->name}}</a>
            </div>
        </div>
        <nav>

            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
{{--                <li class="nav-item @if(Route::current()->getName() == 'dictionary.index' || Route::current()->getName() == 'type-content.index') menu-is-opening menu-open @endif">--}}
{{--                    <a href="#" class="nav-link">--}}
{{--                        <span class="fa fa-list-alt fa-lg"></span>--}}
{{--                        <p>Контентная модель--}}
{{--                            <i class="fa fa-angle-left right"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview"--}}
{{--                        @if(Route::current()->getName() == 'dictionary.index' || Route::current()->getName() == 'type-content.index')--}}
{{--                            style="display: block;"--}}
{{--                        @endif--}}
{{--                    >--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('dictionary.index') }}"--}}
{{--                               class="nav-link ml-2 @if(Route::current()->getName() == 'dictionary.index') active @endif">--}}
{{--                                --}}{{-- <i class="fa fa-folder-open fa-lg" aria-hidden="true"></i> --}}
{{--                                <p>Справочники</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('type-content.index') }}"--}}
{{--                               class="nav-link ml-2 @if(Route::current()->getName() == 'type-content.index') active @endif">--}}
{{--                                --}}{{-- <i class="fa fa-puzzle-piece fa-lg" aria-hidden="true"></i> --}}
{{--                                <p>Типы контента</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li class="nav-item border-down @if($currentPage == true) menu-is-opening menu-open @endif">--}}
{{--                    <a href="#" class="nav-link">--}}
{{--                        <span class="fa fa-id-card-o fa-lg"></span>--}}
{{--                        <p>Менеджер контента--}}
{{--                            <i class="fa fa-angle-left right"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview element-content-sidebar-pull"--}}
{{--                        @if($currentPage == true)--}}
{{--                            style="display: block;"--}}
{{--                        @endif--}}
{{--                    >--}}


{{--                        @if(!empty($typeContents))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="/element-content" class="nav-link--}}
{{--                                    @if(Route::current()->getName() == 'element-content.indexAll')--}}
{{--                                        active--}}
{{--                                    @endif--}}
{{--                                ml-2"><p>Весь контент</p></a>--}}
{{--                            </li>--}}
{{--                        @endif--}}

{{--                        @foreach($typeContents as $typeContent)--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="/element-content/{{$typeContent->id}}" class="nav-link--}}
{{--                                    @if(request()->route('type_content_id') == $typeContent->id)--}}
{{--                                        active--}}
{{--                                    @endif--}}
{{--                                ml-2"><i class="fa {{$typeContent->icon}} fa-lg"></i>--}}
{{--                                    <p>{{$typeContent->name}}</p></a>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}

{{--                    </ul>--}}
{{--                </li>--}}

                <li class="nav-item mt-3">
                    <a href="/type-content/index" class="nav-link">
                        <i class="fa fa-address-book-o fa-lg" aria-hidden="true"></i>
                        <p>Клиенты{{--<i class="right fas fa-angle-left"></i>--}}
                        </p>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a href="/dictionary/" class="nav-link">
                        <i class="fa fa-money fa-lg" aria-hidden="true"></i>
                        <p>Тарифы</p>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a href="/clients/monitoring" class="nav-link">
                        <i class="fa fa-desktop fa-lg" aria-hidden="true"></i>
                        <p>Мониторинг</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<style>
    /*.menu-is-opening{
        background-color: #1f1f1f;
    }*/
    .border-down {
        border-bottom: 1px solid #4f5962;
    "
    }
</style>
