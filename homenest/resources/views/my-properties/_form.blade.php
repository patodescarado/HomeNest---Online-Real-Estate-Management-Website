@php
    $p = $property ?? null;
    $kindValue = old('listing_kind', $p?->listing_kind ?? 'sale');
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST') @method($method) @endif

    {{-- Hình thức --}}
    <div class="mb-3">
        <label class="form-label d-block">Hình thức</label>
        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="listing_kind" id="kind-sale" value="sale" {{ $kindValue === 'sale' ? 'checked' : '' }}>
            <label class="btn btn-outline-primary" for="kind-sale">Bán</label>
            <input type="radio" class="btn-check" name="listing_kind" id="kind-rent" value="rent" {{ $kindValue === 'rent' ? 'checked' : '' }}>
            <label class="btn btn-outline-primary" for="kind-rent">Cho thuê</label>
        </div>
        @error('listing_kind') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="title" class="form-label">Tiêu đề</label>
        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $p?->title) }}">
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="property_type_id" class="form-label">Loại hình</label>
            <select id="property_type_id" name="property_type_id" class="form-select @error('property_type_id') is-invalid @enderror"></select>
            @error('property_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label for="price" class="form-label">Giá (đ)</label>
            <input type="number" min="0" id="price" name="price" class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price', $p?->price) }}">
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label for="area" class="form-label">Diện tích (m²)</label>
            <input type="number" step="0.1" min="0" id="area" name="area" class="form-control @error('area') is-invalid @enderror"
                   value="{{ old('area', $p?->area) }}">
            @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label for="bedrooms" class="form-label">Phòng ngủ</label>
            <input type="number" min="0" id="bedrooms" name="bedrooms" class="form-control" value="{{ old('bedrooms', $p?->bedrooms) }}">
        </div>
        <div class="col-md-3">
            <label for="bathrooms" class="form-label">Phòng tắm</label>
            <input type="number" min="0" id="bathrooms" name="bathrooms" class="form-control" value="{{ old('bathrooms', $p?->bathrooms) }}">
        </div>
        <div class="col-md-3">
            <label for="province_id" class="form-label">Tỉnh/Thành</label>
            <select id="province_id" name="province_id" class="form-select @error('province_id') is-invalid @enderror">
                <option value="">-- Chọn tỉnh/thành --</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->id }}" @selected(old('province_id', $p?->province_id) == $province->id)>{{ $province->name }}</option>
                @endforeach
            </select>
            @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label for="district_id" class="form-label">Quận/Huyện</label>
            <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror"></select>
            @error('district_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror"
               value="{{ old('address', $p?->address) }}">
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea id="description" name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $p?->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Ảnh hiện có (chỉ trang sửa) --}}
    @if ($p && $p->images->isNotEmpty())
        <div class="mb-3">
            <label class="form-label d-block">Ảnh hiện có</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($p->images as $img)
                    <img src="{{ Storage::url($img->image_path) }}" alt="" class="rounded border"
                         style="width:120px;height:90px;object-fit:cover">
                @endforeach
            </div>
        </div>
    @endif

    <div class="mb-4">
        <label for="images" class="form-label">{{ $p ? 'Tải thêm ảnh' : 'Ảnh' }}</label>
        <input type="file" id="images" name="images[]" accept="image/*" multiple
               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @error('images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-primary">{{ $p ? 'Lưu thay đổi' : 'Đăng tin' }}</button>
    <a href="{{ route('my-properties.index') }}" class="btn btn-link">Hủy</a>
</form>

<script type="application/json" id="types-data">
    @json($types->map->only(['id', 'name', 'for_sale', 'for_rent']))
</script>

<script>
(function () {
    const types = JSON.parse(document.getElementById('types-data').textContent);
    const typeSelect = document.getElementById('property_type_id');
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');
    const kindInputs = document.querySelectorAll('input[name="listing_kind"]');

    const currentKind = () => document.querySelector('input[name="listing_kind"]:checked')?.value ?? 'sale';

    function renderTypes(selected) {
        const key = currentKind() === 'sale' ? 'for_sale' : 'for_rent';
        typeSelect.innerHTML = types.filter(t => t[key])
            .map(t => `<option value="${t.id}" ${t.id == selected ? 'selected' : ''}>${t.name}</option>`).join('');
    }

    async function loadDistricts(provinceId, selected) {
        districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
        if (!provinceId) return;
        const res = await fetch('/api/provinces/' + provinceId + '/districts');
        const list = await res.json();
        districtSelect.innerHTML += list
            .map(d => `<option value="${d.id}" ${d.id == selected ? 'selected' : ''}>${d.name}</option>`).join('');
    }

    kindInputs.forEach(el => el.addEventListener('change', () => renderTypes(null)));
    provinceSelect.addEventListener('change', () => loadDistricts(provinceSelect.value, null));

    // Khởi tạo: giữ giá trị cũ khi sửa hoặc khi form quay lại sau lỗi validate
    renderTypes(@json(old('property_type_id', $p?->property_type_id)));
    loadDistricts(provinceSelect.value, @json(old('district_id', $p?->district_id)));
})();
</script>
