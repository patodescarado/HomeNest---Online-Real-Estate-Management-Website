@extends('layouts.site')

@section('content')
<div class="container py-5">
    <h3>Thông tin cá nhân</h3>

    <div class="card p-4 mt-3">
        
        <p>Họ tên: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
        <p>Số điện thoại: {{ $user->profile?->phone ?? 'Chưa cập nhật' }}</p>

    </div>
</div>
@endsection