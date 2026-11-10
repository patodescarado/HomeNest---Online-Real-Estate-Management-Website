@extends('layouts.site')

@section('title', 'HomeNest - Trang chủ')

@section('content')
<div class="bg-light rounded-3 p-4 mb-4">
    <h1 class="h3 mb-3">Tìm bất động sản phù hợp với bạn</h1>
    <form action="{{ route('properties.index') }}" method="GET" class="row g-2">
        <div class="col-md-3">
            <select name="kind" class="form-select">
                <option value="sale">Mua bán</option>
                <option value="rent">Cho thuê</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="province_id" class="form-select">
                <option value="">Tất cả tỉnh/thành</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Từ khóa: tên dự án, đường, quận...">
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary">Tìm kiếm</button>
        </div>
    </form>
</div>

<h2 class="h5 mb-3">Tin mới nhất</h2>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @forelse ($latest as $property)
        @include('partials.property-card', ['property' => $property])
    @empty
        <p class="text-muted">Chưa có tin đăng nào.</p>
    @endforelse
</div>
@endsection
