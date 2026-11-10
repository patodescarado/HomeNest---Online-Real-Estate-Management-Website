<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Property;
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
    public function index(Request $request, ?string $kind = null, ?string $slug = null)
    {
        $query = Property::approved()->with(['district.province', 'coverImage']);
        $type  = null;

    if ($kind) {
        $query->where('listing_kind', $kind);
    }
    if ($slug) {
        $type = PropertyType::where('slug', $slug)->firstOrFail();
        abort_unless($kind === 'sale' ? $type->for_sale : $type->for_rent, 404);
        $query->where('type_id', $type->id);
    }

    match ($request->query('sort')) {
        'price_asc'  => $query->orderBy('price'),
        'price_desc' => $query->orderByDesc('price'),
        default      => $query->latest(),
    };

    return view('properties.index', [
        'properties' => $query->paginate(12)->withQueryString(),
        'kind' => $kind, 'type' => $type,
    ]);
    }
}