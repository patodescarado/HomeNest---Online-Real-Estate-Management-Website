<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModerationLog;
use App\Models\Property;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $statusCounts = [
            'approved' => Property::where('status', 'approved')->count(),
            'pending' => Property::where('status', 'pending')->count(),
            'rejected' => Property::where('status', 'rejected')->count(),
            'expired' => Property::where('status', 'expired')->count(),
        ];

        $regions = Property::query()
            ->join('districts', 'districts.district_id', '=', 'properties.district_id')
            ->join('provinces', 'provinces.province_id', '=', 'districts.province_id')
            ->select('provinces.province_id', 'provinces.name')
            ->selectRaw('count(*) as total')
            ->groupBy('provinces.province_id', 'provinces.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'pendingCount' => $statusCounts['pending'],
            'activeCount' => Property::approved()->count(),
            'userCount' => User::count(),
            'statusCounts' => $statusCounts,
            'regions' => $regions,
        ]);
    }

    public function pending(): View
    {
        return view('admin.properties.index', [
            'properties' => Property::where('status', 'pending')
                ->with(['owner', 'district.province'])
                ->latest()
                ->paginate(12),
            'pendingOnly' => true,
        ]);
    }

    public function properties(): View
    {
        return view('admin.properties.index', [
            'properties' => Property::with(['owner', 'district.province'])
                ->latest()
                ->paginate(12),
            'pendingOnly' => false,
        ]);
    }

    public function approve(Property $property): RedirectResponse
    {
        DB::transaction(function () use ($property) {
            $property = Property::query()->lockForUpdate()->findOrFail($property->property_id);
            abort_unless($property->status === 'pending', 409, 'Tin này không còn ở trạng thái chờ duyệt.');

            $property->forceFill([
                'status' => 'approved',
                'expires_at' => now()->addDays(30),
            ])->save();

            (new ModerationLog)->forceFill([
                'property_id' => $property->property_id,
                'admin_id' => auth()->id(),
                'action' => 'approved',
            ])->save();
        });

        return back()->with('success', 'Đã duyệt tin đăng.');
    }

    public function reject(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        DB::transaction(function () use ($property, $validated) {
            $property = Property::query()->lockForUpdate()->findOrFail($property->property_id);
            abort_unless($property->status === 'pending', 409, 'Tin này không còn ở trạng thái chờ duyệt.');

            $property->forceFill(['status' => 'rejected'])->save();

            (new ModerationLog)->forceFill([
                'property_id' => $property->property_id,
                'admin_id' => auth()->id(),
                'action' => 'rejected',
                'reason' => $validated['reason'],
            ])->save();
        });

        return back()->with('success', 'Đã từ chối tin đăng.');
    }

    public function destroy(Property $property, ImageUploadService $images): RedirectResponse
    {
        Gate::authorize('delete', $property);

        $images->deleteAll($property);
        $property->delete();

        return back()->with('success', 'Đã xóa tin đăng.');
    }
}
