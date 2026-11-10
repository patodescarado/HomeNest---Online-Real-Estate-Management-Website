@extends('layouts.site')

@section('title', 'Sửa tin đăng')

@section('content')
<h1 class="h4 mb-3">Sửa tin đăng</h1>
@include('my-properties._form', [
    'action' => route('my-properties.update', $property),
    'method' => 'PUT',
    'property' => $property,
])
@endsection
