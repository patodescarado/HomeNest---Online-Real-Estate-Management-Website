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
use App\Models\PropertyType;
use App\Models\PropertyImage;
use App\Models\Province;
class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'latest' => Property::approved()->with(['district.province', 'image'])->latest()->take(8)->get(),
            'provinces' => Province::all(),
        ]);
}
}