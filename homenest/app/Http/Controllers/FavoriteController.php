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


class FavoriteController extends Controller
{
        public function index()
    {
        $properties = auth()->user()->favorites()
            ->approved()
            ->with(['district.province', 'coverImage'])
            ->orderByPivot('created_at', 'desc')
            ->paginate(12);

        return view('favorites.index', [
            'properties'  => $properties,
            'favoriteIds' => auth()->user()->favoriteIds(),
        ]);
    }

    public function toggle(Property $property)
    {
        abort_unless($property->isPublic(), 404);
        $result = auth()->user()->favorites()->toggle($property->id);
        return response()->json(['saved' => count($result['attached']) > 0]);
    }
}
