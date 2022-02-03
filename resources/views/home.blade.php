@extends('admin.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Dashboard') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    @if (session('status'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ session('status') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    --}}



{{--                    @can('read-component')--}}
{{--                        <a class="btn btn-success" href="{{ route('tech.create') }}"> Create New Role</a>--}}
{{--                    @endcan--}}
{{--                    @auth--}}
{{--                           <p class="text-center">123 </p>--}}
{{--                    @endauth--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
@endsection
