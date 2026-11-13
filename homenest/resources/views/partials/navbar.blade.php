
@php
    $types = [
        'can-ho-chung-cu' => 'Căn hộ chung cư',
        'nha-rieng'       => 'Nhà riêng',
        'nha-mat-pho'     => 'Nhà mặt phố',
        'dat'             => 'Đất',
        'biet-thu'             => 'Biệt thự',
        'bds-khac'        => 'Bất động sản khác',
    ];
@endphp

<nav class="navbar navbar-expand-lg homenest-navbar sticky-top">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="bi bi-house-heart-fill me-1"></i>
            HomeNest
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">

            <ul class="navbar-nav mx-auto gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                       href="{{ url('/') }}">
                        Trang chủ
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle
                        {{ request('purpose') === 'sale' ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        Mua bán
                    </a>

                    <ul class="dropdown-menu">
                        @foreach ($types as $slug => $label)
                            <li>
                                <a class="dropdown-item"
                                   href="{{ url('/bat-dong-san?purpose=sale&type=' . $slug) }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle
                        {{ request('purpose') === 'rent' ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        Cho thuê
                    </a>

                    <ul class="dropdown-menu">
                        @foreach ($types as $slug => $label)
                            <li>
                                <a class="dropdown-item"
                                   href="{{ url('/bat-dong-san?purpose=rent&type=' . $slug) }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('yeu-thich*') ? 'active' : '' }}"
                       href="{{ url('/yeu-thich') }}">
                        <i class="bi bi-heart me-1"></i>
                        Yêu thích
                    </a>
                </li>

            </ul>

            <ul class="navbar-nav align-items-lg-center gap-2">

                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-login">
                            Đăng nhập
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('register') }}"
                           class="btn btn-primary-custom">
                            Đăng ký
                        </a>
                    </li>

                @else

                    <li class="nav-item">
                        <a href="{{ url('/tin-dang') }}"
                           class="btn btn-primary-custom">
                            <i class="bi bi-plus-lg me-1"></i>
                            Đăng tin
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            @if (auth()->user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item" href="{{ url('/admin') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Quản trị
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                                                    <li>
                                    <a class="dropdown-item"
                                    href="{{ route('profile.show') }}">
                                        <i class="bi bi-person me-2"></i>
                                        Thông tin cá nhân
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>


                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </li>

                @endguest

            </ul>

        </div>
    </div>
</nav>
