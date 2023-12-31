<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админка</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">

    <link rel="stylesheet" href="{{ asset("css/datepicker.min.css") }}">

{{--    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"--}}
{{--          integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">--}}

{{--    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">--}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
{{--    <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">--}}

<!-- SELECT2 -->
    <!-- <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.css')}}"> -->
</head>
<body class="hold-transition sidebar-mini">
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

<!-- SELECT2 -->
<!-- <script src="{{ asset('plugins/select2/js/select2.js') }}"></script> -->

<script src="{{ asset("js/datepicker.min.js") }}"></script>



<div class="wrapper">

@include('admin.navbar')
@include('admin.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"  style="background-color:#F5F0F0">
    {{--@include('admin.content')--}}
    @include('flash-message')
    {{--подключаем файл с уведомлениями--}}
    {{--@include('validate-message')--}}

        <!-- Main content -->
        <div class="content pt-3" style="background-color:#F5F0F0">
            <div class="container-fluid bg-white px-5" style="min-height: 91vh; width:85vw; box-shadow: 0px 4px 4px 4px rgba(0, 0, 0, 0.25);">
                <div class="row">
                    @yield('content')
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->


    </div>
    @include('admin.mymodal')
        <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->

<script type="text/javascript">

</script>
</body>

</html>
