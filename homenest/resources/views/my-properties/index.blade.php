@extends('layouts.site')

@section('title', 'Tin đăng của tôi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Tin đăng của tôi</h1>
    <a href="{{ route('my-properties.create') }}" class="btn btn-primary">+ Đăng tin mới</a>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr><th>Tiêu đề</th><th>Hình thức</th><th>Giá</th><th>Trạng thái</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($properties as $property)
            <tr>
                <td><a href="{{ route('properties.show', $property) }}">{{ \Illuminate\Support\Str::limit($property->title, 60) }}</a></td>
                <td>{{ $property->listing_kind === 'sale' ? 'Bán' : 'Cho thuê' }}</td>
                <td>{{ number_format((float) $property->price, 0, ',', '.') }} đ</td>
                <td>@include('partials.status-badge', ['status' => $property->status])</td>
                <td class="text-end">
                    <a href="{{ route('my-properties.edit', $property) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                    <form action="{{ route('my-properties.destroy', $property) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Xóa tin này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-muted">Bạn chưa đăng tin nào.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $properties->links('pagination::bootstrap-5') }}
@endsection
