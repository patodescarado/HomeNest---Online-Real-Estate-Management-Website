
@php
    $adminMenus = [
        [
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => url('/admin'),
            'active' => request()->is('admin'),
        ],
        [
            'label' => 'Tin chờ duyệt',
            'icon' => 'bi-hourglass-split',
            'url' => url('/admin/properties/pending'),
            'active' => request()->is('admin/properties/pending*'),
        ],
        [
            'label' => 'Tất cả tin',
            'icon' => 'bi-houses',
            'url' => url('/admin/properties'),
            'active' => request()->is('admin/properties')
                        || request()->is('admin/properties/*')
                        && !request()->is('admin/properties/pending*'),
        ],
        [
            'label' => 'Người dùng',
            'icon' => 'bi-people',
            'url' => url('/admin/users'),
            'active' => request()->is('admin/users*'),
        ],
        [
            'label' => 'Danh mục',
            'icon' => 'bi-grid',
            'url' => url('/admin/categories'),
            'active' => request()->is('admin/categories*'),
        ],
        [
            'label' => 'Báo cáo vi phạm',
            'icon' => 'bi-flag',
            'url' => url('/admin/reports'),
            'active' => request()->is('admin/reports*'),
        ],
    ];
@endphp

{{-- Desktop sidebar --}}
<aside class="admin-sidebar d-none d-lg-flex flex-column p-3">

    <a href="{{ url('/admin') }}" class="admin-logo text-decoration-none">
        <i class="bi bi-house-heart-fill me-2"></i>
        HomeNest
        <span>ADMIN</span>
    </a>

    <div class="admin-menu-label mt-4 mb-2">MENU CHÍNH</div>

    <ul class="nav flex-column gap-2">
        @foreach ($adminMenus as $menu)
            <li class="nav-item">
                <a href="{{ $menu['url'] }}"
                   class="nav-link {{ $menu['active'] ? 'active' : '' }}">
                    <i class="bi {{ $menu['icon'] }} me-3"></i>
                    {{ $menu['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-auto pt-4">
        <hr class="border-secondary">

        <a href="{{ url('/') }}" class="nav-link admin-back-link">
            <i class="bi bi-arrow-left me-2"></i>
            Về website
        </a>
    </div>
</aside>

{{-- Mobile sidebar --}}
<div class="offcanvas offcanvas-start admin-mobile-sidebar"
     tabindex="-1" id="mobileAdminSidebar">

    <div class="offcanvas-header">
        <a href="{{ url('/admin') }}" class="admin-logo text-decoration-none">
            HomeNest <span>ADMIN</span>
        </a>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <div class="admin-menu-label mb-2">MENU CHÍNH</div>

        <ul class="nav flex-column gap-2">
            @foreach ($adminMenus as $menu)
                <li class="nav-item">
                    <a href="{{ $menu['url'] }}"
                       class="nav-link {{ $menu['active'] ? 'active' : '' }}">
                        <i class="bi {{ $menu['icon'] }} me-3"></i>
                        {{ $menu['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <hr class="border-secondary">

        <a href="{{ url('/') }}" class="nav-link admin-back-link">
            <i class="bi bi-arrow-left me-2"></i>
            Về website
        </a>
    </div>
</div>
