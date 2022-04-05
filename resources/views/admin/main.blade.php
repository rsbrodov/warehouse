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
    <link rel="stylesheet" href="{{ asset("css/dragula.min.css") }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
{{--    <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<!-- SELECT2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- SELECT2 -->
<script src="{{ asset('plugins/select2/js/select2.js') }}"></script>

<script src="{{ asset("js/datepicker.min.js") }}"></script>
<script src="{{ asset("js/dragula.min.js") }}"></script>
<script src="{{ asset("js/popper.min.js") }}"></script>
<script src="{{ asset("js/bootstrap.min.js") }}"></script>
<script src="{{ asset("js/jquery-3.6.0.min.js") }}"></script>
<div class="wrapper">

@include('admin.navbar')
@include('admin.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    {{--                @include('admin.content')--}}
    @include('flash-message')
    {{--подключаем файл с уведомлениями--}}
{{--    @include('validate-message')--}}

    <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        {{--                    <h1 class="m-0">Starter Page</h1>--}}
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Starter Page</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
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
    @include('admin.control-sidebar')
    @include('admin.footer')
</div>
<!--
<script src="https://getbootstrap.com/docs/5.0/examples/checkout/form-validation.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
-->

<!-- ./wrapper -->
<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->

<script type="text/javascript">

    /*new AirDatepicker('.air-datepicker', {
        dateFormat(date) {
            return date.toLocaleString('ja', {
                year: 'numeric',
                day: '2-digit',
                month: 'long'
            });
        }
    })*/
</script>
</body>

</html>
