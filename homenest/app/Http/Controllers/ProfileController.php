<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', [
            'user' => auth()->user()
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated) {

            // Cập nhật email trong bảng users
            $user->email = $validated['email'];

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Cập nhật hoặc tạo thông tin trong user_profiles
            $user->profile()->updateOrCreate(
                [],
                [
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                ]
            );
        });

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

        // HomeController
    public function index()
    {
        return view('home', [
            'latest' => Property::approved()->with(['district.province', 'coverImage'])->latest()->take(8)->get(),
        ]);
    }

}