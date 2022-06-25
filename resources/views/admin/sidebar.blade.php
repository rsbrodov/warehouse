<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Headless CMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::guard('web')->user()->name}}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
    {{--        <div class="form-inline">--}}
    {{--            <div class="input-group" data-widget="sidebar-search">--}}
    {{--                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">--}}
    {{--                <div class="input-group-append">--}}
    {{--                    <button class="btn btn-sidebar">--}}
    {{--                        <i class="fas fa-search fa-fw"></i>--}}
    {{--                    </button>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    <!-- Sidebar Menu -->
        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="fa fa-user-circle fa-lg" aria-hidden="true"></i>
                        <p>Пользователи{{--<i class="right fas fa-angle-left"></i>--}}
                        </p>
                    </a>

                    {{--                    <ul class="nav nav-treeview">--}}
                    {{--                        <li class="nav-item">--}}
                    {{--                            <a href="../../index.html" class="nav-link">--}}
                    {{--                                <i class="far fa-circle nav-icon"></i>--}}
                    {{--                                <p>Dashboard v1</p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        <li class="nav-item">--}}
                    {{--                            <a href="../../index2.html" class="nav-link">--}}
                    {{--                                <i class="far fa-circle nav-icon"></i>--}}
                    {{--                                <p>Dashboard v2</p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    </ul>--}}
                </li>
                <li class="nav-item">
                    <a href="{{ route('dictionary.index') }}" class="nav-link">
                        <i class="fa fa-folder-open fa-lg" aria-hidden="true"></i>
                        <p>Справочники</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('type-content.index') }}" class="nav-link">
                        <i class="fa fa-puzzle-piece fa-lg" aria-hidden="true"></i>
                        <p>Типы контента</p>
                    </a>
                </li>
                <?php
                $type_contents = \App\Models\TypeContent::where(['created_author' => Auth::guard('web')->user()->id, 'status' => 'Published'])->with('created_authors:id,name')->with('updated_authors:id,name')->orderBy('name', 'desc')->get()->unique('id_global');//все уникальные
                $ids = [];
                foreach ($type_contents as $type_content){
                    $ids[] = \App\Models\TypeContent::where('id_global', $type_content->id_global)->orderBy('version_major', 'desc')->orderBy('version_minor', 'desc')->first()->id;
                }
                $type_contents = \App\Models\TypeContent::whereIn('id', $ids)->orderBy('created_at', 'asc')->get();
                $current_page = false;
                foreach ($type_contents as $type_content){
                    if(request()->route('type_content_id') == $type_content->id){
                        $current_page = true;
                    }
                }
                ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="fa fa-coffee fa-lg"></span>
                        <p>Менеджер контента
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview"
                            @if($current_page == true)
                                style="display: block;"
                            @endif
                        >
                        @foreach($type_contents as $type_content)
                            <li class="nav-item">
                                <a href="/element-content/{{$type_content->id}}" class="nav-link
                                    @if(request()->route('type_content_id') == $type_content->id)
                                        active
                                    @endif
                                ml-2"><i class="fa {{$type_content->icon}} fa-lg"></i><p>{{$type_content->name}}</p></a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                {{--                <li class="nav-item">--}}
                {{--                    <a href="#" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-table"></i>--}}
                {{--                        <p>--}}
                {{--                            Отчеты--}}
                {{--                            <i class="fas fa-angle-left right"></i>--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                    <ul class="nav nav-treeview">--}}
                {{--                        <li class="nav-item">--}}
                {{--                            <a href="../tables/simple.html" class="nav-link">--}}
                {{--                                <i class="far fa-circle nav-icon"></i>--}}
                {{--                                <p>Simple Tables</p>--}}
                {{--                            </a>--}}
                {{--                        </li>--}}
                {{--                        <li class="nav-item">--}}
                {{--                            <a href="../tables/data.html" class="nav-link">--}}
                {{--                                <i class="far fa-circle nav-icon"></i>--}}
                {{--                                <p>DataTables</p>--}}
                {{--                            </a>--}}
                {{--                        </li>--}}

                {{--                    </ul>--}}
                {{--                </li>--}}
                <li class="nav-header">НАСТРОЙКИ</li>
                <li class="nav-item">
                    <a href="{{ route('users.roles-create-view') }}" class="nav-link">
                        <i class="fa fa-users fa-lg" aria-hidden="true"></i>
                        <p>Роли/полномочия</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa fa-comment-o fa-lg" aria-hidden="true"></i>
                        <p>
                            Чат
                        </p>
                    </a>
                </li>
{{--                                <li class="nav-item">--}}
{{--                                    <a href="../kanban.html" class="nav-link">--}}
{{--                                        <i class="nav-icon fas fa-columns"></i>--}}
{{--                                        <p>--}}
{{--                                            Kanban Board--}}
{{--                                        </p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a href="#" class="nav-link">--}}
{{--                                        <i class="nav-icon far fa-envelope"></i>--}}
{{--                                        <p>--}}
{{--                                            Mailbox--}}
{{--                                            <i class="fas fa-angle-left right"></i>--}}
{{--                                        </p>--}}
{{--                                    </a>--}}
{{--                                    <ul class="nav nav-treeview">--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="../mailbox/mailbox.html" class="nav-link">--}}
{{--                                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                                <p>Inbox</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="../mailbox/compose.html" class="nav-link">--}}
{{--                                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                                <p>Compose</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="../mailbox/read-mail.html" class="nav-link">--}}
{{--                                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                                <p>Read</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
{{--                                <li class="nav-header">MISCELLANEOUS</li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a href="../../iframe.html" class="nav-link">--}}
{{--                                        <i class="nav-icon fas fa-ellipsis-h"></i>--}}
{{--                                        <p>Tabbed IFrame Plugin</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a href="https://adminlte.io/docs/3.1/" class="nav-link">--}}
{{--                                        <i class="nav-icon fas fa-file"></i>--}}
{{--                                        <p>Documentation</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="nav-header">MULTI LEVEL EXAMPLE</li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a href="#" class="nav-link">--}}
{{--                                        <i class="fas fa-circle nav-icon"></i>--}}
{{--                                        <p>Level 1</p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a href="#" class="nav-link">--}}
{{--                                        <i class="nav-icon fas fa-circle"></i>--}}
{{--                                        <p>--}}
{{--                                            Level 1--}}
{{--                                            <i class="right fas fa-angle-left"></i>--}}
{{--                                        </p>--}}
{{--                                    </a>--}}
{{--                                    <ul class="nav nav-treeview">--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="#" class="nav-link">--}}
{{--                                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                                <p>Level 2</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="#" class="nav-link">--}}
{{--                                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                                <p>--}}
{{--                                                    Level 2--}}
{{--                                                    <i class="right fas fa-angle-left"></i>--}}
{{--                                                </p>--}}
{{--                                            </a>--}}
{{--                                            <ul class="nav nav-treeview">--}}
{{--                                                <li class="nav-item">--}}
{{--                                                    <a href="#" class="nav-link">--}}
{{--                                                        <i class="far fa-dot-circle nav-icon"></i>--}}
{{--                                                        <p>Level 3</p>--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="nav-item">--}}
{{--                                                    <a href="#" class="nav-link">--}}
{{--                                                        <i class="far fa-dot-circle nav-icon"></i>--}}
{{--                                                        <p>Level 3</p>--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="nav-item">--}}
{{--                                                    <a href="#" class="nav-link">--}}
{{--                                                        <i class="far fa-dot-circle nav-icon"></i>--}}
{{--                                                        <p>Level 3</p>--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="#" class="nav-link">--}}
{{--                                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                                <p>Level 2</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
            </ul>


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
