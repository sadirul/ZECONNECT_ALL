<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $admin = $request->user('admin');

        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shop_name' => ['required', 'string', 'max:255'],
            'short_name' => ['required', 'string', 'max:50'],
            'mobile' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,'.$admin->id],
            'address' => ['nullable', 'string'],
            'profile_pic' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('profile_pic')) {
            if (! empty($admin->profile_pic)) {
                Storage::disk('public')->delete($admin->profile_pic);
            }

            $validated['profile_pic'] = $request->file('profile_pic')->store('admins', 'public');
        }

        $admin->update($validated);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function editPassword(): View
    {
        return view('admin.settings.password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $admin = $request->user('admin');

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ]);

        $admin->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.settings.password.edit')
            ->with('success', 'Password changed successfully.');
    }
}
