@extends('layouts.app')
@section('content')
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @if (Session::has('credentials'))
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endif
            </div>
        @endif

        <div class="content">
            <div class="title m-b-md">
                Test GSI
            </div>
        </div>
    </div>
@endsection
