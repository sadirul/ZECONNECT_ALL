@extends('admin.layouts.app')

@section('content')
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Edit Profile</h2>
        <p class="text-sm text-slate-500">Update your admin profile information</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Profile Picture</label>
                <div class="mb-3">
                    @if ($admin->profile_pic)
                        <img src="{{ asset('storage/'.$admin->profile_pic) }}" alt="Profile picture" class="h-20 w-20 rounded-full object-cover ring-2 ring-slate-200">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-500 text-xl font-semibold text-white">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <input type="file" name="profile_pic" accept="image/*" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('profile_pic')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('email')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Shop Name</label>
                <input type="text" name="shop_name" value="{{ old('shop_name', $admin->shop_name) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('shop_name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Short Name</label>
                <input type="text" name="short_name" value="{{ old('short_name', $admin->short_name) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('short_name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Mobile</label>
                <input type="text" name="mobile" value="{{ old('mobile', $admin->mobile) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('mobile')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">WhatsApp</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $admin->whatsapp) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('whatsapp')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Address</label>
                <textarea name="address" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">{{ old('address', $admin->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
@endsection
