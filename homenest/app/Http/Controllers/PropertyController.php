<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\SearchPropertyRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertyImage;
use App\Models\Province;
use App\Models\District;
use App\Models\Favorite;
use App\Models\User;

class PropertyController extends Controller
{   
    public function show(Property $property)
    {
        $user = auth()->user();
        $canSee = $property->isPublic()
            || ($user && ($user->id === $property->owner_id || $user->isAdmin()));
        abort_unless($canSee, 404);

        if ($property->isPublic() && !session()->has("viewed.{$property->id}")) {
            $property->timestamps = false;        // lượt xem không làm đổi updated_at
            $property->increment('view_count');
            session(["viewed.{$property->id}" => true]);
        }

        $property->load(['coverImage', 'district.province', 'type', 'owner.profile']);
        return view('properties.show', compact('property'));
    }
    
    public function index(SearchPropertyRequest $request, ?string $kind = null, ?string $slug = null)
{
    $filters = $request->validated();

    if ($kind) {
        $filters['listing_kind'] = $kind;               // địa chỉ /mua-ban hay /cho-thue luôn thắng tham số trên URL
    }
    $type = null;
    if ($slug) {
        $type = PropertyType::where('slug', $slug)->firstOrFail();
        abort_unless($kind === 'sale' ? $type->for_sale : $type->for_rent, 404);
        $filters['type_id'] = $type->id;
    }

    // Người dùng nhập ngược min/max thì tự đổi chỗ
    foreach ([['price_min', 'price_max'], ['area_min', 'area_max']] as [$lo, $hi]) {
        if (isset($filters[$lo], $filters[$hi]) && $filters[$lo] > $filters[$hi]) {
            [$filters[$lo], $filters[$hi]] = [$filters[$hi], $filters[$lo]];
        }
    }

    $query = Property::approved()
        ->with(['district.province', 'coverImage'])
        ->when($filters['listing_kind'] ?? null, fn ($q, $v) => $q->where('listing_kind', $v))
        ->filter($filters);

    match ($filters['sort'] ?? 'newest') {
        'price_asc'  => $query->orderBy('price'),
        'price_desc' => $query->orderByDesc('price'),
        default      => $query->latest(),
    };

    return view('properties.index', [
        'properties'  => $query->paginate(12)->withQueryString(),
        'kind'        => $kind,
        'type'        => $type,
        'types'       => PropertyType::when($kind === 'sale', fn ($q) => $q->where('for_sale', true))
                            ->when($kind === 'rent', fn ($q) => $q->where('for_rent', true))
                            ->orderBy('sort_order')->get(),
        'provinces'   => Province::orderBy('name')->get(),
        'favoriteIds' => auth()->user()?->favoriteIds() ?? [],
    ]);
}
}