
@php
    $p = $property ?? null;

    $kindValue = old('listing_kind', $p?->listing_kind ?? 'sale');

    $selectedTypeId = old('type_id', $p?->type_id);
    $selectedProvinceId = old('province_id', $p?->province_id);
    $selectedDistrictId = old('district_id', $p?->district_id);

    $typesData = $types->map(function ($type) {
        return $type->only([
            'type_id',
            'name',
            'for_sale',
            'for_rent',
        ]);
    });
@endphp

<script type="application/json" id="types-data">
    @json($typesData)
</script>

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Hình thức --}}
    <div class="mb-3">
        <label class="form-label d-block">Hình thức</label>

        <div class="btn-group" role="group">
            <input
                type="radio"
                class="btn-check"
                name="listing_kind"
                id="kind-sale"
                value="sale"
                {{ $kindValue === 'sale' ? 'checked' : '' }}
            >

            <label class="btn btn-outline-primary" for="kind-sale">
                Bán
            </label>

            <input
                type="radio"
                class="btn-check"
                name="listing_kind"
                id="kind-rent"
                value="rent"
                {{ $kindValue === 'rent' ? 'checked' : '' }}
            >

            <label class="btn btn-outline-primary" for="kind-rent">
                Cho thuê
            </label>
        </div>

        @error('listing_kind')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tiêu đề --}}
    <div class="mb-3">
        <label for="title" class="form-label">Tiêu đề</label>

        <input
            type="text"
            id="title"
            name="title"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $p?->title) }}"
        >

        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Loại hình, giá, diện tích --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="type_id" class="form-label">Loại hình</label>

            <select
                id="type_id"
                name="type_id"
                class="form-select @error('type_id') is-invalid @enderror"
            >
                <option value="">-- Chọn loại hình --</option>

                @foreach ($types as $type)
                    <option
                        value="{{ $type->type_id }}"
                        @selected($selectedTypeId == $type->type_id)
                    >
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>

            @error('type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="price" class="form-label">Giá (đ)</label>

            <input
                type="number"
                min="0"
                id="price"
                name="price"
                class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price', $p?->price) }}"
            >

            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="area" class="form-label">Diện tích (m²)</label>

            <input
                type="number"
                step="0.1"
                min="0"
                id="area"
                name="area"
                class="form-control @error('area') is-invalid @enderror"
                value="{{ old('area', $p?->area) }}"
            >

            @error('area')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Phòng ngủ, phòng tắm, tỉnh thành, quận huyện --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label for="bedrooms" class="form-label">Số phòng ngủ</label>

            <input
                type="number"
                min="0"
                id="bedrooms"
                name="bedrooms"
                class="form-control"
                value="{{ old('bedrooms', $p?->bedrooms) }}"
            >
        </div>

        <div class="col-md-3">
            <label for="bathrooms" class="form-label">Số phòng tắm</label>

            <input
                type="number"
                min="0"
                id="bathrooms"
                name="bathrooms"
                class="form-control"
                value="{{ old('bathrooms', $p?->bathrooms) }}"
            >
        </div>

        <div class="col-md-3">
            <label for="province_id" class="form-label">Tỉnh/Thành</label>

            <select
                id="province_id"
                name="province_id"
                class="form-select @error('province_id') is-invalid @enderror"
            >
                <option value="">-- Chọn tỉnh/thành --</option>

                @foreach ($provinces as $province)
                    <option
                        value="{{ $province->province_id }}"
                        @selected($selectedProvinceId == $province->province_id)
                    >
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>

            @error('province_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label for="district_id" class="form-label">Quận/Huyện</label>

            <select
                id="district_id"
                name="district_id"
                class="form-select @error('district_id') is-invalid @enderror"
            >
                <option value="">-- Chọn quận/huyện --</option>

                @foreach ($districts as $district)
                    <option
                        value="{{ $district->district_id }}"
                        @selected($selectedDistrictId == $district->district_id)
                    >
                        {{ $district->name }}
                    </option>
                @endforeach
            </select>

            @error('district_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Địa chỉ --}}
    <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>

        <input
            type="text"
            id="address"
            name="address"
            class="form-control @error('address') is-invalid @enderror"
            value="{{ old('address', $p?->address) }}"
        >

        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Mô tả --}}
    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>

        <textarea
            id="description"
            name="description"
            rows="6"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $p?->description) }}</textarea>

        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Ảnh hiện có --}}
    @if ($p && $p->images->isNotEmpty())
        <div class="mb-3">
            <label class="form-label d-block">Ảnh hiện có</label>

            <div class="d-flex flex-wrap gap-2">
                @foreach ($p->images as $img)
                    <img
                        src="{{ Storage::url($img->image_path) }}"
                        alt=""
                        class="rounded border"
                        style="width:120px;height:90px;object-fit:cover"
                    >
                @endforeach
            </div>
        </div>
    @endif

    {{-- Tải ảnh --}}
    <div class="mb-4">
        <label for="images" class="form-label">
            {{ $p ? 'Tải thêm ảnh' : 'Ảnh' }}
        </label>

        <input
            type="file"
            id="images"
            name="images[]"
            accept="image/*"
            multiple
            class="form-control
                @error('images') is-invalid @enderror
                @error('images.*') is-invalid @enderror"
        >

        @error('images')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @error('images.*')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">
        {{ $p ? 'Lưu thay đổi' : 'Đăng tin' }}
    </button>

    <a href="{{ route('my-properties.index') }}" class="btn btn-link">
        Hủy
    </a>
</form>

<script>
(function () {
    const typesElement = document.getElementById('types-data');

    if (!typesElement) return;

    const types = JSON.parse(typesElement.textContent);

    const typeSelect = document.getElementById('type_id');
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');

    const kindInputs = document.querySelectorAll(
        'input[name="listing_kind"]'
    );

    const selectedType = @json($selectedTypeId);
    const selectedDistrict = @json($selectedDistrictId);

    const currentKind = () => {
        return document.querySelector(
            'input[name="listing_kind"]:checked'
        )?.value ?? 'sale';
    };

    function renderTypes(selected = null) {
        const key = currentKind() === 'sale'
            ? 'for_sale'
            : 'for_rent';

        typeSelect.innerHTML =
            '<option value="">-- Chọn loại hình --</option>';

        types
            .filter(type => Number(type[key]) === 1)
            .forEach(type => {
                const option = document.createElement('option');

                option.value = type.type_id;
                option.textContent = type.name;

                option.selected =
                    String(type.type_id) === String(selected ?? '');

                typeSelect.appendChild(option);
            });
    }

    async function loadDistricts(provinceId, selected = null) {
        districtSelect.innerHTML =
            '<option value="">-- Chọn quận/huyện --</option>';

        if (!provinceId) return;

        try {
            const response = await fetch(
                `/api/provinces/${provinceId}/districts`
            );

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const districts = await response.json();

            districts.forEach(district => {
                const option = document.createElement('option');

                option.value = district.district_id;
                option.textContent = district.name;

                option.selected =
                    String(district.district_id) === String(selected ?? '');

                districtSelect.appendChild(option);
            });

            console.log('Selected district:', selected);
            console.log('Available districts:', districts);

        } catch (error) {
            console.error('Không thể tải quận/huyện:', error);
        }
    }

    kindInputs.forEach(input => {
        input.addEventListener('change', () => {
            renderTypes(null);
        });
    });

    provinceSelect.addEventListener('change', () => {
        loadDistricts(provinceSelect.value, null);
    });

    renderTypes(selectedType);

    loadDistricts(
        provinceSelect.value,
        selectedDistrict
    );
})();
</script>
