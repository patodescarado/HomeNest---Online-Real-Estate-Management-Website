@extends('layouts.site')

@section('title', 'Đăng tin mới')

@section('content')
<h1 class="h4 mb-3">Đăng tin mới</h1>
@include('my-properties._form', [
    'action' => route('my-properties.store'),
    'method' => 'POST',
    'property' => null,
])
@endsection
