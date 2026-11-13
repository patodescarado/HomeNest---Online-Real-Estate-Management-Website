@extends('layouts.admin')

@section('title', 'Dashboard quản trị')
@section('page-heading', 'Dashboard')

@section('content')
<div class="admin-page-heading">
    <div>
        <p class="eyebrow mb-1">Tổng quan hệ thống</p>
        <h1 class="section-title">Dashboard</h1>
    </div>
    <a href="{{ route('admin.properties.pending') }}" class="btn">Duyệt tin đang chờ</a>
</div>

<div class="stat-grid admin-stat-grid">
    <article class="card stat-card">
        <span class="muted">Tin chờ duyệt</span>
        <strong class="stat-value">{{ number_format($pendingCount) }}</strong>
        <span class="stat-caption"><i class="bi bi-hourglass-split"></i> Cần được kiểm tra</span>
    </article>
    <article class="card stat-card">
        <span class="muted">Tin đang hiển thị</span>
        <strong class="stat-value">{{ number_format($activeCount) }}</strong>
        <span class="stat-caption"><i class="bi bi-check-circle"></i> Đã được duyệt</span>
    </article>
    <article class="card stat-card">
        <span class="muted">Người dùng</span>
        <strong class="stat-value">{{ number_format($userCount) }}</strong>
        <span class="stat-caption"><i class="bi bi-people"></i> Tài khoản toàn hệ thống</span>
    </article>
</div>

@php
    $maxStatus = max(1, ...array_values($statusCounts));
    $maxRegion = max(1, (int) $regions->max('total'));
    $statusLabels = [
        'approved' => ['Đã duyệt', '#0f766e'],
        'pending' => ['Chờ duyệt', '#e76f51'],
        'rejected' => ['Từ chối', '#94a3b8'],
        'expired' => ['Hết hạn', '#cbd5e1'],
    ];
@endphp

<div class="admin-chart-grid">
    <section class="card chart-card">
        <h2 class="h5 mb-4">Tin theo trạng thái</h2>
        @foreach ($statusLabels as $key => [$label, $color])
            <div class="bar-row">
                <span class="label">{{ $label }}</span>
                <div class="bar-track"><div class="bar" style="width: {{ $statusCounts[$key] / $maxStatus * 100 }}%;background:{{ $color }}"></div></div>
                <span class="muted">{{ number_format($statusCounts[$key]) }}</span>
            </div>
        @endforeach
    </section>
    <section class="card chart-card">
        <h2 class="h5 mb-4">Tin theo khu vực</h2>
        @forelse ($regions as $region)
            <div class="bar-row">
                <span class="label">{{ $region->name }}</span>
                <div class="bar-track"><div class="bar" style="width: {{ $region->total / $maxRegion * 100 }}%"></div></div>
                <span class="muted">{{ number_format($region->total) }}</span>
            </div>
        @empty
            <p class="muted mb-0">Chưa có dữ liệu khu vực.</p>
        @endforelse
    </section>
</div>
@endsection
