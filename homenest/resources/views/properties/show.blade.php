@extends('layouts.site')

@section('title', $property->title)

@section('content')
@php
    $images = $property->image;
    $phone = $property->user?->profile?->phone;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        @if ($property->coverImage)
            <div id="gallery" class="carousel slide mb-3" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    @foreach ($images as $img)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <img src="{{ Storage::url($img->image_path) }}" class="d-block w-100"
                                 style="max-height:460px;object-fit:cover" alt="{{ $property->title }}">
                        </div>
                    @endforeach
                </div>
                @if ($images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#gallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#gallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        @else
            <img src="{{ asset('images/no-image.svg') }}" class="img-fluid rounded mb-3" alt="Chưa có ảnh">
        @endif

        <h1 class="h4">{{ $property->title }}</h1>
        <p class="text-danger fs-4 fw-bold">
            {{ number_format((float) $property->price, 0, ',', '.') }} đ{{ $property->listing_kind === 'rent' ? '/tháng' : '' }}
        </p>

        <table class="table table-sm w-auto">
            <tr><th class="pe-4">Hình thức</th><td>{{ $property->listing_kind === 'sale' ? 'Bán' : 'Cho thuê' }}</td></tr>
            <tr><th>Loại hình</th><td>{{ $property->type?->name }}</td></tr>
            <tr><th>Diện tích</th><td>{{ $property->area }} m²</td></tr>
            @if ($property->bedrooms)<tr><th>Phòng ngủ</th><td>{{ $property->bedrooms }}</td></tr>@endif
            @if ($property->bathrooms)<tr><th>Phòng tắm</th><td>{{ $property->bathrooms }}</td></tr>@endif
            <tr><th>Địa chỉ</th><td>{{ $property->address }}, {{ $property->district?->name }}, {{ $property->province?->name }}</td></tr>
        </table>

        <h2 class="h6 mt-4">Mô tả</h2>
        <p style="white-space:pre-line">{{ $property->description }}</p>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h6">Liên hệ người đăng</h2>
                <p class="mb-1 fw-semibold">{{ $property->user?->name }}</p>
                @if ($phone)
                    <a href="tel:{{ $phone }}" class="btn btn-success w-100 mt-2">Gọi {{ $phone }}</a>
                @endif
                @if ($property->user?->email)
                    <a href="mailto:{{ $property->user->email }}" class="btn btn-outline-secondary w-100 mt-2">Gửi email</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
