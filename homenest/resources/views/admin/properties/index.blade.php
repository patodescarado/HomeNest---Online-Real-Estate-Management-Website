@extends('layouts.admin')

@section('title', $pendingOnly ? 'Tin chờ duyệt' : 'Tất cả tin')
@section('page-heading', $pendingOnly ? 'Tin chờ duyệt' : 'Tất cả tin')

@section('content')
<div class="admin-page-heading">
    <div>
        <p class="eyebrow mb-1">Quản lý nội dung</p>
        <h1 class="section-title">{{ $pendingOnly ? 'Tin chờ duyệt' : 'Tất cả tin đăng' }}</h1>
    </div>
    @if ($pendingOnly)
        <span class="count-pill">{{ number_format($properties->total()) }} tin cần xử lý</span>
    @endif
</div>

<div class="card table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Tin đăng</th><th>Người đăng</th><th>Ngày gửi</th><th>Thao tác</th></tr>
            </thead>
            <tbody>
            @forelse ($properties as $property)
                <tr>
                    <td>
                        <a class="table-title" href="{{ route('properties.show', $property) }}">{{ $property->title }}</a>
                        <div class="muted small">{{ $property->district?->name }}{{ $property->district?->province ? ', '.$property->district->province->name : '' }}</div>
                    </td>
                    <td>{{ $property->user?->name ?? 'Không xác định' }}</td>
                    <td>{{ $property->created_at?->format('d/m/Y') }}</td>
                    
                    <td>
                        @if ($property->status === 'pending')
                            <div class="admin-actions">
                                <form action="{{ route('admin.properties.approve', $property) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm" type="submit"><i class="bi bi-check-lg me-1"></i>Duyệt</button>
                                </form>
                                <form action="{{ route('admin.properties.reject', $property) }}" method="POST" class="reject-form">
                                    @csrf
                                    <label class="visually-hidden" for="reason-{{ $property->property_id }}">Lý do từ chối tin {{ $property->title }}</label>
                                    <input class="input reject-reason" id="reason-{{ $property->property_id }}"
                                           name="reason" minlength="10" maxlength="1000" required
                                           value="{{ old('reason') }}" placeholder="Lý do từ chối (ít nhất 10 ký tự)">
                                    <button class="btn btn-sm btn-danger" type="submit">Từ chối</button>
                                </form>
                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa tin đăng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="bi bi-trash me-1"></i>Xóa
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="admin-actions">
                                <a class="btn btn-sm btn-outline" href="{{ route('properties.show', $property) }}">Xem tin</a>
                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa tin đăng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="bi bi-trash me-1"></i>Xóa
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty"><i class="bi bi-check2-circle"></i><p>{{ $pendingOnly ? 'Không còn tin nào chờ duyệt.' : 'Chưa có tin đăng.' }}</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $properties->links('pagination::bootstrap-5') }}
@endsection
