@extends('admin.layouts.app')

@section('content')
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Banner Module</h2>
        <p class="text-sm text-slate-500">Add and delete banners (title is optional)</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-slate-800">Add Banner</h3>

        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Title (optional)</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('title')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Image</label>
                <input type="file" name="image" accept="image/*" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                @error('image')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Add Banner
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-slate-800">All Banners</h3>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($banners as $banner)
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <img src="{{ asset('storage/'.$banner->image) }}" alt="Banner image" class="h-40 w-full object-cover">
                    <div class="space-y-3 p-3">
                        <p class="text-sm font-medium text-slate-700">{{ $banner->title ?: 'No title' }}</p>
                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" data-confirm="Delete this banner?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md border border-rose-200 px-3 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-sm text-slate-500">No banners found.</p>
            @endforelse
        </div>
    </div>

    <div>
        {{ $banners->links() }}
    </div>
@endsection
