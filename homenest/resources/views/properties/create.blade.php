@extends('layouts.site')

@section('title', 'Đăng tin mới - HomeNest')

@section('content')
<section class="hn-create-section">
    <div class="hn-create-container">
        <h1 class="hn-page-title mb-3">Đăng tin mới</h1>

        @if($errors->any())
            <div class="alert alert-danger"><strong>Vui lòng kiểm tra lại thông tin:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" class="hn-create-panel">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="title">Tiêu đề</label>
                <input id="title" name="title" class="form-control hn-control" value="{{ old('title') }}" required>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="listing_kind">Hình thức (Mua / Thuê)</label>
                    <select id="listing_kind" name="listing_kind" class="form-select hn-control" required>
                        <option value="">Chọn hình thức</option>
                        <option value="sale" @selected(old('listing_kind') === 'sale')>Mua bán</option>
                        <option value="rent" @selected(old('listing_kind') === 'rent')>Cho thuê</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="type_id">Loại hình</label>
                    <select id="type_id" name="type_id" class="form-select hn-control" required>
                        <option value="">Chọn loại hình</option>
                        @foreach(($propertyTypes ?? collect()) as $type)<option value="{{ $type->id }}" @selected((string)old('type_id') === (string)$type->id)>{{ $type->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="province_id">Tỉnh / Thành</label>
                    <select id="province_id" name="province_id" class="form-select hn-control" required>
                        <option value="">Chọn tỉnh / thành</option>
                        @foreach(($provinces ?? collect()) as $province)<option value="{{ $province->id }}" @selected((string)old('province_id') === (string)$province->id)>{{ $province->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="district_id">Quận / Huyện</label>
                    <select id="district_id" name="district_id" class="form-select hn-control" required>
                        <option value="">Chọn quận / huyện</option>
                        @foreach(($districts ?? collect()) as $district)<option value="{{ $district->id }}" @selected((string)old('district_id') === (string)$district->id)>{{ $district->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="price">Giá (VNĐ)</label>
                    <input id="price" name="price" type="number" min="0" step="1000" class="form-control hn-control" value="{{ old('price') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="area">Diện tích (m²)</label>
                    <input id="area" name="area" type="number" min="0" step="0.1" class="form-control hn-control" value="{{ old('area') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="bedrooms">Số phòng ngủ</label>
                    <input id="bedrooms" name="bedrooms" type="number" min="0" class="form-control hn-control" value="{{ old('bedrooms') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="bathrooms">Số phòng tắm</label>
                    <input id="bathrooms" name="bathrooms" type="number" min="0" class="form-control hn-control" value="{{ old('bathrooms') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="address">Địa chỉ</label>
                    <input id="address" name="address" class="form-control hn-control" value="{{ old('address') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="description">Mô tả</label>
                    <textarea id="description" name="description" rows="4" class="form-control hn-control hn-textarea">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="hn-upload-box mt-3">
                <label for="images" class="hn-upload-label">
                    <strong>Kéo thả ảnh vào đây hoặc bấm để chọn</strong>
                    <span>(tối đa 10 ảnh, chọn 1 ảnh bìa)</span>
                </label>
                <input id="images" name="images[]" type="file" accept="image/*" multiple class="form-control" hidden>
                <div id="hn-image-preview" class="hn-image-preview"></div>
                <small class="hn-muted d-block mt-2">Ảnh đầu tiên được chọn sẽ làm ảnh bìa. Việc lưu ảnh cần được xử lý trong PropertyController.</small>
            </div>

            <div class="hn-map-placeholder mt-3">Bấm lên bản đồ để chọn vị trí</div>

            <div class="hn-form-actions">
                <button type="submit" name="submit_action" value="draft" class="btn hn-btn hn-btn-outline">Lưu nháp</button>
                <button type="submit" name="submit_action" value="submit" class="btn hn-btn hn-btn-primary">Gửi duyệt</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('images');
    const preview = document.getElementById('hn-image-preview');
    const label = document.querySelector('.hn-upload-label');
    if (!input || !preview) return;

    label.addEventListener('click', () => input.click());
    input.addEventListener('change', () => {
        preview.innerHTML = '';
        const files = Array.from(input.files || []);
        if (files.length > 10) {
            alert('Bạn chỉ được chọn tối đa 10 ảnh.');
            input.value = '';
            return;
        }
        files.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;
            const wrapper = document.createElement('div');
            wrapper.className = 'hn-preview-item' + (index === 0 ? ' is-cover' : '');
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            wrapper.appendChild(img);
            if (index === 0) {
                const badge = document.createElement('span');
                badge.textContent = 'Ảnh bìa';
                wrapper.appendChild(badge);
            }
            preview.appendChild(wrapper);
        });
    });
});
</script>
@endpush
