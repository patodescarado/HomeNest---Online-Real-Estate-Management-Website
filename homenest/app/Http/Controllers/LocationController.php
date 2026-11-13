<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Province;
use App\Models\District;

class LocationController extends Controller
{
    public function districts(Province $province)
    {
        return $province->districts()->orderBy('name')->get(['district_id', 'name']);
    }
}