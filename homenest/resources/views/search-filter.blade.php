<form method="GET" action="{{ url()->current() }}" class="row g-2 mb-4">
  <div class="col-12">
    <input name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Từ khóa: tên đường, dự án, mô tả...">
  </div>
  <div class="col-6 col-md-3">
    <select name="type_id" class="form-select">
      <option value="">Loại hình</option>
      @foreach($types as $t)
        <option value="{{ $t->id }}" @selected(request('type_id', $type->id ?? null) == $t->id)>{{ $t->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-6 col-md-3">
    <select name="province_id" id="province" class="form-select" data-url="{{ url('/api/provinces') }}">
      <option value="">Tỉnh / Thành</option>
      @foreach($provinces as $p)
        <option value="{{ $p->id }}" @selected(request('province_id') == $p->id)>{{ $p->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-6 col-md-3">
    <select name="district_id" id="district" class="form-select" data-selected="{{ request('district_id') }}">
      <option value="">Quận / Huyện</option>
    </select>
  </div>
  <div class="col-6 col-md-3">
    <select name="bedrooms" class="form-select">
      <option value="">Số phòng ngủ</option>
      @foreach([1,2,3,4] as $n)
        <option value="{{ $n }}" @selected(request('bedrooms') == $n)>Từ {{ $n }} phòng</option>
      @endforeach
    </select>
  </div>
  <div class="col-6 col-md-3"><input name="price_min" type="number" min="0" value="{{ request('price_min') }}" class="form-control" placeholder="Giá từ (VND)"></div>
  <div class="col-6 col-md-3"><input name="price_max" type="number" min="0" value="{{ request('price_max') }}" class="form-control" placeholder="Giá đến (VND)"></div>
  <div class="col-6 col-md-3"><input name="area_min" type="number" min="0" value="{{ request('area_min') }}" class="form-control" placeholder="Diện tích từ (m²)"></div>
  <div class="col-6 col-md-3"><input name="area_max" type="number" min="0" value="{{ request('area_max') }}" class="form-control" placeholder="Diện tích đến (m²)"></div>
  <div class="col-12 d-flex gap-2 justify-content-between">
    <select name="sort" class="form-select w-auto">
      <option value="newest"     @selected(request('sort') === 'newest')>Mới nhất</option>
      <option value="price_asc"  @selected(request('sort') === 'price_asc')>Giá tăng dần</option>
      <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option>
    </select>
    <div class="d-flex gap-2">
      <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
      <button class="btn btn-primary">Tìm kiếm</button>
    </div>
  </div>
</form>