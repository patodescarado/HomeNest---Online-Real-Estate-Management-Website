
@extends('layouts.site')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Tin đăng yêu thích</h2>
            <p class="text-muted mb-0">
                Danh sách bất động sản bạn đã lưu.
            </p>
        </div>
    </div>

    @if($properties->count() > 0)

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            @foreach($properties as $property)
                @include('partials.property-card', [
                    'property' => $property,
                    'favoriteIds' => $favoriteIds
                ])
            @endforeach

        </div>

        <div class="mt-4">
            {{ $properties->links() }}
        </div>

    @else

        <div class="text-center py-5">
            <h5 class="text-muted">
                Bạn chưa lưu tin đăng nào.
            </h5>

            <a href="{{ route('home') }}"
               class="btn btn-primary mt-3">
                Khám phá tin đăng
            </a>
        </div>

    @endif

</div>
@endsection
