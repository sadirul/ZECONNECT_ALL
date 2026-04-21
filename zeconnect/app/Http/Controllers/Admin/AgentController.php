<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(): View
    {
        $agents = User::query()
            ->where('role', 'agent')
            ->latest()
            ->paginate(10);

        return view('admin.agents.index', compact('agents'));
    }

    public function create(): View
    {
        return view('admin.agents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'address' => ['nullable', 'string'],
            'profile_pic' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('profile_pic')) {
            $validated['profile_pic'] = $request->file('profile_pic')->store('agents', 'public');
        }

        $validated['role'] = 'agent';
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent created successfully.');
    }

    public function edit(User $agent): View
    {
        abort_if($agent->role !== 'agent', 404);

        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent): RedirectResponse
    {
        abort_if($agent->role !== 'agent', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$agent->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'address' => ['nullable', 'string'],
            'profile_pic' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('profile_pic')) {
            if ($agent->profile_pic) {
                Storage::disk('public')->delete($agent->profile_pic);
            }

            $validated['profile_pic'] = $request->file('profile_pic')->store('agents', 'public');
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['role'] = 'agent';

        $agent->update($validated);

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent updated successfully.');
    }

    public function destroy(User $agent): RedirectResponse
    {
        abort_if($agent->role !== 'agent', 404);

        if ($agent->profile_pic) {
            Storage::disk('public')->delete($agent->profile_pic);
        }

        $agent->delete();

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent deleted successfully.');
    }
}
