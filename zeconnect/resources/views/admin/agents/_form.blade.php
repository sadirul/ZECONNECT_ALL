@csrf
@php($agent = $agent ?? null)

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
        <input type="text" name="name" value="{{ old('name', $agent->name ?? '') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
        @error('name')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Mobile</label>
        <input type="text" name="mobile" value="{{ old('mobile', $agent->mobile ?? '') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
        @error('mobile')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $agent->email ?? '') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
        @error('email')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Profile Picture</label>
        <input type="file" name="profile_pic" accept="image/*" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
        @error('profile_pic')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror

        @if (!empty($agent?->profile_pic))
            <img src="{{ asset('storage/'.$agent->profile_pic) }}" alt="Profile picture" class="mt-2 h-12 w-12 rounded-full object-cover">
        @endif
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Password {{ isset($agent) ? '(leave blank to keep current)' : '' }}</label>
        <input type="password" name="password" {{ isset($agent) ? '' : 'required' }} class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
        @error('password')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Confirm Password</label>
        <input type="password" name="password_confirmation" {{ isset($agent) ? '' : 'required' }} class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Address</label>
        <textarea name="address" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">{{ old('address', $agent->address ?? '') }}</textarea>
        @error('address')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $agent->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            Active
        </label>
    </div>
</div>

<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        {{ $submitLabel ?? 'Save' }}
    </button>
    <a href="{{ route('admin.agents.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
        Cancel
    </a>
</div>
