<?php

namespace App\Http\Controllers;
    
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchPropertyRequest;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\District;
use App\Models\Property;
use App\Models\PropertyType;
use App\Http\Requests\StorePropertyRequest;
use App\Services\ImageUploadService;

class MyPropertyController extends Controller
{
    public function create()
{
    $provinces = Province::orderBy('name')->get();
    $districts = District::orderBy('name')->get();
    $types = PropertyType::orderBy('name')->get();

    return view('my-properties.create', [
        'property' => null,
        'provinces' => $provinces,
        'districts' => $districts,
        'types' => $types,
    ]);
}

public function edit(Property $property)
{
    $provinces = Province::orderBy('name')->get();
    $districts = District::orderBy('name')->get();
    $types = PropertyType::orderBy('name')->get();

    return view('my-properties.edit', [
        'property' => $property,
        'provinces' => $provinces,
        'districts' => $districts,
        'types' => $types,
    ]);
}

    public function index()
{
    $properties = auth()->user()->properties()
        ->with('coverImage')
        ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
        ->latest()->paginate(10);
    return view('my-properties.index', compact('properties'));
}

public function store(StorePropertyRequest $request, ImageUploadService $images)
{
    DB::transaction(function () use ($request, $images) {
        $property = Property::create(
            $request->safe()->except(['images', 'cover']) + ['owner_id' => auth()->id()]
        );                                           // status mặc định 'pending' từ database
        $images->store($property, $request->file('images'), (int) $request->input('cover', 0));
    });
    return redirect()->route('my-properties.index')
        ->with('success', 'Tin đã được gửi, đang chờ quản trị viên duyệt.');
}

public function update(UpdatePropertyRequest $request, Property $property, ImageUploadService $images)
{
    Gate::authorize('update', $property);
    DB::transaction(function () use ($request, $property, $images) {
        $property->update($request->safe()->except(['images', 'cover']));
        if ($request->hasFile('images')) {
            $images->store($property, $request->file('images'), -1);   // ảnh mới, không đổi ảnh bìa
        }
        if (in_array($property->status, ['rejected', 'approved'])) {
            $property->forceFill(['status' => 'pending'])->save();
        }
    });
    return redirect()->route('my-properties.index')->with('success', 'Đã cập nhật, tin chờ duyệt lại.');
}

public function destroy(Property $property, ImageUploadService $images)
{
    Gate::authorize('delete', $property);
    $images->deleteAll($property);
    $property->delete();
    return back()->with('success', 'Đã xóa tin.');
}

public function hide(Property $property)
{
    Gate::authorize('update', $property);
    abort_unless($property->status === 'approved', 422);
    $property->forceFill(['status' => 'hidden'])->save();
    return back();
}

public function unhide(Property $property)
{
    Gate::authorize('update', $property);
    abort_unless($property->status === 'hidden' && $property->expires_at?->isFuture(), 422);
    $property->forceFill(['status' => 'approved'])->save();
    return back();
}

public function renew(Property $property)
{
    Gate::authorize('update', $property);
    abort_unless(in_array($property->status, ['approved', 'expired']), 422);
    $base = $property->expires_at?->isFuture() ? $property->expires_at : now();
    $property->forceFill(['status' => 'approved', 'expires_at' => $base->copy()->addDays(30)])->save();
    return back()->with('success', 'Đã gia hạn thêm 30 ngày.');
}
}
