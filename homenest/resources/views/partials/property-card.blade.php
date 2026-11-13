@php
    
    $cover = $property->coverImage
        ? Storage::url($property->coverImage->image_path)
        : asset('images/no-image.svg');
@endphp

@auth
  @php($saved = in_array($property->id, $favoriteIds ?? []))
  <button type="button" data-url="{{ route('favorites.toggle', $property) }}"
          class="btn btn-sm js-fav {{ $saved ? 'btn-danger' : 'btn-outline-danger' }}">
    {{ $saved ? 'Đã lưu' : 'Lưu' }}
  </button>
@else
  <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger">Lưu</a>
@endauth

<div class="col">
    <div class="card h-100 shadow-sm">
        <a href="{{ route('properties.show', $property) }}">
            <img src="{{ $cover }}" class="card-img-top" alt="{{ $property->title }}"
                 style="height:200px;object-fit:cover">
        </a>
        <div class="card-body">
            <span class="badge bg-{{ $property->listing_kind === 'sale' ? 'primary' : 'success' }} mb-2">
                {{ $property->listing_kind === 'sale' ? 'Bán' : 'Cho thuê' }}
            </span>
            <h5 class="card-title fs-6">
                <a href="{{ route('properties.show', $property) }}" class="text-decoration-none text-dark">
                    {{ \Illuminate\Support\Str::limit($property->title, 70) }}
                </a>
            </h5>
            <p class="text-danger fw-bold mb-1">
                {{ number_format((float) $property->price, 0, ',', '.') }} đ{{ $property->listing_kind === 'rent' ? '/tháng' : '' }}
            </p>
            <p class="small text-muted mb-0">
                {{ $property->area }} m² · {{ $property->district?->name }}, {{ $property->province?->name }}
            </p>
        </div>
    </div>
</div>
